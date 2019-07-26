<?php

namespace App\Console\Commands;

use App\Eloquents\Restaurant;
use Illuminate\Console\Command;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\PlacesApi;
use Log;
use OutOfBoundsException;
use Throwable;

class FetchRestaurant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:restaurant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Google Place APIから飲食店情報を取得';

    /** @var PlacesApi */
    private $placesApi;

    /** @var Restaurant */
    private $restaurant;

    /**
     * Create a new command instance.
     *
     * @param PlacesApi $placesApi
     * @param Restaurant $restaurant
     */
    public function __construct(PlacesApi $placesApi, Restaurant $restaurant)
    {
        $this->placesApi = $placesApi->setKey(config('nga.google_api_key'));
        $this->restaurant = $restaurant;

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->restaurant->all() as $restaurant) {
            if (!$restaurant->can_auto_update) {
                continue;
            }
            if ($restaurant->is_closed) {
                continue;
            }
            try {
                $changed = $this->registerGooglePlaceId($restaurant);
                $this->update($changed);
            } catch (OutOfBoundsException $e) {
                Log::warning($e->getMessage());
                dump($e->getMessage());
                continue;
            } catch (GooglePlacesApiException $e) {
                Log::warning($e->getMessage() . " id:{$restaurant->id}, name:{$restaurant->name}");
                dump($e->getMessage());
                continue;
            } catch (Throwable $e) {
                $error = $e->getMessage() . PHP_EOL . " id:{$restaurant->id}, name:{$restaurant->name}";
                //TODO エラーを外部に通知する
                Log::alert($error);
                dump($error);
                continue;
            }
        }
    }

    /**
     * @param Restaurant $restaurant
     * @return array
     */
    private function optionParams(Restaurant $restaurant): array
    {
        $arr = [
            'language' => 'ja',
        ];

        //座標登録地点から半径10km以内の条件追加
        if ($restaurant->longitude && $restaurant->latitude) {
            $arr += [
                'location' => $restaurant->longitude . ',' . $restaurant->latitude,
                'radius' => 10000,
            ];
        }

        return $arr;
    }

    /**
     * google_place_idを取得し登録する
     * 店名でキーワード検索、見つからない場合はwarning出して後ほど手動設定する
     * キーワード検索結果先頭の店舗を登録するため、2019新規登録店舗の初回取得時は目視確認必要
     * 2018から引き継ぎの店舗の場合は座標登録されているため誤検出はないはず
     * @param Restaurant $restaurant
     * @return Restaurant
     * @throws GooglePlacesApiException
     */
    private function registerGooglePlaceId(Restaurant $restaurant): Restaurant
    {
        if ($restaurant->google_place_id) {
            return $restaurant;
        }

        $result = $this->placesApi->placeAutocomplete(
            $restaurant->google_search_keyword ?? $restaurant->name, $this->optionParams($restaurant));

        //検索結果0件の場合
        if ($result->get('status') !== 'OK') {
            $error = "google placeAutocomplete return not found. id:{$restaurant->id}, name:{$restaurant->name}";
            throw new OutOfBoundsException($error);
        }

        $place = $result->get('predictions')->first();
        $restaurant->fill(['google_place_id' => $place['place_id']])->save();

        return $restaurant;
    }

    /**
     * 住所・電話番号は既存登録のものを優先する
     * @param Restaurant $restaurant
     * @throws GooglePlacesApiException
     */
    private function update(Restaurant $restaurant): void
    {
        $place = $this->placesApi
            ->placeDetails($restaurant->google_place_id, $this->optionParams($restaurant))
            ->get('result');

        $restaurant->fill([
            'google_url' => $place['url'],
            //英数字を半角化
            'address' => $restaurant->address ?? mb_convert_kana($place['vicinity'] ?? '', 'a'),
            'latitude' => $place['geometry']['location']['lat'],
            'longitude' => $place['geometry']['location']['lng'],
            'tel' => $restaurant->tel ?? $place['formatted_phone_number'] ?? null,
            'business_hours' => $this->formatWeekdayText($place['opening_hours']['weekday_text'] ?? []),
        ])->save();
    }

    /**
     * @param array $weekday
     * @return string
     */
    private function formatWeekdayText(array $weekday): string
    {
        return collect($weekday)->reduce(function ($carry, $item) {
            return $carry . $item . '<br>';
        }, '');
    }
}

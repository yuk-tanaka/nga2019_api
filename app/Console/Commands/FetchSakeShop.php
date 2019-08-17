<?php

namespace App\Console\Commands;

use App\Eloquents\SakeShop;
use Illuminate\Console\Command;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\PlacesApi;
use Log;
use OutOfBoundsException;
use Throwable;

/**
 * 2019に関しては件数少ないため事前にGoogleApiKey取得しておく
 * TODO:FetchRestaurantとの基底GoogleApiクラスの実装(現状はほぼコピペ)
 * @package App\Console\Commands
 */
class FetchSakeShop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:sakeShop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Google Place APIから酒販店情報を取得';

    /** @var PlacesApi */
    private $placesApi;

    /** @var SakeShop */
    private $sakeShop;

    /**
     * Create a new command instance.
     *
     * @param PlacesApi $placesApi
     * @param SakeShop $sakeShop
     */
    public function __construct(PlacesApi $placesApi, SakeShop $sakeShop)
    {
        $this->placesApi = $placesApi->setKey(config('nga.google_api_key'));
        $this->sakeShop = $sakeShop;

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->sakeShop->all() as $sakeShop) {
            try {
                $this->update($sakeShop);
            } catch (OutOfBoundsException $e) {
                Log::warning($e->getMessage());
                dump($e->getMessage());
                continue;
            } catch (GooglePlacesApiException $e) {
                Log::warning($e->getMessage() . " id:{$sakeShop->id}, name:{$sakeShop->name}");
                dump($e->getMessage());
                continue;
            } catch (Throwable $e) {
                $error = $e->getMessage() . PHP_EOL . " id:{$sakeShop->id}, name:{$sakeShop->name}";
                Log::alert($error);
                dump($error);
                continue;
            }
        }
    }

    /**
     * @return array
     */
    private function optionParams(): array
    {
        return [
            'language' => 'ja',
        ];
    }


    /**
     * 住所・電話番号は既存登録のものを優先する
     * @param SakeShop $sakeShop
     * @throws GooglePlacesApiException
     */
    private function update(SakeShop $sakeShop): void
    {
        $place = $this->placesApi
            ->placeDetails($sakeShop->google_place_id, $this->optionParams())
            ->get('result');

        $sakeShop->fill([
            'google_url' => $place['url'],
            'address' => $sakeShop->address ?? mb_convert_kana($place['vicinity'] ?? '', 'a'),
            'tel' => $sakeShop->tel ?? $place['formatted_phone_number'] ?? null,
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

<?php

namespace App\Console\Commands;

use App\Eloquents\Brewery;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Log;
use OutOfBoundsException;
use Throwable;

class FetchBrewery extends Command
{
    /** @var string */
    protected $signature = 'fetch:brewery';

    /** @var string */
    protected $description = 'sakenote APIから酒蔵情報を取得';

    /** @var Client */
    private $guzzleClient;

    /** @var Brewery */
    private $brewery;

    /**
     * Create a new command instance.
     *
     * @param Client $guzzleClient
     * @param Brewery $brewery
     */
    public function __construct(Client $guzzleClient, Brewery $brewery)
    {
        $this->guzzleClient = $guzzleClient;
        $this->brewery = $brewery;

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->brewery->all() as $brewery) {
            try {
                $changed = $this->registerSakenoteMakerIdAndCompanyName($brewery);
                $this->update($changed);
            } catch (OutOfBoundsException $e) {
                Log::warning($e->getMessage());
                dump($e->getMessage());
                continue;
            } catch (GuzzleException $e) {
                Log::warning($e->getMessage());
                dump($e->getMessage());
                continue;
            } catch (Throwable $e) {
                $error = $e->getMessage() . PHP_EOL . " id:{$brewery->id}, name:{$brewery->name}";
                //TODO エラーを外部に通知する
                Log::alert($error);
                dump($error);
                continue;
            }
        }
    }

    /**
     * sakenote_maker_idおよびcompany_nameを取得し登録する（apiがidによる取得に対応していないため）
     * 銘柄名でキーワード検索、見つからない場合はwarning出して後ほど手動設定する
     * キーワード検索結果先頭の店舗を登録するため、2019新規登録店舗の初回取得時は目視確認必要
     * 2018から引き継ぎの店舗の場合は座標登録されているため誤検出はないはず
     * @param Brewery $brewery
     * @return Brewery
     * @throws GuzzleException
     */
    private function registerSakenoteMakerIdAndCompanyName(Brewery $brewery): Brewery
    {
        if ($brewery->sakenote_maker_id) {
            return $brewery;
        }
        $url = config('nga.sakenote_api_url') . 'sakes';
        $response = $this->guzzleClient->request('GET', $url, [
            'query' => [
                'sake_name' => $brewery->name,
                'token' => config('nga.sakenote_api_key'),
            ],
        ]);

        $result = collect(json_decode($response->getBody()->getContents()));

        //検索結果0件の場合
        if (!$result->get('num_pages')) {
            $error = "sakenote return not found. id:{$brewery->id}, name:{$brewery->name}";
            throw new OutOfBoundsException($error);
        }

        $brewery->fill([
            'company_name' => $result->get('sakes')[0]->maker_name,
            'sakenote_maker_id' => $result->get('sakes')[0]->maker_id,
        ])->save();

        return $brewery;
    }

    /**
     * 酒蔵名でapi取得（uniqueではない）ののち、maker_idで絞り込む
     * @param Brewery $brewery
     * @throws GuzzleException
     */
    private function update(Brewery $brewery): void
    {
        $url = config('nga.sakenote_api_url') . 'makers';
        $response = $this->guzzleClient->request('GET', $url, [
            'query' => [
                'maker_name' => $brewery->company_name,
                'token' => config('nga.sakenote_api_key'),
            ],
        ]);

        $maker = collect(json_decode($response->getBody()->getContents())->makers)
            ->filter(function ($item) use ($brewery) {
                return (string)$item->maker_id === $brewery->sakenote_maker_id;
            })->first();

        $brewery->fill([
            'address' => $maker->maker_address,
            'web' => $maker->maker_url,
        ])->save();
    }
}

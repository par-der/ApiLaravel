<?php

namespace App\Ozon;


use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OzonApi
{

    protected $response = null;

    public function __construct()
    {

    }

    /**
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function ozonApi(){
            return Http::retry(3, 100)->withHeaders([
                'Content-Type' => 'application/json',
                'Client-Id' => env("OZON_API_CLIENT_ID"),
                'Api-Key' => env("OZON_API_KEY"),
            ])->baseUrl('https://api-seller.ozon.ru/');
    }

    /**
     * @param string $url
     * @param array $query
     * @return array
     */
    public function get(string $url, array $query = []): array
    {
        $response = $this->ozonApi()->get($url, $query)->body();
        $this->response = $response;
        return $response->json();
    }

    /**
     * @param string $url
     * @param array $query
     * @return array
     */
    public function post(string $url, array $query = []): array
    {
        $response = $this->ozonApi()->post($url, $query);
        $this->response = $response;
        return $response->json();
    }

    /**
     * @return Response
     */
    public function getResponse(): Response {
        return $this->response;
    }

    /**
     * @param array $filter
     * @param string $last_id
     * @param int $limit
     * @return array
     */
    public function productInfoStocksV3(array $filter, string $last_id = '', int $limit = 100): array {
        return $this->post('/v3/product/info/stocks', [
            'filter' => $filter,
            'last_id' => $last_id,
            'limit' => $limit,
        ]);
    }

    /**
     * @param array $filter
     * @param string $dir
     * @param int $limit
     * @param int $offset
     * @param bool $translit
     * @param array $with
     * @return array
     */
    public function postingFboListV2(array $filter, int $offset = 0, string $dir = 'asc', int $limit = 1000, bool $translit = false, array $with = []): array {
        return $this->post('/v2/posting/fbo/list', [
            'dir' => $dir,
            'filter' => $filter,
            'limit' => $limit,
            'offset' => $offset,
            'translit' => $translit,
            'with' => [
                'analytics_data' => true,
                'barcodes' => true,
                'financial_data' => true,
            ],
        ]);
    }

//    public function postingFbsListV3(array $filter, int $offset = 0, string $dir = 'asc', int $limit = 1000, array $with = []): array {
//        return $this->post('v3/posting/fbs/list', [
//            'dir' => $dir,
//            'filter' => $filter,
//            'limit' => $limit,
//            'offset' => $offset,
//            // 'with' => $with,
//        ]);
//    }
}

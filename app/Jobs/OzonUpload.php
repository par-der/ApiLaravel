<?php
namespace App\Jobs;
use App\Models\Ozon\OzonInfoStock;
use App\Models\Ozon\OzonPostingFbo;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Ozon\OzonApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class OzonUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $api = null;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param OzonApi $api
     * @return void
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function handle(OzonApi $api): void
    {
        $this->api = $api;
        $this->productInfoStocksV3($api);
        $this->postingFboListV2($api);
    }

    /**
     * @param OzonApi $api
     * @return void
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function productInfoStocksV3(OzonApi $api){


        $result = $api->productInfoStocksV3(['visibility' =>  'ALL']);
        $items = $result['result']['items'];
        do {
            $dataForSave = [];


            foreach ($items as $item) {
                //dd($item['stocks'][0]['present']);

                $dataForSave[] = [
                    'product_id' => $item['product_id'],
                    'offer_id' => $item['offer_id'],
                    'fbo_present' => $item['stocks'][0]['present'] ?? null,
                    'fbo_reserved' => $item['stocks'][0]['reserved'] ?? null,
                    'fbs_present' => $item['stocks'][1]['present'] ?? null,
                    'fbs_reserved' => $item['stocks'][1]['reserved'] ?? null,
                    'date' => date("Y-m-d"),
                ];
            }
            $dataForSaveChunks = array_chunk($dataForSave, 100);
            foreach ($dataForSaveChunks as $chunk) {
                //dd($dataForSaveChunks);
                OzonInfoStock::upsert($chunk, ['product_id']);
            }
            $result = $api->productInfoStocksV3(['visibility' =>  'ALL']);
            $response = $api->getResponse();
            $items = $result['result']['items'];
        } while(!empty($items['result']['items']));
        if(!$response->successful())
        {
            $response->throw();
        }
    }

    /**
     * @param OzonApi $api
     * @return void
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function postingFboListV2(OzonApi $api)
    {

        $offset = 0;
        $result = $api->postingFboListV2(["since" => "2020-09-01T00:00:00.000Z",
            "status" => "",
            "to" => "2023-11-17T10:44:12.828Z"], 'asc', 100, $offset);
        //dd($res);
        $items = $result['result'];
        do {
            $dataForSave = [];

            foreach ($items as $item) {
                //dd($item);

                for ($i = 0; $i < count($item['products']); $i++) {

                    $dataForSave[] = [
                        'order_id' => $item['order_id'],
                        'order_number' => $item['order_number'],
                        'posting_number' => $item['posting_number'],
                        'status' => $item['status'],
                        'cancel_reason_id' => $item['cancel_reason_id'],
                        'created_at' => (new DateTime($item['created_at']))->format('Y-m-d H:i:s'),
                        'in_process_at' => (new DateTime($item['in_process_at']))->format('Y-m-d H:i:s'),
                        'additional_data' => json_encode(null),
                        //'products' => $item['products'] ?? null,

                        'sku' => $item['products'][$i]['sku'] ?? null,
                        'name' => $item['products'][$i]['name'] ?? null,
                        'quantity' => $item['products'][$i]['quantity'] ?? null,
                        'offer_id' => $item['products'][$i]['offer_id'] ?? null,
                        'price' => $item['products'][$i]['price'] ?? null,
                        'digital_codes' => json_encode(null) ?? null,
                        'currency_code' => $item['products'][$i]['currency_code'] ?? null,

                        'region' => $item['analytics_data']['region'] ?? null,
                        'city' => $item['analytics_data']['city'] ?? null,
                        'delivery_type' => $item['analytics_data']['delivery_type'] ?? null,
                        'is_premium' => $item['analytics_data']['is_premium'] ?? null,
                        'payment_type_group_name' => $item['analytics_data']['payment_type_group_name'] ?? null,
                        'warehouse_id' => $item['analytics_data']['warehouse_id'] ?? null,
                        'warehouse_name' => $item['analytics_data']['warehouse_name'] ?? null,
                        'is_legal' => $item['analytics_data']['is_legal'] ?? null,

                        'marketplace_service_item_fulfillment' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_fulfillment'] ?? null,
                        'marketplace_service_item_pickup' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_pickup'] ?? null,
                        'marketplace_service_item_dropoff_pvz' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_dropoff_pvz'] ?? null,
                        'marketplace_service_item_dropoff_sc' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_dropoff_sc'] ?? null,
                        'marketplace_service_item_dropoff_ff' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_dropoff_ff'] ?? null,
                        'marketplace_service_item_direct_flow_trans' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_direct_flow_trans'] ?? null,
                        'marketplace_service_item_return_flow_trans' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_return_flow_trans'] ?? null,
                        'marketplace_service_item_deliv_to_customer' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_deliv_to_customer'] ?? null,
                        'marketplace_service_item_return_not_deliv_to_customer' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_return_not_deliv_to_customer'] ?? null,
                        'marketplace_service_item_return_part_goods_customer' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_return_part_goods_customer'] ?? null,
                        'marketplace_service_item_return_after_deliv_to_customer' => $item['financial_data']['products'][$i]['item_services']['marketplace_service_item_return_after_deliv_to_customer'] ?? null,

                        'commission_amount' => $item['financial_data']['products'][$i]['commission_amount'] ?? null,
                        'commission_percent' => $item['financial_data']['products'][$i]['commission_percent'] ?? null,
                        'payout' => $item['financial_data']['products'][$i]['payout'] ?? null,
                        'product_id' => $item['financial_data']['products'][$i]['product_id'] ?? null,
                        'old_price' => $item['financial_data']['products'][$i]['old_price'] ?? null,
                        'total_discount_value' => $item['financial_data']['products'][$i]['total_discount_value'] ?? null,
                        'total_discount_percent' => $item['financial_data']['products'][$i]['total_discount_percent'] ?? null,
                        'actions' => json_encode(null),
                        'picking' => json_encode($item['financial_data']['products'][$i]['picking']),
                        'client_price' => $item['financial_data']['products'][$i]['client_price'] ?? null,
                        'cluster_from' => $item['financial_data']['cluster_from'] ?? null,
                        'cluster_to' => $item['financial_data']['cluster_to'] ?? null,

                    ];
                }
            }
            $dataForSaveChunks = array_chunk($dataForSave, 100);
            foreach ($dataForSaveChunks as $chunk) {
                //dd($chunk);
                OzonPostingFbo::upsert($chunk, ['order_id', 'posting_number', 'sku']);
            }
            $offset += 100;
            $result = $api->postingFboListV2(["since" => "2020-09-01T00:00:00.000Z",
                "status" => "",
                "to" => "2023-11-17T10:44:12.828Z"], 'asc', 100, $offset);
            $response = $api->getResponse();
            $items = $result['result'];
        } while (!empty($items['result']));
        if(!$response->successful())
        {
            $response->throw();
        }
    }
}

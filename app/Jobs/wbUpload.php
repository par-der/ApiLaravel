<?php

namespace App\Jobs;

use App\Http\Controllers\WB\wbGetDataController;
use App\Models\ExciseGood;
use App\Models\WB\Income;
use App\Models\WB\Price;
use App\Models\WB\ReportDetailByPeriod;
use App\Models\WB\Sale;
use App\Models\WB\Stock;
use App\Models\WB\wbOrder;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class wbUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Дата, с которой начинаем загрузку данных
    private ?string $apiUrl;
    private ?string $apiKey;
    private ?string $apiStat;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiUrl = env("WB_URL");
        $this->apiKey = env("WB_APIKEY");
        $this->apiStat = env("WB_STATKEY");
    }

    /**
     * @return void
     */
    public function handle()
    {

        $date = new DateTime('2023-01-01');
        $this->setExciseGoods($date);
        $this->setIncomes($date);
        $this->setOrders($date);
        $this->setPrices();
        $this->setSales($date);
        $this->setStocks($date);
        $this->setReportDetailByPeriods($date);

    }

    /**
     * @return void
     */
    public function setStocks($dateFrom){

        $date = (new DateTime())->format('Y-m-d');
        $stocks = $this->getStocks($dateFrom);
        //$dataForSave = [];
        Stock::where('date', $date)->delete();
        foreach ($stocks as $item) {
            //dd($stocks);

            Stock::create([
                'date' => $date,
                'last_change_date' => $item['lastChangeDate'],
                'supplier_article' => $item['supplierArticle'],
                'tech_size' => $item['techSize'],
                'barcode' => $item['barcode'],
                'quantity' => $item['quantity'],
                'is_supply' => $item['isSupply'],
                'is_realization' => $item['isRealization'],
                'quantity_full' => $item['quantityFull'],
                'warehouse_name' => $item['warehouseName'],
                'subject' => $item['subject'],
                'category' => $item['category'],
                'days_on_site' => $item['daysOnSite'],
                'brand' => $item['brand'],
                'sccode' => $item['SCCode'],
                'price' => $item['Price'],
                'discount' => $item['Discount'],
                'nm_id' => $item['nmId'],
            ]);
        }

    }

    /**
     * @return void
     */
    public function setIncomes($dateFrom)
    {

        $incomes = $this->getIncomes($dateFrom);
        $dataForSave = [];
            foreach ($incomes as $item) {
                //dd($incomes);
                $dataForSave[] = [
                    'income_id' => $item['incomeId'],
                    'number' => $item['number'],
                    'date' => $item['date'],
                    'last_change_date' => $item['lastChangeDate'],
                    'supplier_article' => $item['supplierArticle'],
                    'tech_size' => $item['techSize'],
                    'barcode' => $item['barcode'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['totalPrice'],
                    'date_close' => $item['dateClose'],
                    'warehouse_name' => $item['warehouseName'],
                    'nm_id' => $item['nmId'],
                    'status' => $item['status'],
                ];
            }
            $dataForSaveChunks = array_chunk($dataForSave, 1000);
            foreach ($dataForSaveChunks as $chunk) {
                Income::upsert($chunk, ['income_id', 'barcode']);
            }
    }

    /**
     * @return void
     */
    public function setOrders($dateFrom)
    {

        $orders = $this->getOrders($dateFrom);
        $dataForSave = [];
        foreach ($orders as $item) {
            //dd($item);

            $dataForSave[] = [
                'g_number' => $item['gNumber'],
                'date' => $item['date'],
                'last_change_date' => $item['lastChangeDate'],
                'supplier_article' => $item['supplierArticle'],
                'tech_size' => $item['techSize'],
                'barcode' => $item['barcode'],
                'total_price' => $item['totalPrice'],
                'discount_percent' => $item['discountPercent'],
                'warehouse_name' => $item['warehouseName'],
                'oblast' => $item['oblast'],
                'income_id' => $item['incomeID'],
                'odid' => $item['odid'],
                'nm_id' => $item['nmId'],
                'subject' => $item['subject'],
                'category' => $item['category'],
                'brand' => $item['brand'],
                'is_cancel' => $item['isCancel'],
                'cancel_dt' => $item['cancel_dt'],
                'sticker' => $item['sticker'],
                'srid' => $item['srid'],
            ];
        }
        $dataForSaveChunks = array_chunk($dataForSave, 1000);
        foreach ($dataForSaveChunks as $chunk) {
            //dd($dataForSaveChunks);
            wbOrder::upsert($chunk, ['odid']);
        }
    }

    /**
     * @return void
     */
    public function setSales($dateFrom)
    {

        $sales = $this->getSales($dateFrom);
        $dataForSave = [];
        foreach ($sales as $item) {
            //dd($sales);

            $dataForSave[] = [
                'last_change_date' => $item['lastChangeDate'],
                'supplier_article' => $item['supplierArticle'],
                'tech_size' => $item['techSize'],
                'barcode' => $item['barcode'],
                'total_price' => $item['totalPrice'],
                'discount_percent' => $item['discountPercent'],
                'is_supply' => $item['isSupply'],
                'is_realization' => $item['isRealization'],
                'promo_code_discount' => $item['promoCodeDiscount'],
                'warehouse_name' => $item['warehouseName'],
                'country_name' => $item['countryName'],
                'oblast_okrug_name' => $item['oblastOkrugName'],
                'region_name' => $item['regionName'],
                'income_id' => $item['incomeID'],
                'sale_id' => $item['saleID'] ?? null,
                'odid' => $item['odid'],
                'spp' => $item['spp'],
                'for_pay' => $item['forPay'],
                'finished_price' => $item['finishedPrice'],
                'price_with_disc' => $item['priceWithDisc'],
                'nm_id' => $item['nmId'],
                'subject' => $item['subject'],
                'category' => $item['category'],
                'brand' => $item['brand'],
                'is_storno' => $item['IsStorno'],
                'g_number' => $item['gNumber'],
                'sticker' => $item['sticker'],
                'srid' => $item['srid'],
                'date' =>$item['date'],
            ];
        }
        $dataForSaveChunks = array_chunk($dataForSave, 1000);
        foreach ($dataForSaveChunks as $chunk) {
            //dd($dataForSaveChunks);
            Sale::upsert($chunk, ['sale_id']);
        }

    }

    /**
     * @return void
     */
    public function setReportDetailByPeriods($dateFrom)
    {

        $reportDateilByPeriod = $this->getReportDetailByPeriods($dateFrom);
        do {

            $dataForSave = [];
            foreach ($reportDateilByPeriod as $item) {
                //dd($reportDateilByPeriod);

                $dataForSave[] = [
                    'realizationreport_id' => $item['realizationreport_id'] ?? null,
                    'date_from' => (new DateTime($item['date_from']))->format('Y-m-d') ?? null,
                    'date_to' => (new DateTime($item['date_to']))->format('Y-m-d') ?? null,
                    'create_dt' => (new DateTime($item['create_dt']))->format('Y-m-d') ?? null,
                    'suppliercontract_code' => $item['suppliercontract_code'] ?? null,
                    'rrd_id' => $item['rrd_id'] ?? null,
                    'gi_id' => $item['gi_id'] ?? null,
                    'subject_name' => $item['subject_name'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                    'brand_name' => $item['brand_name'] ?? null,
                    'sa_name' => $item['sa_name'] ?? null,
                    'ts_name' => $item['ts_name'] ?? null,
                    'barcode' => $item['barcode'] ?? null,
                    'doc_type_name' => $item['doc_type_name'] ?? null,
                    'quantity' => $item['quantity'] ?? 0,
                    'retail_price' => $item['retail_price'] ?? null,
                    'retail_amount' => $item['retail_amount'] ?? null,
                    'sale_percent' => $item['sale_percent'] ?? null,
                    'commission_percent' => $item['commission_percent'] ?? null,
                    'office_name' => $item['office_name'] ?? null,
                    'supplier_oper_name' => $item['supplier_oper_name'] ?? null,
                    'order_dt' => (new DateTime($item['order_dt']))->format('Y-m-d') ?? null,
                    'sale_dt' => (new DateTime($item['sale_dt']))->format('Y-m-d') ?? null,
                    'rr_dt' => (new DateTime($item['rr_dt']))->format('Y-m-d') ?? null,
                    'shk_id' => $item['shk_id'] ?? null,
                    'retail_price_withdisc_rub' => $item['retail_price_withdisc_rub'] ?? null,
                    'delivery_amount' => $item['delivery_amount'] ?? null,
                    'return_amount' => $item['return_amount'] ?? null,
                    'delivery_rub' => $item['delivery_rub'] ?? null,
                    'gi_box_type_name' => $item['gi_box_type_name'] ?? null,
                    'product_discount_for_report' => $item['product_discount_for_report'] ?? null,
                    'supplier_promo' => $item['supplier_promo'] ?? null,
                    'rid' => $item['rid'] ?? null,
                    'ppvz_spp_prc' => $item['ppvz_spp_prc'] ?? null,
                    'ppvz_kvw_prc_base' => $item['ppvz_kvw_prc_base'] ?? null,
                    'ppvz_kvw_prc' => $item['ppvz_kvw_prc'] ?? null,
                    'ppvz_sales_commission' => $item['ppvz_sales_commission'] ?? null,
                    'ppvz_for_pay' => $item['ppvz_for_pay'] ?? null,
                    'ppvz_reward' => $item['ppvz_reward'] ?? null,
                    'acquiring_fee' => $item['acquiring_fee'] ?? null,
                    'acquiring_bank' => $item['acquiring_bank'] ?? null,
                    'ppvz_vw' => $item['ppvz_vw'] ?? null,
                    'ppvz_vw_nds' => $item['ppvz_vw_nds'] ?? null,
                    'ppvz_office_id' => $item['ppvz_office_id'] ?? null,
                    'ppvz_office_name' => $item['ppvz_office_name'] ?? null,
                    'ppvz_supplier_id' => $item['ppvz_supplier_id'] ?? null,
                    'ppvz_supplier_name' => $item['ppvz_supplier_name'] ?? null,
                    'ppvz_inn' => $item['ppvz_inn'] ?? null,
                    'declaration_number' => $item['declaration_number'] ?? null,
                    'sticker_id' => $item['sticker_id'] ?? null,
                    'site_country' => $item['site_country'] ?? null,
                    'penalty' => $item['penalty'] ?? null,
                    'additional_payment' => $item['additional_payment'] ?? null,
                    'srid' => $item['srid'] ?? null,
                ];
            }
            $dataForSaveChunks = array_chunk($dataForSave, 1000);
            foreach ($dataForSaveChunks as $chunk) {
                //dd($dataForSaveChunks);
                ReportDetailByPeriod::upsert($chunk, ['rrd_id']);
            }
            $reportDateilByPeriod = $this->getReportDetailByPeriods($dateFrom, $item['rrd_id']);
        } while(isset($reportDateilByPeriod));
    }

    /**
     * @return void
     */
    public function setExciseGoods($dateFrom)
    {

        $exciseGoods = $this->getExciseGoods($dateFrom);
        $dataForSave = [];
        foreach ($exciseGoods as $item){
            //dd($exciseGoods);
            if (!ExciseGood::whereId($item['id'])->first()) {
                $newItem = array(
                    'finishedPrice' => $item['realizationreport_id'],
                    'operationTypeId' => $item['date_to'],
                    'fiscalDt' => $item['create_dt'],
                    'suppliercontract_code' => $item['suppliercontract_code'],
                    'docNumber' => $item['rrd_id'],
                    'gfnNumberi_id' => $item['gi_id'],
                    'excise' => $item['subject_name'],
                    'date' => date("Y-m-d"),
                );
                ExciseGood::create($newItem);
            }
        }

    }


    public function setPrices()
    {

        $date = (new DateTime())->format('Y-m-d');
        Price::where('date', $date)->delete();
        $prices = $this->getPrices();
        //$dataForSave = [];
        foreach ($prices as $item){
            //dd($prices);

            Price::create([
                'nm_id' => $item['nmId'],
                'price' => $item['price'],
                'discount' => $item['discount'],
                'promo_code' => $item['promoCode'] ?? null,
                'date' => $date,
            ]);
        }

    }


    private function getIncomes(DateTime $dateFrom){


        $response = Http::retry(3, 100)->withHeaders([
            'Authorization' => $this->apiStat
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/incomes',
            ['dateFrom' => $dateFrom->format('Y-m-d')]);

        return $response->json();
    }


    private function getOrders(DateTime $dateFrom, int $flag = 0){

        $response = Http::retry(3, 100)->withHeaders([
            'Authorization' => $this->apiStat
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/orders', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'flag' => $flag
        ]);

        return $response->json();
    }


    private function getStocks(DateTime $dateFrom){

        $response = Http::retry(3, 100)->withHeaders([
            'Authorization' => $this->apiStat
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/stocks',
            ['dateFrom' => $dateFrom->format('Y-m-d')]);

        return $response->json();
    }


    private function getSales(DateTime $dateFrom, int $flag = 0){

        $response = Http::retry(3, 100)->withHeaders([
            'Authorization' => $this->apiStat
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/sales', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'flag' => $flag
        ]);

        return $response->json();
    }


    private function getReportDetailByPeriods(DateTime $dateFrom, int $rrdid = 0, int $limit = 1000){

        $response = Http::retry(3, 100)->withHeaders([
            'Authorization' => $this->apiStat
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/reportDetailByPeriod', [
            'dateFrom' => $dateFrom->format('Y-m-d'),
            'limit' => $limit,
            'dateTo' => (new DateTime())->format('Y-m-d'),
            'rrdid' => $rrdid
        ]);

        return $response->json();
    }

    /**
     * @return mixed
     */
    private function getExciseGoods(DateTime $dateFrom){


        $response = Http::retry(3, 100)->withHeaders([
            'Authorization' => $this->apiStat,
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/excise-goods', [
            'dateFrom' => $dateFrom->format('Y-m-d')
        ]);

        return $response->json();
    }

    /**
     * @param int $quantity
     * @return mixed
     */
    private function getPrices(int $quantity = 0){

        $response = Http::retry(3, 100)->withHeaders([
            'Authorization' => $this->apiKey,
        ])->get('https://suppliers-api.wildberries.ru/public/api/v1/info', [
            '$quantity' => $quantity
        ]);

        return $response->json();
    }
}

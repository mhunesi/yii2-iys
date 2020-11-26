<?php
/**
 * (developer comment)
 *
 * @link http://www.mustafaunesi.com.tr/
 * @copyright Copyright (c) 2020 Polimorf IO
 * @product PhpStorm.
 * @author : Mustafa Hayri ÜNEŞİ <mhunesi@gmail.com>
 * @date: 2020-11-26
 * @time: 09:55
 */

namespace mhunesi\iys;


use GuzzleHttp\Psr7\Message;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Json;

class Retailers extends BaseObject
{
    /**
     * @var Iys
     */
    public $api;

    public function init()
    {
        parent::init();
    }


    /**
     * Verilen iysCode ve brandCode bilgisine göre marka altına bayi yaratır.
     * Bir retailerCode üreterek cevapta dönülür.
     *
     * https://apidocs.iys.org.tr/#operation/createRetailer
     * @param array $retailer
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function add(array $retailer)
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/retailers";

        $response = $this->api->request('POST', $endPoint, $retailer);

        return Json::decode($response->getBody());
    }

    /**
     * iysCode ve brandCode ile eşleşen bayi listesini görmek için kullanılmalıdır.
     * https://apidocs.iys.org.tr/#operation/searchRetailers
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function all()
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/retailers";

        $response = $this->api->request('GET', $endPoint);

        return Json::decode($response->getBody());
    }

    /**
     * iysCode, brandCode ve retailerCode bilgisi verilen hizmet sağlayıcıya bağlı
     * bayinin detaylarını görmek için kullanılmalıdır.
     * https://apidocs.iys.org.tr/#operation/searchSingleRetailer
     *
     * @param $retailCode
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function view($retailCode)
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/retailers/{$retailCode}";

        $response = $this->api->request('GET', $endPoint);

        return Json::decode($response->getBody());
    }

    /**
     * iysCode, brandCode ve retailerCode bilgisi verilen hizmet sağlayıcıya bağlı bayiyi siler.
     * https://apidocs.iys.org.tr/#operation/deleteRetailer
     *
     * @param $retailCode
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($retailCode)
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/retailers/{$retailCode}";

        $response = $this->api->request('DELETE', $endPoint);

        return Json::decode($response->getBody());
    }

}
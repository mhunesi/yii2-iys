<?php
/**
 * (developer comment)
 *
 * @link http://www.mustafaunesi.com.tr/
 * @copyright Copyright (c) 2020 Polimorf IO
 * @product PhpStorm.
 * @author : Mustafa Hayri ÜNEŞİ <mhunesi@gmail.com>
 * @date: 2020-11-25
 * @time: 22:22
 */

namespace mhunesi\iys;


use yii\base\BaseObject;
use yii\helpers\Json;

class Brands extends BaseObject
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
     * https://dev.iys.org.tr/api-metotlar/marka-yonetimi/marka-listeleme/
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function all()
    {
        $endPoint = "/sps/{$this->api->brand_code}/brands";

        $response = $this->api->request('GET', $endPoint);

        return Json::decode($response->getBody());
    }

    /**
     * https://dev.iys.org.tr/api-metotlar/marka-yonetimi/isortagi-listeleme/
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function allIntegratorBrands()
    {
        if (empty($this->api->integratorCode)) {
            throw new InvalidConfigException("The 'integratorCode' option is required.");
        }

        $endPoint = "/integrator/{$this->api->integratorCode}/sps";

        $response = $this->api->request('GET', $endPoint);

        return Json::decode($response->getBody());
    }

    /**
     * https://dev.iys.org.tr/api-metotlar/marka-yonetimi/isortagi-sorgulama/
     * @param $iysCode
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function oneIntegratorBrand($iysCode)
    {
        if (empty($this->api->integratorCode)) {
            throw new InvalidConfigException("The 'integratorCode' option is required.");
        }

        $endPoint = "/integrator/{$this->api->integratorCode}/sps/{$iysCode}";

        $response = $this->api->request('GET', $endPoint);

        return Json::decode($response->getBody());
    }

}
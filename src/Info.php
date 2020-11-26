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

class Info extends BaseObject
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
     * https://apidocs.iys.org.tr/#operation/getCities
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cities()
    {
        $endPoint = "/info/city";

        $response = $this->api->request('GET',$endPoint);

        return Json::decode($response->getBody());
    }

    /**
     * @param $code
     * https://apidocs.iys.org.tr/#operation/cityDetails
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cityDetails($code)
    {
        $endPoint = "/info/city/{$code}";

        $response = $this->api->request('GET',$endPoint);

        return Json::decode($response->getBody());
    }

    /**
     * https://apidocs.iys.org.tr/#operation/getTowns
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function towns()
    {
        $endPoint = "/info/town";

        $response = $this->api->request('GET',$endPoint);

        return Json::decode($response->getBody());
    }

    /**
     * @param $code
     * https://apidocs.iys.org.tr/#operation/townDetails
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function townDetails($code)
    {
        $endPoint = "/info/town/{$code}";

        $response = $this->api->request('GET',$endPoint);

        return Json::decode($response->getBody());
    }

}
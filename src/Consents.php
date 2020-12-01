<?php
/**
 * (developer comment)
 *
 * @link http://www.mustafaunesi.com.tr/
 * @copyright Copyright (c) 2020 Polimorf IO
 * @product PhpStorm.
 * @author : Mustafa Hayri ÜNEŞİ <mhunesi@gmail.com>
 * @date: 2020-11-26
 * @time: 01:48
 */

namespace mhunesi\iys;


use Yii;
use yii\helpers\Json;
use yii\base\BaseObject;
use GuzzleHttp\Psr7\Message;
use yii\base\InvalidConfigException;

class Consents extends BaseObject
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
     * Bu metot, alıcıdan alınmış izinlerin tekil olarak İYS'ye yüklenmesine imkan tanır.
     *
     * https://apidocs.iys.org.tr/#operation/addConsent
     * @param array $consents
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function add(array $consents)
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/consents";

        $response = $this->api->request('POST', $endPoint, [
            'body' => Json::encode($consents)
        ]);

        $responseString = Message::toString($response);

        Yii::info(['Response' => $responseString, 'Consents' => $consents], __METHOD__);

        return Json::decode($response->getBody());
    }

    /**
     * Bu metot, alıcıdan alınmış izinlerin yığın olarak İYS'ye yüklenmesine imkan tanır.
     *
     * https://apidocs.iys.org.tr/#operation/addBatchConsent
     * @param array $consents
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addBatch(array $consents)
    {
        if (count($consents) > 1000) {
            throw new InvalidConfigException("The consents counts must be max 1000.");
        }

        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/consents/request";

        $response = $this->api->request('POST', $endPoint,[
            'body' => Json::encode($consents)
        ]);

        $responseString = Message::toString($response);

        Yii::info(['Response' => $responseString, 'Consents' => $consents], __METHOD__);

        return Json::decode($response->getBody());
    }

    /**
     * Bu metot, hizmet sağlayıcıların İYS'de kayıtlı olan izinlerini tekil olarak listelemelerini sağlar.
     *
     * https://apidocs.iys.org.tr/#operation/consentDetail
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function detail(array $data)
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/status";

        $response = $this->api->request('POST', $endPoint, [
            'body' => Json::encode($data)
        ]);

        return Json::decode($response->getBody());
    }

    /**
     * Bu metot, asenkron çoklu izin ekleme işlemi sonunda dönen işlem sorgulama bilgisiyle
     * izin kayıt isteklerinin sonuçlarını sorgular.
     *
     * https://apidocs.iys.org.tr/#operation/searchRequestDetails
     * @param string $request_id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestDetails(string $request_id)
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/consents/request/{$request_id}";

        $response = $this->api->request('GET', $endPoint);

        return Json::decode($response->getBody());
    }

    /**
     * Bu metot, hizmet sağlaycının İYS'ye ilettiği veya bilgisi dışında alıcı tarafından
     * İYS üzerinde gerçekleştirilen izin hareketlerini sorgulamayı sağlar.
     *
     * https://apidocs.iys.org.tr/#operation/PullPerBrandCode
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function changes()
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/consents/changes";

        $response = $this->api->request('GET', $endPoint);

        return Json::decode($response->getBody());
    }


    /**
     * Hizmet sağlayıcıların, markalarına izin ekleyebilmesi için İYS aracılığıyla alıcılardan
     * onay isteyebilmesini sağlayan izin ekleme yöntemidir.
     * https://apidocs.iys.org.tr/#operation/startProcess
     *
     * @param $consents
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function overIys(array $consents)
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/consents/overIys";

        $response = $this->api->request('POST', $endPoint, [
            'body' => Json::encode($consents)
        ]);

        return Json::decode($response->getBody());
    }

    /**
     * Bu metot, alıcıya iletilen doğrulama kodunun istek olarak gönderilmesini ve
     * gönderilen kodun geçerli olması durumunda izinin ilgili markaya kaydedilmesini sağlar.
     * https://apidocs.iys.org.tr/#operation/endProcess
     *
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verificationCode(array $data)
    {
        $endPoint = "/sps/{$this->api->iys_code}/brands/{$this->api->brand_code}/consents/verificationCode";

        $response = $this->api->request('POST', $endPoint, [
            'body' => Json::encode($data)
        ]);

        return Json::decode($response->getBody());
    }
}
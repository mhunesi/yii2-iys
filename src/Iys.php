<?php

namespace mhunesi\iys;

use Yii;
use yii\helpers\Json;
use GuzzleHttp\Client;
use yii\base\Component;
use GuzzleHttp\Psr7\Message;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use GuzzleHttp\Exception\RequestException;

/**
 * This is just an example.
 */
class Iys extends Component
{
    /**
     * IYS API url.
     * @var string
     */
    public $url = 'https://api.iys.org.tr';

    /**
     * IYS username
     * @var string
     */
    public $username;

    /**
     * IYS password
     * @var string
     */
    public $password;

    /**
     * Iys code for your brand.
     * @var string
     */
    public $iys_code;

    /**
     * Iys brand code for your brand.
     * @var string
     */
    public $brand_code;

    /**
     * @var Client|array
     */
    public $client;

    /**
     * @var string
     */
    private $access_token;

    /**
     * @var string;
     */
    public $integratorCode;

    /**
     * @throws ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \GuzzleHttp\Exception\InvalidArgumentException
     */
    public function init()
    {
        $this->initClient();
        $this->authenticate();
    }

    /**
     * @param string $brand_code
     * @return $this
     */
    public function setBrandCode(string $brand_code)
    {
        $this->brand_code = $brand_code;
        return $this;
    }

    /**
     * @return Brands
     */
    public function brands()
    {
        return new Brands([
            'api' => clone $this
        ]);
    }

    /**
     * @return Retailers
     */
    public function retailers()
    {
        return new Retailers([
            'api' => $this
        ]);
    }

    /**
     * @return Consents
     */
    public function consents()
    {
        return new Consents([
            'api' => $this
        ]);
    }

    /**
     * @return Info
     */
    public function info()
    {
        return new Info([
            'api' => $this
        ]);
    }

    /**
     * @param $method
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $uri = '', array $options = [])
    {
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (RequestException $e) {
            $request = Message::toString($e->getRequest());
            $response = $e->getResponse();
            if (method_exists($e, 'getResponse') && $e->getResponse()->getStatusCode() === 500) {
                Yii::error('Api exception: ' . $e->getMessage() . " Request: " . $request, __METHOD__);
            }
        }
        return $response;
    }

    /**
     * Authorize Method
     * @return bool
     * @throws ErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \GuzzleHttp\Exception\InvalidArgumentException
     */
    private function authenticate()
    {
        if (!$this->access_token) {
            $response = $this->request('POST', '/oauth2/token', [
                'body' => Json::encode([
                    'username' => $this->username,
                    'password' => $this->password,
                    'grant_type' => 'password'
                ])
            ]);

            try {
                $body = Json::decode($response->getBody());
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
                $responseString = Message::toString($response);
                throw new ErrorException("JSON Error: {$e->getMessage()} Response: {$responseString}");
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $this->access_token = ArrayHelper::getValue($body, 'access_token');
                $this->initClient([
                    'headers' => ['Authorization' => "Bearer {$this->access_token}"]
                ]);

                return true;
            }

            $responseString = Message::toString($response);
            throw new ErrorException("Authenticate Error => Response: {$responseString}");
        }

        return true;
    }

    /**
     * Init Client
     * @param array $config
     * @throws \GuzzleHttp\Exception\InvalidArgumentException
     */
    private function initClient($config = [])
    {
        $this->client = new Client(ArrayHelper::merge([
            'verify' => false,
            'debug' => false,
            'base_uri' => $this->url,
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
        ], $config));
    }
}

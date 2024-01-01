<?php 

namespace ser6io\yii2user\components;

use Yii;
use yii\httpclient\Client;
use ser6io\yii2user\components\UserActionLog;

/*
 * ProxyCheck.io API Client w/ REDIS cache
 * https://proxycheck.io/api/
 */

class IpCheck extends \yii\base\BaseObject
{
    public $redis = 'redis';
    public $expires = 172800; // [sec] = 2 days : REDIS key expiration
    public $proxyCheckApiKey;
    public $ip;

    private $api = 'https://proxycheck.io/v2/';

    private $path;

    public function init()
    {
        $this->path = dirname(__FILE__) . '/../runtime';
    }

    public function isBlocked()
    {
        $ip = $this->ip ?? Yii::$app->request->userIP;
        $block = Yii::$app->{$this->redis}->get("ip:$ip");
        if ($block == null) {
           $block = $this->checkIp($ip);
        }
        return $block;
    }

    public function ipInfo()
    {
        $ip = $this->ip ?? Yii::$app->request->userIP;

        $ip_detailsExist = Yii::$app->{$this->redis}->exists("ip_details:$ip");

        if (!$ip_detailsExist) {
            $this->checkIp($ip);
        }

        $result = Yii::$app->{$this->redis}->hgetall("ip_details:$ip");

        $ip_detailsArray = [];
        for ($i = 0; $i < count($result); $i+=2) {
            $ip_detailsArray[$result[$i]] = $result[$i+1];
        }

        return $ip_detailsArray;
    }

    private function checkIp($ip, $requestParams = ['vpn' => 1, 'asn' => 1, 'risk' => 2, 'seen' => 1])
    {       
        if (isset($this->proxyCheckApiKey)) {
            $requestParams['key'] = $this->proxyCheckApiKey;
        }

        $client = new Client();
        $response = $client->createRequest()
            ->setUrl("$this->api$ip")
            ->setData($requestParams)
            ->send();
        
        if ($response->isOk) {

            if (isset($response->data['status'])) {

                $responseMessage = $response->data['status'] . ' ' . ($response->data['message'] ?? '');
                $ipDdetails = ["ip_details:$ip"];
                
                switch ($response->data['status']) {
                    case 'warning':
                        UserActionLog::warning($responseMessage);
                    case 'ok':

                        foreach ($response->data[$ip] as $key => $value) {
                            $ipDdetails[]= "$key";
                            $ipDdetails[] = is_array($value) ? json_encode($value) : "$value";
                        }

                        if (isset($response->data[$ip]['proxy'])) {
                            $block = $response->data[$ip]['proxy'] == "no" ? 0 : 1;
                        } else {
                            $block = 0;
                            UserActionLog::error("Status NOT found. $responseMessage");
                        }
                        break;
                    case 'denied':
                        $block = 0;
                        foreach ($response->data as $key => $value) {
                            $ipDdetails[]= "$key";
                            $ipDdetails[] = "$value";
                        }
                        UserActionLog::error($responseMessage);
                        break;
                    case 'error':
                        $block = YII_DEBUG ? 0 : 1;
                        foreach ($response->data as $key => $value) {
                            $ipDdetails[]= "$key";
                            $ipDdetails[] = "$value";
                        }
                        UserActionLog::error($responseMessage);
                        break;
                    default:
                        $block = 0;
                        UserActionLog::error("Unsupported Status: $responseMessage");
                        break;
                }
            } else {
                $block = 0;
                UserActionLog::error("Unspecified ProxyCheck API Error " . json_encode($response->data));
            }
        } else {
            $block = 0;
            UserActionLog::error("http Error $response->StatusCode");
        }

        $result = Yii::$app->{$this->redis}->executeCommand("SET", ["ip:$ip", $block, "EX", $this->expires]);
        if (!$result) {
            UserActionLog::error("REDIS Error: could not store IP $ip");
        }  

        if (isset($ipDdetails)) {
            $result = Yii::$app->{$this->redis}->executeCommand('hmset', $ipDdetails);
        }
        
        if (!isset($result) or !$result) {
            UserActionLog::error("REDIS Error: could not store details for $ip");
        } //else { //No need to expire so ip info is available later
           // $result = Yii::$app->{$this->redis}->executeCommand('EXPIRE', ["ip_details:$ip", $this->expires]);
        //}
        
        return $block;
    }

}
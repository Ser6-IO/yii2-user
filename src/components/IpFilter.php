<?php

namespace ser6io\yii2user\components;

use Yii;
use ser6io\yii2user\components\IpCheck;

/**
 * IpFilter
 *
 * @version 0.0.1
 */

class IpFilter extends \yii\base\Component {

    public $redis = 'redis';
    public $expires = 172800;
    public $message = 'Access Denied.';
    public $proxyCheckApiKey;

	public function init() {
		    
        $IpCheck = new IpCheck([
            'proxyCheckApiKey' => $this->proxyCheckApiKey,
            'redis' => $this->redis,
            'expires' => $this->expires,
        ]);

        if ($IpCheck->isBlocked()) {
            
            \ser6io\yii2user\components\UserActionLog::warning("Access attempt by Blacklisted IP");

            echo $this->message;
            Yii::$app->response->statusCode = 418;
            Yii::$app->end();
        }
    
        \ser6io\yii2user\components\UserActionLog::profile('IpCheck');
		parent::init();
	}
}


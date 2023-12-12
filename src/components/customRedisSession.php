<?php

namespace ser6io\yii2user\components;

/**
 * Issue: RedisSession does not update TTL on read only
 * https://servd.host/blog/craft-yii-and-redis-session-absentee
 * https://github.com/servdhost/craft-asset-storage/blob/master/src/PhpSession/SessionHandler.php
 * 
 * Seeing if this fixes CSFR errrors...
 */

class customRedisSession extends \yii\redis\Session
{
    public function readSession($id)
    {
        $data = parent::readSession($id);
        
        //Touch session to reset its ttl
        $this->redis->executeCommand('EXPIRE', [$this->calculateKey($id), $this->getTimeout()]);

        return $data;
    }
}
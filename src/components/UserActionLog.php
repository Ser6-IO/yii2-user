<?php

/**
 * Requires UserActionLogTarget to be configured in config
 */

 namespace ser6io\yii2user\components;

use Yii;

class UserActionLog
{
    public static function getCategory()
    {
        //Check if console app???
        //if ($app instanceof \yii\console\Application) 


        if (Yii::$app->controller->module->id != APP_ID) {
            $module = APP_ID . '\\' . Yii::$app->controller->module->id;
        } else {
            $module = Yii::$app->controller->module->id;
        }
        return "$module\\" . Yii::$app->controller->id . '\\' . Yii::$app->controller->action->id;
    }
   
    public static function error($message = null, $category = null)
    {
        $logger = Yii::getLogger();
        $logger->log($message ?? Yii::$app->request->get(), $logger::LEVEL_ERROR, $category ?? static::getCategory());
    }

    public static function warning($message = null, $category = null)
    {
        $logger = Yii::getLogger();
        $logger->log($message ?? Yii::$app->request->get(), $logger::LEVEL_WARNING, $category ?? static::getCategory());
    }

    public static function info($message = null, $category = null)
    {
        $logger = Yii::getLogger();
        $logger->log($message ?? Yii::$app->request->get(), $logger::LEVEL_INFO, $category ?? static::getCategory());
    }

    public static function trace($message = null, $category = null)
    {
        $logger = Yii::getLogger();
        $logger->log($message ?? Yii::$app->request->get(), $logger::LEVEL_TRACE, $category ?? static::getCategory());
    }

    public static function profile($message = null, $category = null)
    {
        $logger = Yii::getLogger();
        $logger->log($message ?? Yii::$app->request->get(), $logger::LEVEL_PROFILE, $category ?? static::getCategory());
    }
}

?>
<?php

namespace ser6io\yii2user;

use Yii;

/**
 * User module definition class
 */
class User extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'ser6io\yii2user\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->defaultRoute = 'profile';

        $this->layoutPath = '@app/views/layouts';
    }
}

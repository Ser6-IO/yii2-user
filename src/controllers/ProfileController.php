<?php

namespace ser6io\yii2user\controllers;

use yii\web\Controller;

/**
 * Default controller for the `user` module
 */
class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Renders the User profile view
     * @return string
     */
    public function actionView()
    {
        return $this->render('view');
    }
}

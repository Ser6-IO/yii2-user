<?php

namespace ser6io\yii2user\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use ser6io\yii2user\models\LoginForm;

/**
 * Default controller for the `user` module
 */
class AuthController extends Controller
{
    public $defaultAction = 'login';

    public $layout = 'blank';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm(['scenario' => LoginForm::PASSWORD_LOGIN]);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('info', "Logged in as $model->username.");
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Login w/ link action.
     *
     * @return Response|string
     */
    public function actionLinkLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm(['scenario' => LoginForm::LINK_LOGIN]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if (!$model->sendLoginLink()) {
                Yii::error($model->username, 'activity\sendLoginLink');
            }

            return  $this->render('login-message', [
                'title' => 'Check your inbox!',
                'body' => 'Click the confirmation link sent to your email address to log in.',
                'footer' => 'Didn\'t receive an email? <a href="/user/auth/link-login">Resend it</a> or <a href="/site/contact">contact us</a>.'
            ]);

        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Login callback action.
     *
     * @return Response|string
     */
    public function actionLoginCallback()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm(['scenario' => LoginForm::LINK_LOGIN_CALLBACK]);
        if ($model->load(Yii::$app->request->get(), '') && $model->loginByToken()) {
            Yii::$app->session->setFlash('info', "Logged in as $model->username.");
            return $this->goBack();
        }

        Yii::error($model->token, 'activity\\' . Yii::$app->controller->module->id . '\\' . Yii::$app->controller->id . '\\' . Yii::$app->controller->action->id);
        return  $this->render('login-message',[
            'title' => APP_NAME . ' - Login failed',
            'body' => '<i class="bi bi-exclamation-triangle"></i> Sorry, we are unable to  log you in with this link.',
            'footer' => "Please <a href='/user/auth/link-login'>try again</a> or <a href='/user/auth/login'>log in with your password</a> instead."
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}

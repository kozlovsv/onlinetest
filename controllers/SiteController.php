<?php

namespace app\controllers;

use app\models\form\LoginForm;
use app\models\form\PasswordResetRequestForm;
use app\models\form\RegistrationForm;
use app\models\form\ResetPasswordForm;
use app\models\Letter;
use app\models\LetterLevel;
use app\models\UserAchievement;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $letters = Letter::find()->orderBy(['id' => SORT_ASC])->all();
        $lettersLevel = LetterLevel::mapCntLevel();
        $cntLevels = UserAchievement::getLevelsForLetters();
        $chunckLetters = [];

        $storage = [];
        $flag = true;
        foreach ($letters as  $i => $letter) {
            if ($flag) {
                $storage[] = $letter;
                $flag = false;
            } else {
                $storage[] = $letter;
                if (count($storage) < 2) {
                    continue;
                } else {
                    $flag = true;
                }
            }
            $chunckLetters[] = $storage;
            $storage = [];
        }
        $chunckLetters = array_chunk($chunckLetters, 5);
        return $this->render('index', compact('chunckLetters', 'cntLevels', 'lettersLevel'));
    }

    /**
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'empty';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'empty';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте почтовый ящик и следуйте инструкциям');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Извините, восстановление пароля не возможно. Скорее всего к вашему аккаунту не привязан почтовый ящик. Обратитесь к администратору.');
            }
        }

        return $this->render('request-password-reset', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'empty';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Пароль успешно изменен');
            return $this->goHome();
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionRegistration()
    {
        $this->layout = 'empty';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post())){
            if ($user = $model->save()) {
                Yii::$app->user->login($user);
                Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались в системе.');
                return $this->goHome();
            }
        }

        return $this->render('registration', [
            'model' => $model,
        ]);
    }
}

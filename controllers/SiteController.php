<?php

namespace app\controllers;

use app\models\Auth;
use app\models\form\LoginForm;
use app\models\form\PasswordResetRequestForm;
use app\models\form\RegistrationForm;
use app\models\form\ResetPasswordForm;
use app\models\Letter;
use app\models\LetterLevel;
use app\models\TestTask;
use app\models\User;
use app\models\UserAchievement;
use Yii;
use yii\authclient\ClientInterface;
use yii\base\Exception;
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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
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
        $this->layout = 'main-footer';
        if (!Yii::$app->user->identity->help_is_read){
            return $this->redirect(['site/help']);
        }

        $letters = Letter::find()->orderBy(['id' => SORT_ASC])->all();
        $lettersLevel = LetterLevel::mapCntLevel();
        $cntLevels = UserAchievement::getLevelsForLetters();
        $cntAllAchievement = UserAchievement::find()->own()->count();
        $chunckLetters = [];

        $storage = [];
        $flag = true;
        foreach ($letters as $i => $letter) {
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
        $pandaLevel = TestTask::getPandaLevel();
        return $this->render('index', compact('chunckLetters', 'cntLevels', 'lettersLevel', 'pandaLevel', 'cntAllAchievement'));
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
     * @param ClientInterface $client
     */
    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        if (empty($attributes['email'])) {
            Yii::$app->session->setFlash('error', "Ошибка авторизации. Не задан обязательный аттрибут Email");
            return;
        }

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = $auth->user;
                Yii::$app->user->login($user);
                return;
            }
            // регистрация
            if (User::find()->where(['login' => $attributes['email']])->exists()) {
                Yii::$app->session->setFlash('error', "Пользователь с логином {$attributes['email']} уже существует, но {$client->getTitle()} c ним не связан. Для начала войдите на сайт используя свой логин для сайта, для того, что бы связать её.");
                return;
            }

            $form = new RegistrationForm();
            $form->password = Yii::$app->security->generateRandomString(6);
            $form->login = $attributes['email'];
            $form->email = $attributes['email'];
            $form->name = !empty($attributes['name']) ? $attributes['name'] : $attributes['email'];

            $transaction = Yii::$app->getDb()->beginTransaction();
            try {
                if (!$form->save(false)) throw new Exception('При сохранении нового пользователя в базу произошла ошибка. ' . implode(',', $form->errors));
                $auth = new Auth([
                    'user_id' => $form->id,
                    'source' => $client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if (!$auth->save()) throw new Exception('При сохранении связки Auth пользователя в базу произошла ошибка. ' . implode(',', $auth->errors));
                $transaction->commit();

                $user = $form->user;
                Yii::$app->user->login($user);
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::error('Авторизация клиента OAuth.' . $e->getMessage());
                return;
            }
        } elseif (!$auth) { // добавляем внешний сервис аутентификации
            $auth = new Auth([
                'user_id' => Yii::$app->user->id,
                'source' => $client->getId(),
                'source_id' => $attributes['id'],
            ]);
            $auth->save();
        }
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
        if ($model->load(Yii::$app->request->post())) {
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

    /**
     * @return string
     */
    public function actionEula()
    {
        $this->layout = 'empty';
        return $this->render('eula');
    }

    /**
     * @return string
     */
    public function actionHelp()
    {
        return $this->render('help');
    }

    public function actionReadHelp(){
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $user->help_is_read = 1;
        $user->save(false, ['help_is_read']);
        return $this->goHome();
    }


   /* //Функция озвучки слов.
    public function actionGrabSound()
    {
        $words = \app\models\VocabularyWord::find()->select(['id', 'title'])->where(['>', 'id', 328])->orderBy('id')->asArray()->all();
        foreach ($words as $word) {
            $word_title = urlencode($word['title']);
            $url = "https://tts.voicetech.yandex.net/generate?key=22fe10e2-aa2f-4a58-a934-54f2c1c4d908&text={$word_title}&format=mp3&lang=ru-RU&speed=0.9&emotion=neutral&speaker=oksana&robot=1";
            $path = Yii::$app->basePath . "/web/audio/words/src/{$word['id']}.mp3";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            curl_close($ch);
            file_put_contents($path, $data);
            echo $word['id'] . '</br>';
        }
        echo 'Ok';
    }*/
}

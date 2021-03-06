<?php

namespace app\models\form;

use app\models\User;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * Форма Регистрации
 */
class RegistrationForm extends Model
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var User
     */
    public $user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'name', 'password'], 'required', 'message' => 'Заполните "{attribute}"'],
            [['login', 'email', 'name', 'password'], 'string', 'max' => 255],
            [['login', 'email', 'name', 'password'], 'trim',],
            [['email'], 'email'],
            [['login'], 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с таким логином уже зарегистрирован.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'name' => 'ФИО',
            'email' => 'Email'
        ];
    }


    /**
     * Зарегистрировать
     * @param bool $sendMail
     * @return User|null
     */
    public function save($sendMail = true)
    {
        if (!$this->validate()) {
            return null;
        }

        try {
            $user = new User();
            $user->login = $this->login;
            $user->email = $this->email;
            $user->name = $this->name;
            $user->auth_key = '';
            $user->setPassword($this->password);
            $user->roles = [User::ROLE_STUDENT];
            if ($user->save(false)) {
                $this->id = $user->id;
                $this->user = $user;
                if ($sendMail) {
                    if (!$this->sendRegistrationEmail())  Yii::$app->session->setFlash('error', "При попытке отправить письмо с регистрационными данными на email {$this->email} произошел сбой. Возможно указан некорректный Email.");
                }
                return $user;
            } else {
                throw new Exception('Не удалось сохранить данные о пользователе: ' . VarDumper::dumpAsString($user->errors));
            }
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', 'При регистрации произошла ошибка. Обратитесь к администратору.');
            Yii::error("Ошибка при регистрации: {$e->getMessage()}\r\n{$e->getTraceAsString()}");
            return null;
        }
    }

    /**
     * @return bool
     */
    public function sendRegistrationEmail()
    {
        if (!$this->email) return true;
        try {
            return Yii::$app->mailer->compose('registration', ['form' => $this])
                ->setFrom([Yii::$app->params['robotEmail'] => Yii::$app->name])
                ->setTo($this->email)
                ->setSubject('Регистрация на портале ' . Yii::$app->name)
                ->send();
        } catch (Exception $e) {
            Yii::error('Ошибка отправки письма с регистрацией. ' . $e->getMessage());
            return false;
        }
    }
}

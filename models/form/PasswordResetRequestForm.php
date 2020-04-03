<?php

namespace app\models\form;

use Exception;
use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Class PasswordResetRequestForm
 * @package app\models\form
 */
class PasswordResetRequestForm extends Model
{

    public $login;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['login', 'trim'],
            ['login', 'required', 'message' => 'Введите логин'],
            ['login', 'exist',
                'targetClass' => User::class,
                'message' => 'Пользователь с таким логином не найден',
            ],
        ];
    }

    /**
     * @return bool
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findByLogin($this->login);
        $user->setScenario($user::SCENARIO_RESET_PASSWORD);

        if (!$user || empty($user->email)) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        try {
            return Yii::$app->mailer->compose('reset-password', ['user' => $user])
                ->setFrom([Yii::$app->params['robotEmail'] => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject('Сброс пароля для ' . Yii::$app->name)
                ->send();
        } catch (Exception $e) {
            Yii::error('Ошибка отправки письма с восстановлением пароля. ' . $e->getMessage());
            return false;
        }
    }
}
<?php

namespace app\models\form;

use Yii;
use yii\base\Model;
use yii\base\InvalidArgumentException;
use app\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    /**
     * @var string
     */
    public $password;

    /**
     * @var User
     */
    public $user;

    /**
     * @param string $token
     * @param array $config
     * @throws InvalidArgumentException
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Неверный адрес для сброса пароля');
        }
        $this->user = User::findByPasswordResetToken($token);
        if (!$this->user) {
            throw new InvalidArgumentException('Ошибка сброса пароля');
        }
        $this->user->setScenario(User::SCENARIO_RESET_PASSWORD);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
        ];
    }

    /**
     * @return bool
     */
    public function resetPassword()
    {
        $user = $this->user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        if ($user->save(false)) {
            return $this->sendResetEmail();
        }
        return false;
    }

    /**
     * @return bool
     */
    public function sendResetEmail()
    {
        if (empty($this->user->email)) return true;
        return Yii::$app->mailer->compose('password', ['form' => $this])
            ->setFrom([Yii::$app->params['robotEmail'] => Yii::$app->name])
            ->setTo($this->user->email)
            ->setSubject('Данные для входа в систему ' . Yii::$app->name)
            ->send();
    }
}
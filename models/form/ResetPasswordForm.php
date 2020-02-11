<?php

namespace app\models\form;

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
    private $_user;

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
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('Ошибка сброса пароля');
        }
        $this->_user->setScenario(User::SCENARIO_RESET_PASSWORD);
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
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
}
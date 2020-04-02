<?php

namespace app\models\form;

use yii\base\Model;
use app\models\User;

/**
 * Password reset form
 */
class ChangePasswordForm extends Model
{
    /**
     * @var string
     */
    public $password;

    /**
     * @var User
     */
    private $_user;

    public function getId() {
        return $this->_user->id;
    }

    public function getName() {
        return $this->_user->name;
    }

    /**
     * @param User $user
     * @param array $config
     */
    public function __construct($user, $config = [])
    {
        $this->_user = $user;
        assert($user);
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
            ['password', 'trim'],
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
        if (!$this->validate())
            return false;
        $user = $this->_user;
        $user->setPassword($this->password);
        return $user->save(false);
    }
}
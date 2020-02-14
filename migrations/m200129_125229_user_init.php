<?php

use app\models\User;
use app\modules\auth\models\AuthAssignment;
use yii\db\Migration;


class m200129_125229_user_init extends Migration
{
    public function Up()
    {
        $user = new User();
        $user->id = 1;
        $user->login = 'kozlovsv78';
        $user->email = 'kozlovsv78@gmail.com';
        $user->name = 'Козлов Сергей Владимирович';
        $user->setPassword('vera9aug99');
        $user->auth_key = '1';
        $user->roles = ['administrator'];
        return $user->save();
    }

    public function Down()
    {
        User::deleteAll();
        AuthAssignment::deleteAll();
    }
}

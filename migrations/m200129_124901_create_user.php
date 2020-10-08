<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use yii\db\Expression;
use yii\db\Migration;

class m200129_124901_create_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'login' => $this->string(255)->notNull()->unique()->comment('Логин'),
            'name' => $this->string(255)->notNull()->comment('ФИО'),
            'email' => $this->string(255)->Null()->comment('E-mail'),
            'password_hash' => $this->string(255)->notNull()->comment('Пароль'),
            'auth_key' => $this->string(32)->notNull()->comment('Ключ авторизации'),
            'password_reset_token' => $this->string()->unique()->comment('Токен для восстановления пароля'),
            'created_at' => $this->timestamp()->notNull()->defaultValue(new Expression('CURRENT_TIMESTAMP'))->comment('Дата создания'),
        ]);
        $this->createIndex('email', 'user', 'email');
        $this->addCommentOnTable('user', 'Пользователи');
    }

    public function safeDown()
    {
        $this->dropIndex('email', 'user');
        $this->dropTable('user');
    }
}

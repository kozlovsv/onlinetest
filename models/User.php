<?php

namespace app\models;

use app\models\traits\MapTrait;
use app\modules\auth\models\AuthItem;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id ID
 * @property string $login Логин
 * @property string $email E-mail
 * @property string $name ФИО
 * @property string $password_hash Пароль
 * @property string $auth_key Ключ авторизации
 * @property string $password_reset_token Ключ для восстановления пароля
 * @property string $created_at Дата создания
 * @property int $contragent_id Контрагент
 * @property string $rolesString Список ролей в тексте
 * @property string $position Должность
 * @property string $phone Телефон
 *
 * @property AuthItem[] $roles Список ролей
 * @property TestTask[] $testTasks
 */

class User extends ActiveRecord implements IdentityInterface
{
    use MapTrait;

    const SCENARIO_RESET_PASSWORD = 'reset-password';
    const SCENARIO_EDIT_USER = 'edit-user';


    const ROLE_ADMINISTRATOR = 'administrator';
    const ROLE_TEACHER = 'teacher';
    const ROLE_STUDENT = 'student';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(),
            [self::SCENARIO_RESET_PASSWORD => ['login'],
            self::SCENARIO_EDIT_USER => ['login','email', 'name']]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'name'], 'required'],
            [['roles'], 'safe'],
            [['login', 'email', 'name'] , 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['login'], 'unique', 'message' => 'Пользователь с таким логином уже зарегистрирован.'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'email' => 'E-mail',
            'name' => 'ФИО',
            'password_hash' => 'Хеш пароля',
            'auth_key' => 'Ключ аутентификации',
            'password_reset_token' => 'Токен восстановления пароля',
            'created_at' => 'Добавлен',
            'roles' => 'Роли',
            'rolesString' => 'Роли',
        ];
    }

    /**
     * Поиск личности
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Поиск личности по токену
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($type != HttpBasicAuth::class) return null;
        $user = self::find()->where(['auth_key' => $token])->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }

    /**
     * Найти по email
     * @param string $login
     * @return static|null
     */
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login]);
    }

    /**
     * Найти юзера по токену восстановления пароля
     * @param string $token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['password_reset_token' => $token]);
    }

    /**
     * Валидация токена восстановления пароля
     * @param string $token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);

        return $timestamp + 3600 >= time();
    }

    /**
     * Валидировать ключ аутентификации
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Валидировать пароль
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @return string[]
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Установить пароль
     * @param string $password
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Сгенерировать ключ аутентификации
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Сгенерировать токен восстановления пароля
     * @throws Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Очистить токен сброса пароля
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getRoles()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'item_name'])
            ->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @param AuthItem[] $values
     * @return AuthItem[]
     */
    public function setRoles($values)
    {
        return $this->roles = $values;
    }

    /**
     * @return string
     */
    public function getRolesString()
    {
        return implode(', ', ArrayHelper::getColumn($this->roles, 'description'));
    }


    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        $scenarios = $this->scenarios();
        // сохранение ролей
        if (!empty($this->roles) && in_array('roles', $scenarios[$this->scenario])) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($this->id);
            foreach ($this->roles as $roleName) {
                $role = AuthItem::findOne(['name' => $roleName]);
                /** @var Role $role */
                $auth->assign($role, $this->id);
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Gets query for [[TestTasks]].
     *
     * @return ActiveQuery
     */
    public function getTestTasks()
    {
        return $this->hasMany(TestTask::class, ['user_id' => 'id']);
    }
}

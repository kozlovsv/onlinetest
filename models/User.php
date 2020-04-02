<?php

namespace app\models;

use app\components\AuthManager;
use app\models\query\TestTaskQuery;
use app\models\traits\MapTrait;
use app\modules\auth\models\AuthAssignment;
use app\modules\auth\models\AuthItem;
use app\widgets\Menu;
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
 * @property int $help_is_read Справка прочитана?
 *
 * @property AuthItem[] $roles Список ролей
 * @property TestTask[] $testTasks
 * @property UserAchievement[] $userAchievements
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
            [['help_is_read'], 'integer'],
            [['login', 'email', 'name'] , 'string', 'max' => 255],
            [['login', 'email', 'name'] , 'trim'],
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
            'help_is_read' => 'Справка прочитана',
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
            $this->clearCache();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Очистка кэшей
     * @inheritdoc
     */
    protected function clearCache()
    {
        Yii::$app->cache->delete(AuthManager::getCacheKey($this->id));
        Yii::$app->cache->delete(Menu::CACHE_PREFIX . $this->id);
    }

    /**
     * Gets query for [[TestTasks]].
     *
     * @return ActiveQuery| TestTaskQuery
     */
    public function getTestTasks()
    {
        return $this->hasMany(TestTask::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserAchievements]].
     *
     * @return ActiveQuery
     */
    public function getUserAchievements()
    {
        return $this->hasMany(UserAchievement::class, ['user_id' => 'id']);
    }

    /**
     * @param string $roleName
     * @param null | array $sort
     * @return array
     */
    public static function mapByRole($roleName, $sort = null)
    {
        $sorting = $sort ? $sort : ['name' => SORT_ASC];
        $items = self::find()->joinWith(['roles'])->andWhere([AuthAssignment::tableName() . '.item_name' => $roleName])->orderBy($sorting)->all();
        return ArrayHelper::map($items, 'id', 'name');
    }

    /**
     * Получить кол-во звезд
     * @return int
     */
    public function getUserAchievementsCount()
    {
        return $this->getUserAchievements()->count();
    }

    /**
     * Запрос на список пройденных контрольных
     * @return ActiveQuery
     */
    public function getTestTaskRepetition()
    {
        return $this->getTestTasks()->finished()->repetition();
    }

    /**
     * Получить кол-во пройденных контрольных
     * @return int
     */
    public function getTestTaskRepetitionCount()
    {
        return $this->getTestTaskRepetition()->count();
    }

    /**
     * Получить стредний рейтинг по контрольным
     * @return float
     */
    public function getAverageRating()
    {
        return $this->getTestTaskRepetition()->average('rating');
    }

    /**
     * Получить средняя оценка по контрольным
     * @return float
     */
    public function getAverageGrade()
    {
        return TestTask::ratingToGrade($this->getAverageRating());
    }
}

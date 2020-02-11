<?php

namespace app\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;

class Generator extends \yii\gii\Generator
{
    public $basePath = 'app';
    public $modelName;
    public $modelLabel;
    public $modelRLabel;
    public $moduleLabel;
    public $enableModal = true;
    public $migrationPath = '@app/migrations';


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Atonex CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model. Atonex customization.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['basePath', 'modelName', 'migrationPath', 'modelLabel', 'moduleLabel'], 'required'],
            [['basePath', 'modelName', 'migrationPath', 'modelLabel', 'moduleLabel', 'modelRLabel'], 'filter', 'filter' => 'trim'],
            [['basePath', 'modelName'], 'match', 'pattern' => '/^[\w]*$/', 'message' => 'Разрешены только буквы латинского алфавита.'],
            [['modelName'], 'validateModelName'],
            [['enableModal'], 'boolean'],
            /*[['modelName'], 'filter', 'filter' => function($value) {
                return Inflector::camel2words($value);
            }],*/
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'basePath' => 'Базовый путь',
            'modelName' => 'Модель',
            'enableModal' => 'Модальное',
            'migrationPath' => 'Путь до миграций',
            'modelLabel' => 'Заголовок модели',
            'moduleLabel' => 'Заголовок модуля',
            'modelRLabel' => 'Заголовок модели в родительном падеже',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'basePath' => 'Наименование базового каталога <code>app</code> или  <code>frontend</code>. Без @. Данное поле используется для формирования путей к файлам, а так же Namespace',
            'modelName' => 'Наименование класса модели, без пути. Путь подставляется автоматически.',
            'enableModal' => 'Флаг, указывающий тип КРУД: модальные окна или обычные страницы.',
            'migrationPath' => 'Путь до файлов миграций, например: <code>@app/migrations</code>',
            'modelLabel' => 'Заголовок модели для форм (h1, breadcrumbs, и т.д.) например, Пользователь',
            'moduleLabel' => 'Заголовок модуля (страница index) например, Пользователи',
            'modelRLabel' => 'Заголовок модели в родительном падеже например, Пользователя. Если не задан, то берется обычный заголовок модели.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['controller.php', 'migration.php', 'search.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['basePath', 'migrationPath']);
    }

    /**
     * Checks if model class is valid
     */
    public function validateModelName()
    {
        /* @var $class ActiveRecord */
        $class = $this->getModelClass();
        $pk = $class::primaryKey();
        if (empty($pk)) {
            $this->addError('modelName', "The table associated with $class must have primary key(s).");
        }
    }

    public function getModelClass()
    {
        return $this->basePath . '\\models\\' . $this->modelName;
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->getControllerClass(), '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->getSearchModelClass(), '\\') . '.php'));
        $files[] = new CodeFile($searchModel, $this->render('search.php'));

        $files[] = $this->generateMigration();
        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }
        return $files;
    }

    public function getControllerClass()
    {
        return $this->basePath . '\\controllers\\' . $this->modelName . 'Controller';
    }

    public function getSearchModelClass()
    {
        return $this->basePath . '\\models\\search\\' . $this->modelName . 'Search';
    }

    public function generateMigration()
    {
        /* @var $class ActiveRecord */
        $class = $this->getModelClass();
        $modelName = $class::tableName();
        $migrationName = 'm' . gmdate('ymd_H0101') . '_auth_item_' . $modelName . '_add';
        $file = rtrim(Yii::getAlias($this->migrationPath), '/') . "/{$migrationName}.php";
        return new CodeFile($file, $this->render('migration.php', [
            'migrationName' => $migrationName,
            'modelName' => $modelName,
            'modelLabel' => $this->modelLabel,
        ]));
    }

    /**
     * @return string the controller view path
     */
    public function getViewPath()
    {
        return Yii::getAlias('@' . $this->basePath . '/views/' . $this->getControllerID());
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID()
    {
        return Inflector::camel2id($this->modelName);
    }

    public function getNameAttribute()
    {
        foreach ($this->getColumnNames() as $name) {
            if (!strcasecmp($name, 'name') || !strcasecmp($name, 'title')) {
                return $name;
            }
        }
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->getModelClass();
        $pk = $class::primaryKey();

        return $pk[0];
    }

    /**
     * @return array model column names
     */
    public function getColumnNames()
    {
        /* @var $class ActiveRecord */
        $class = $this->getModelClass();
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
        } else {
            /* @var $model \yii\base\Model */
            $model = new $class();
            return $model->attributes();
        }
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return bool|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /* @var $class ActiveRecord */
        $class = $this->getModelClass();
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions()
    {
        $columns = $this->getSearchColumns();
        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                default:
                    $likeKeyword = $this->getClassDbDriverName() === 'pgsql' ? 'ilike' : 'like';
                    $likeConditions[] = "->andFilterWhere(['{$likeKeyword}', '{$column}', \$this->{$column}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    public function getSearchColumns()
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->getModelClass();
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }
        return $columns;
    }

    /**
     * @return string|null driver name of modelClass db connection.
     * In case db is not instance of \yii\db\Connection null will be returned.
     * @since 2.0.6
     */
    protected function getClassDbDriverName()
    {
        /* @var $class ActiveRecord */
        $class = $this->getModelClass();
        $db = $class::getDb();
        return $db instanceof Connection ? $db->driverName : null;
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->getModelClass();
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            } else {
                return "'id' => \$model->{$pks[0]}";
            }
        } else {
            $params = [];
            foreach ($pks as $pk) {
                if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                    $params[] = "'$pk' => (string)\$model->$pk";
                } else {
                    $params[] = "'$pk' => \$model->$pk";
                }
            }

            return implode(', ', $params);
        }
    }

    public function getSearchColumnNames()
    {
        return array_keys($this->getSearchColumns());
    }
}

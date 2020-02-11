<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\log\Logger;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class Log extends ActiveRecord
{
    /**
     * Категория приложения
     */
    const CATEGORY_APPLICATION = 'common';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'level' => 'Уровень',
            'category' => 'Категория',
            'levelLabel' => 'Уровень',
            'categoryLabel' => 'Категория',
            'log_time' => 'Время',
            'prefix' => 'Префикс',
            'message' => 'Сообщение',
        ];
    }

    /**
     * @return array
     */
    public static function categoryMap()
    {
        return [
            self::CATEGORY_APPLICATION => 'Приложение',
        ];
    }

    /**
     * @return array
     */
    public static function levelMap()
    {
        return [
            Logger::LEVEL_ERROR => 'Ошибка',
            Logger::LEVEL_WARNING => 'Предупреждение',
            Logger::LEVEL_INFO => 'Уведомление',
        ];
    }

    /**
     * @return int|mixed
     */
    public function getCategoryLabel()
    {
        $map = self::categoryMap();

        return isset($map[$this->category]) ? $map[$this->category] : $this->category;
    }

    /**
     * @return int|mixed
     */
    public function getLevelLabel()
    {
        $map = self::levelMap();

        return isset($map[$this->level]) ? $map[$this->level] : $this->level;
    }
}

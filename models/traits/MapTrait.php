<?php

namespace app\models\traits;

use yii\helpers\ArrayHelper;

trait MapTrait
{
    /**
     * @param string $id
     * @param string $name
     * @param null $sort
     * @return array
     */
    public static function map($id = 'id', $name = 'name', $sort = null)
    {
        $sorting = $sort ? $sort : [$name => SORT_ASC];
        /** @noinspection PhpUndefinedMethodInspection */
        $items = self::find()->orderBy($sorting)->all();

        return ArrayHelper::map($items, $id, $name);
    }
}
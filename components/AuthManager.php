<?php
namespace app\components;

use Yii;
use yii\rbac\DbManager;

class AuthManager extends DbManager
{

    /**
     * @inheritdoc
     */
    public function getAssignments($id)
    {
        $key = self::getCacheKey($id);

        if (Yii::$app->cache->exists($key)) {
            $assignments = Yii::$app->cache->get($key);
        } else {
            $assignments = parent::getAssignments($id);
            Yii::$app->cache->add($key, $assignments);
        }

        return $assignments;
    }

    /**
     * @param $id
     * @return string
     */
    public static function getCacheKey($id)
    {
        return Yii::$app->id . '-assignments-' . $id;
    }
}

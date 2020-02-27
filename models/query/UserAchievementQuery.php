<?php


namespace app\models\query;


use app\models\TestTask;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class UserAchievementQuery
 * @package app\models\query
 *
 * @see \app\models\UserAchievement
 */
class UserAchievementQuery extends ActiveQuery
{
    /**
     * Свой тест
     * @return $this
     */
    public function own()
    {
        return $this->andWhere(['user_id' => Yii::$app->user->id]);
    }

    /**
     * Свой тест
     * @param $letterId int | array
     * @return $this
     */
    public function letter($letterId)
    {
        return $this->andWhere(['letter_id' => $letterId]);
    }

    /**
     * @inheritdoc
     * @return TestTask[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Список выученных слов
     * @param $letterId int | array
     * @return UserAchievementQuery
     */
    public function learnedWords($letterId) {
        return $this->own()
            ->letter($letterId)
            ->innerJoin('user_achievement_word', 'user_achievement_word.user_achievement_id = user_achievement.id');
    }

    /**
     * @inheritdoc
     * @return TestTask|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
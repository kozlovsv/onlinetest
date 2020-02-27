<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_achievement_word".
 *
 * @property int $id
 * @property int $user_achievement_id Достижение пользователя
 * @property int $vocabulary_word_id Выученное слово
 *
 * @property UserAchievement $userAchievement
 * @property VocabularyWord $vocabularyWord
 */
class UserAchievementWord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_achievement_word';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_achievement_id', 'vocabulary_word_id'], 'required'],
            [['user_achievement_id', 'vocabulary_word_id'], 'integer'],
            [['user_achievement_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAchievement::class, 'targetAttribute' => ['user_achievement_id' => 'id']],
            [['vocabulary_word_id'], 'exist', 'skipOnError' => true, 'targetClass' => VocabularyWord::class, 'targetAttribute' => ['vocabulary_word_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_achievement_id' => 'Достижение пользователя',
            'vocabulary_word_id' => 'Выученное слово',
        ];
    }

    /**
     * Gets query for [[UserAchievement]].
     *
     * @return ActiveQuery
     */
    public function getUserAchievement()
    {
        return $this->hasOne(UserAchievement::class, ['id' => 'user_achievement_id']);
    }

    /**
     * Gets query for [[VocabularyWord]].
     *
     * @return ActiveQuery
     */
    public function getVocabularyWord()
    {
        return $this->hasOne(VocabularyWord::class, ['id' => 'vocabulary_word_id']);
    }
}

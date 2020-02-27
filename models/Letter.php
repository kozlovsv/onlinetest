<?php

namespace app\models;

use app\models\traits\MapTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "letter".
 *
 * @property int $id
 * @property string $title Буква
 *
 * @property LetterLevel $letterLevel
 * @property UserAchievement[] $userAchievements
 * @property VocabularyWord[] $vocabularyWords
 */
class Letter extends ActiveRecord
{
    use MapTrait;

    const DEFAULT_ID = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 1],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Буква',
        ];
    }

    /**
     * Gets query for [[LetterLevel]].
     *
     * @return ActiveQuery
     */
    public function getLetterLevel()
    {
        return $this->hasOne(LetterLevel::class, ['letter_id' => 'id']);
    }

    /**
     * Gets query for [[UserAchievements]].
     *
     * @return ActiveQuery
     */
    public function getUserAchievements()
    {
        return $this->hasMany(UserAchievement::class, ['letter_id' => 'id']);
    }


    /**
     * Gets query for [[VocabularyWords]].
     *
     * @return ActiveQuery
     */
    public function getVocabularyWords()
    {
        return $this->hasMany(VocabularyWord::class, ['letter_id' => 'id']);
    }
}

<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "vocabulary_word".
 *
 * @property int $id
 * @property string $title Слов
 * @property int $letter_id Буква
 *
 * @property TestTaskQuestion[] $testTaskQuestions
 * @property Letter $letter
 * @property VocabularyWordVariant[] $vocabularyWordVariants
 */
class VocabularyWord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vocabulary_word';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'title' => 'Слово',
            'letter_id' => 'Буква',
        ];
    }

    /**
     * Gets query for [[VocabularyWordVariants]].
     *
     * @return ActiveQuery
     */
    public function getVocabularyWordVariants()
    {
        return $this->hasMany(VocabularyWordVariant::class, ['vocabulary_word_id' => 'id']);
    }

    public function beforeSave($insert){
        $firstLetter = mb_strtoupper(mb_substr($this->title, 0, 1));
        $letter = Letter::findOne(['title' => $firstLetter]);
        $this->letter_id = $letter ? $letter->id : Letter::DEFAULT_ID;
        return parent::beforeSave($insert);
    }

    /**
     * Gets query for [[Letter]].
     *
     * @return ActiveQuery
     */
    public function getLetter()
    {
        return $this->hasOne(Letter::class, ['id' => 'letter_id']);
    }

    /**
     * @param int $letterId
     * @param int $wordsCount
     * @return array
     */
    public static function getNotLearnedWords($letterId, $wordsCount = 0){
        $learnedWordsQuery = UserAchievement::find()->learnedWords($letterId)->select(['vocabulary_word_id']);
        $words = VocabularyWord::find()
            ->select(['id'])
            ->where(['letter_id' => $letterId])
            ->andWhere(['not in', 'id', $learnedWordsQuery]);
        if ($wordsCount) {
            $words = $words->limit($wordsCount);
        }
        $words = $words->orderBy(new Expression('rand()'));
        return  $words->asArray()->all();
    }

    /**
     * @param array $letterId
     * @param int $wordsCount
     * @return array
     */
    public static function geLearnedWords($letterId, $wordsCount = 0){
        $learnedWordsQuery = UserAchievement::find()->learnedWords($letterId)->select(['vocabulary_word_id']);
        $words = VocabularyWord::find()
            ->select(['id'])
            ->where(['letter_id' => $letterId])
            ->andWhere(['in', 'id', $learnedWordsQuery]);
        if ($wordsCount) {
            $words = $words->limit($wordsCount);
        }
        $words = $words->orderBy(new Expression('rand()'));
        return  $words->asArray()->all();
    }

}

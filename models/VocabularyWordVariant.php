<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "vocabulary_word_variant".
 *
 * @property int $id
 * @property int $vocabulary_word_id Словарное слово
 * @property string $title Неправильное слово
 *
 * @property VocabularyWord $vocabularyWord
 */
class VocabularyWordVariant extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vocabulary_word_variant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['vocabulary_word_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
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
            'vocabulary_word_id' => 'Словарное слово',
            'title' => 'Неправильное слово',
        ];
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

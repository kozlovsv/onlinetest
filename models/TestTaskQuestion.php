<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "test_task_question".
 *
 * @property int $id
 * @property int $test_task_id Test
 * @property int $vocabulary_word_id Словарное слово
 * @property string $answer Ответ
 * @property int $result Результат
 *
 * @property TestTask $testTask
 * @property VocabularyWord $vocabularyWord
 */
class TestTaskQuestion extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_task_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['test_task_id', 'vocabulary_word_id'], 'required'],
            [['test_task_id', 'vocabulary_word_id', 'result'], 'integer'],
            [['answer'], 'string', 'max' => 255],
            [['test_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestTask::class, 'targetAttribute' => ['test_task_id' => 'id']],
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
            'answer' => 'Ваш ответ',
            'result' => 'Результат',
            'resultLabel' => 'Результат',
        ];
    }

    /**
     * Gets query for [[TestTask]].
     *
     * @return ActiveQuery
     */
    public function getTestTask()
    {
        return $this->hasOne(TestTask::class, ['id' => 'test_task_id']);
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

    public function getResultLabel() {
        return $this->result ? 'Правильно' : 'Ошибка';
    }

    public static function clearTest($taskId) {
        self::updateAll(['answer' => '', 'result' => 0], ['test_task_id' => $taskId]);
    }

    public function clearAnswer() {
        $this->answer = '';
        $this->result = 0;
        return $this->save(false, ['answer', 'result']);
    }
}

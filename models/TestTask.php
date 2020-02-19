<?php

namespace app\models;

use app\models\query\TestTaskQuery;
use kozlovsv\crud\helpers\ModelPermission;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "test_task".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property int $status Статус
 * @property string $created_at Дата создания
 * @property string $passed_at Дата прохождения
 * @property int $training_status Статус обучения
 *
 *
 * @property User $user
 * @property TestTaskQuestion[] $testTaskQuestions
 * @property int passedTrainingPercent
 * @property int passedTestPercent
 */
class TestTask extends ActiveRecord
{
    /**
     * Новый
     */
    const STATUS_NEW = 0;

    /**
     * Пройден
     */
    const STATUS_FINISHED = 1;

    private $_questionsCount;
    private $_rating;
    private $_questionsPassed;
    private $_questionsTrained;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_task';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'user_id' => 'Ученик',
            'user.name' => 'Ученик',
            'status' => 'Тест',
            'statusLabel' => 'Тест',
            'training_status' => 'Обучение',
            'trainingStatusLabel' => 'Обучение',
            'created_at' => 'Создан',
            'passed_at' => 'Пройден',
            'questionsCount' => 'Кол-во слов',
            'uniqueLettersString' => 'Буквы',
            'questionsTestStayCount' => 'Осталось пройти',
            'grade' => 'Оценка',
            'rating' => 'Рейтинг, %',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[TestTaskQuestions]].
     *
     * @return ActiveQuery
     */
    public function getTestTaskQuestions()
    {
        return $this->hasMany(TestTaskQuestion::class, ['test_task_id' => 'id']);
    }

    /**
     * Получить количество вопросов в тесте.
     * @return int
     */
    public function getQuestionsCount()
    {
        if (is_null($this->_questionsCount)){
            $this->_questionsCount = $this->getTestTaskQuestions()->count();
        }
        return $this->_questionsCount;
    }

    /**
     * Получить количество пройденных вопросов в тесте.
     * @return int
     */
    public function getQuestionsPassedTestCount()
    {
        if (is_null($this->_questionsPassed)){
            $this->_questionsPassed = $this->getTestTaskQuestions()->andWhere(['!=', 'answer', ''])->count();
        }
        return $this->_questionsPassed;
    }

    /**
     * Получить количество выученных вопросов в тесте.
     * @return int
     */
    public function getQuestionsPassedTrainingCount()
    {
        if (is_null($this->_questionsTrained)) {
            $this->_questionsTrained = $this->getTestTaskQuestions()->andWhere(['training_result' => 1])->count();
        }
        return $this->_questionsTrained;
    }

    /**
     * Осталось пройти вопросов в тесте
     * @return int
     */
    public function getQuestionsTestStayCount()
    {
        return $this->getQuestionsCount() - $this->getQuestionsPassedTestCount();
    }

    /**
     * Осталось выучить вопросов
     * @return int
     */
    public function getQuestionsTrainingStayCount()
    {
        return $this->getQuestionsCount() - $this->getQuestionsPassedTrainingCount();
    }

    /**
     * Получить номер текущего вопроса в тесте
     * @return int
     */
    public function getCurrentTestNumQuestion()
    {
        return $this->getQuestionsPassedTestCount() + 1;
    }

    /**
     * Получить номер текущего вопроса в обучении
     * @return int
     */
    public function getCurrentTrainingNumQuestion()
    {
        return $this->getQuestionsPassedTrainingCount() + 1;
    }

    /**
     * % пройденного теста.
     * @return int
     */
    public function getPassingTestPercent()
    {
        $cnt = $this->getQuestionsCount();
        if ($cnt == 0)  return 0;
        return intval(round(($this->getCurrentTestNumQuestion() / $cnt) * 100, 2));
    }

    /**
     * % проходимого обучения. Кол-во уже изученных + 1 текущий вопрос / Общее кол-во).
     * @return int
     */
    public function getPassingTrainingPercent()
    {
        $cnt = $this->getQuestionsCount();
        if ($cnt == 0)  return 0;
        return intval(round(($this->getCurrentTrainingNumQuestion() / $cnt) * 100, 2));
    }

    /**
     * Общий % пройденного обучения (Кол-во уже изученных / Общее кол-во).
     * @return int
     */
    public function getPassedTrainingPercent()
    {
        $cnt = $this->getQuestionsCount();
        if ($cnt == 0)  return 0;
        return intval(round(($this->getQuestionsPassedTrainingCount() / $cnt) * 100, 2));
    }

    /**
     * Общий % пройденного ntcnbhjdfybz (Кол-во уже отвеченных / Общее кол-во).
     * @return int
     */
    public function getPassedTestPercent()
    {
        $cnt = $this->getQuestionsCount();
        if ($cnt == 0)  return 0;
        return intval(round(($this->getQuestionsPassedTestCount() / $cnt) * 100, 2));
    }

    /**
     * Получить результат теста в %.
     * @return float
     */
    public function getRating()
    {
        if (is_null($this->_rating)){
            $cnt = $this->getQuestionsCount();
            $this->_rating = 0;
            if ($cnt <> 0){
                $res = $this->getTestTaskQuestions()->sum('result');
                $this->_rating = round(($res / $cnt) * 100, 1);
            }
        }
        return $this->_rating;
    }

    /**
     * Оценка по 5 бальной шкале.
     * @return int
     */
    public function getGrade()
    {
        if ($this->status == self::STATUS_NEW) return 0;
        $rating = $this->getRating();
        if ($rating >= 90) return 5;
        if ($rating >= 75) return 4;
        if ($rating >= 60) return 3;
        return 2;
    }

    /**
     * Получить буквы в тесте.
     * @return array
     */
    public function getUniqueLetters()
    {
        $letters = $this->getTestTaskQuestions()->select(['letter.title', 'test_task_question.vocabulary_word_id'])->joinWith('vocabularyWord.letter')->orderBy('letter.id')->asArray()->all();
        return array_unique(array_column($letters, 'title'));
    }

    /**
     * Получить буквы в тесте через запятую.
     * @return string
     */
    public function getUniqueLettersString()
    {
        return implode(', ', $this->getUniqueLetters());
    }

    /**
     * @inheritdoc
     * @return TestTaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TestTaskQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function statusMap()
    {
        return [
            self::STATUS_NEW => 'Не пройден',
            self::STATUS_FINISHED => 'Пройден',
        ];
    }

    /**
     * @return array
     */
    public static function trainingStatusMap()
    {
        return [
            self::STATUS_NEW => 'Не пройдено',
            self::STATUS_FINISHED => 'Пройдено',
        ];
    }

    /**
     * @return int|mixed
     */
    public function getStatusLabel()
    {
        $map = self::statusMap();
        return isset($map[$this->status]) ? $map[$this->status] : $this->status;
    }

    /**
     * @return int|mixed
     */
    public function getTrainingStatusLabel()
    {
        $map = self::trainingStatusMap();
        return isset($map[$this->training_status]) ? $map[$this->training_status] : $this->status;
    }

    public function canTestContinue() {
        return $this->canTest() && ($this->status == self::STATUS_NEW);
    }

    public function canTrainingContinue() {
        return $this->canTest() && ($this->training_status == self::STATUS_NEW);
    }

    public function canTest() {
        return ModelPermission::canCreate(self::tableName()) && ($this->user_id == Yii::$app->user->id);
    }

    /**
     * Обновить тест. Очистить пройденные материалы.
     */
    public function reNewTest(){
        $this->status = self::STATUS_NEW;
        $this->passed_at = null;
        $this->save(false, ['status', 'passed_at']);
        TestTaskQuestion::clearTest($this->id);
    }

    /**
     * Обучение заново
     */
    public function reNewTraining(){
        $this->training_status = self::STATUS_NEW;
        $this->save(false, ['training_status']);
        TestTaskQuestion::clearTraining($this->id);
    }
}
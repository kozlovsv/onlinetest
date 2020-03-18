<?php

namespace app\models;

use app\models\query\TestTaskQuery;
use Exception;
use kozlovsv\crud\helpers\ModelPermission;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "test_task".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property int $status Статус
 * @property string $created_at Дата создания
 * @property string $passed_at Дата прохождения
 * @property int $is_repetition Повторение?
 * @property int|null $letter_id Буква
 * @property int $rating Рейтинг
 *
 *
 * @property Letter $letter
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

    /**
     * Рейтинг для оценки 5
     */
    const GRADE_5 = 90;
    /**
     * Рейтинг для оценки 4
     */
    const GRADE_4 = 75;
    /**
     * Рейтинг для оценки 3
     */
    const GRADE_3 = 60;

    const CNT_PANDA_IS_FULL = 5;
    private $_questionsCount;
    private $_questionsPassed;

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
            'status' => 'Тест',
            'statusLabel' => 'Тест',
            'created_at' => 'Создан',
            'passed_at' => 'Пройден',
            'questionsCount' => 'Cлов',
            'uniqueLettersString' => 'Буквы',
            'questionsTestStayCount' => 'Осталось пройти',
            'grade' => 'Оценка',
            'rating' => 'Рейтинг, %',
            'is_repetition' => 'На оценку?',
            'isRepetitionLabel' => 'На оценку?',
            'letter_id' => 'Буква',
            'userName' => 'Ученик'
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
     * Gets query for [[Letter]].
     *
     * @return ActiveQuery
     */
    public function getLetter()
    {
        return $this->hasOne(Letter::class, ['id' => 'letter_id']);
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
     * Осталось пройти вопросов в тесте
     * @return int
     */
    public function getQuestionsTestStayCount()
    {
        return $this->getQuestionsCount() - $this->getQuestionsPassedTestCount();
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
    public function calcRating()
    {
        $cnt = $this->getQuestionsCount();
        if ($cnt == 0 || $this->status == self::STATUS_NEW) return 0;
        $res = $this->getTestTaskQuestions()->sum('result');
        return  round(($res / $cnt) * 100, 0);
    }

    /**
     * Оценка по 5 бальной шкале.
     * @return int
     */
    public function getGrade()
    {
        if ($this->status == self::STATUS_NEW) return 0;
        return self::ratingToGrade($this->rating);
    }

    /**
     * Перевод рейтинга в оценку по 5 бальной шкале.
     * @param int $rating
     * @return int
     */
    public static function ratingToGrade($rating)
    {
        if (!$rating) return 0;
        if ($rating >= self::GRADE_5) return 5;
        if ($rating >= self::GRADE_4) return 4;
        if ($rating >= self::GRADE_3) return 3;
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
    public static function isRepetitionMap() {
        return [ 0 => 'Нет', 1 => 'Да',];
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
     * @return string
     */
    public function getisRepetitionLabel() {
        return $this->is_repetition ? 'Да' : 'Нет';
    }

    public function canTest() {
        return ModelPermission::canCreate(self::tableName()) && ($this->user_id == Yii::$app->user->id);
    }

    /**
     * Обновить тест. Очистить пройденные материалы.
     */
    public function reNewTest(){
        $this->status = self::STATUS_NEW;
        $this->is_repetition = 1;
        $this->passed_at = null;
        $this->save(false, ['status', 'passed_at', 'is_repetition']);
        TestTaskQuestion::clearTest($this->id);
    }


    /**
     * @param int $cntPassedTests Колво пройденных тестов
     * @param int $minRandInterval Минимальное значение случайного интервала
     * @param int $maxRandInterval Максимальное значение случайного интервала
     * @param int $thresholdMax Максимальный порог
     * @param int $thresholdCntTests Количество тестов для прохождения максимального порога
     * @return int
     */
    public static function getRandomTypeViaPassedTests($cntPassedTests, $minRandInterval = 0, $maxRandInterval = 100, $thresholdMax = 50, $thresholdCntTests = 10) {
        $cntPassedTests = min($cntPassedTests, $thresholdCntTests);
        $threshold = intval(round($thresholdMax * $cntPassedTests /  $thresholdCntTests));
        $rand = rand($minRandInterval, $maxRandInterval);
        return $rand >= $threshold ? TestTaskQuestion::TYPE_CHOICE : TestTaskQuestion::TYPE_INPUT;
    }
    /**
     * @param $words array
     * @param bool $isRepetition
     * @param int $letterId
     * @return TestTask|null
     */
    public static function createTestTaskForCurrentUser($words, $isRepetition, $letterId){
        if (!$words) return null;
        $cntTests = TestTask::find()->own()->finished()->passedToday()->count();
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $testTask = new TestTask();
            $testTask->user_id = Yii::$app->user->id;
            $testTask->status = TestTask::STATUS_NEW;
            $testTask->is_repetition = $isRepetition;
            assert($letterId);
            $testTask->letter_id = $letterId;
            if (!$testTask->save(false)) throw new Exception('Не удалось сохранить новый тест в БД');

            foreach ($words as $word)  {
                $testTaskQuestion = new TestTaskQuestion();
                $testTaskQuestion->test_task_id = $testTask->id;
                $testTaskQuestion->vocabulary_word_id = $word['id'];
                $testTaskQuestion->type = self::getRandomTypeViaPassedTests($cntTests);
                if (!$testTaskQuestion->save(false)) throw new Exception('Не удалось сохранить вопрос нового теста в БД');
            }
            $transaction->commit();
            return $testTask;
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error('Создание нового теста.' . $e->getMessage());
            return null;
        }
    }

    public static function getPandaLevel(){
        return TestTask::find()
            ->own()
            ->finished()
            ->passedToday()
            ->andWhere(['is_repetition' => 1])
            ->andWhere(['>=', 'rating', self::GRADE_4])->count();
    }

    public function finishTest(){
        $this->status = TestTask::STATUS_FINISHED;
        $this->passed_at = new Expression('NOW()');
        $this->rating = $this->calcRating();
        $this->save(false);
        UserAchievement::addAchievement($this);
    }

    public function getUserName(){
        return $this->user->name;
    }
}
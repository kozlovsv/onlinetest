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
 *
 *
 * @property User $user
 * @property TestTaskQuestion[] $testTaskQuestions
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
            'status' => 'Статус',
            'statusLabel' => 'Статус',
            'created_at' => 'Создан',
            'passed_at' => 'Пройден',
            'questionsCount' => 'Количество слов',
            'uniqueLettersString' => 'Выбранные буквы',
            'questionsStayCount' => 'Осталось пройти',
            'grade' => 'Оценка',
            'rating' => 'Правильных ответов, %',
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
    public function getQuestionsPassedCount()
    {
        if (is_null($this->_questionsPassed)){
            $this->_questionsPassed = $this->getTestTaskQuestions()->andWhere(['!=', 'answer', ''])->count();
        }
        return $this->_questionsPassed;
    }

    /**
     * Осталось пройти
     * @return int
     */
    public function getQuestionsStayCount()
    {
        return $this->getQuestionsCount() - $this->getQuestionsPassedCount();
    }

    /**
     * Получить номер текущего вопроса
     * @return int
     */
    public function getCurrentNumQuestion()
    {
        return $this->getQuestionsPassedCount() + 1;
    }

    /**
     * % пройденного теста.
     * @return int
     */
    public function getPassedPercent()
    {
        $cnt = $this->getQuestionsCount();
        if ($cnt == 0)  return 0;
        return intval(round(($this->getCurrentNumQuestion() / $cnt) * 100, 2));
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
        $letters = $this->getTestTaskQuestions()->select('letter.title')->joinWith('vocabularyWord.letter')->orderBy('letter.id')->asArray()->all();
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
            self::STATUS_NEW => 'Не закончен',
            self::STATUS_FINISHED => 'Пройден',
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

    public function canTestContinue() {
        return ModelPermission::canCreate(self::tableName()) && ($this->getQuestionsCount() > $this->getQuestionsPassedCount()) && ($this->user_id == Yii::$app->user->id);
    }

    public function canTestRePass() {
        return ModelPermission::canCreate(self::tableName()) && ($this->getQuestionsCount() == $this->getQuestionsPassedCount()) && ($this->user_id == Yii::$app->user->id);
    }

    /**
     * Обновить тест. Очистить пройденные материалы.
     */
    public function reNew(){
        $this->status = self::STATUS_NEW;
        $this->passed_at = null;
        $this->save(false, ['status', 'passed_at']);
        TestTaskQuestion::clear($this->id);
    }
}

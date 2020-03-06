<?php


namespace app\models\form;


use app\models\TestTask;
use app\models\TestTaskQuestion;
use app\models\VocabularyWord;
use yii\base\Model;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

class ChooseAnswerForm extends Model
{
    /**
     * @var string
     */
    public $choice;

    /**
     * @var bool
     */
    public $result;

    /**
     * @var int
     */
    public $test_task_question_id;

    /**
     * @var TestTaskQuestion
     */
    private $_testTaskQuestion;

    public function getTestTaskQuestion() {
        if (is_null($this->_testTaskQuestion)) {
            $this->_testTaskQuestion = $this->getCurrentTestTaskQuestion();
        }
        return $this->_testTaskQuestion;
    }

    public function rules()
    {
        return [
            [['choice', 'test_task_question_id'], 'required', 'message' => 'Необходимо ввести или выбрать значение'],
            [['choice'], 'string'],
            [['test_task_question_id'], 'integer'],
            [['choice'], 'trimChoice'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'choice' => $this->getQuestionType() ? 'Введите значение' : 'Выберите вариант',
        ];
    }


    public function mapQuesions(){
        assert($this->getTestTaskQuestion());
        $word = VocabularyWord::findOne($this->getTestTaskQuestion()->vocabulary_word_id);
        $res = [$word->title];
        foreach ($word->getVocabularyWordVariants()->orderBy(new Expression('rand()'))->limit(2)->asArray()->all() as $word) {
            $res[] = $word['title'];
        }
        shuffle($res);
        $res = array_combine($res, $res);
        return $res;
    }

    public function checkResult() {
        assert($this->getTestTaskQuestion());
        if (is_null($this->result)) {
            $word = VocabularyWord::findOne($this->getTestTaskQuestion()->vocabulary_word_id);
            $this->result = $word->title == $this->choice;
        }
        return $this->result;
    }

    public function save($saveBadResult)
    {
        if (!$this->validate()) {
            return false;
        }
        $this->checkResult();
        if (!$this->result && !$saveBadResult) return true;
        $question = $this->getTestTaskQuestion();
        $question->answer = $this->choice;
        $question->result = $this->result;
        return $question->save(false, ['answer', 'result']);
    }

    protected function getCurrentTestTaskQuestion()
    {
        $_testTaskQuestion = TestTaskQuestion::findOne($this->test_task_question_id);
        if ($_testTaskQuestion === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        return $_testTaskQuestion;
    }

    /**
     * @param TestTask $testTask
     * @return TestTaskQuestion|null
     */
    public function getNextQuestion($testTask) {
        $this->_testTaskQuestion =  $testTask->getTestTaskQuestions()->andWhere(['answer' => ''])->orderBy(new Expression('rand()'))->one();
        if ($this->_testTaskQuestion !== null) {
            $this->test_task_question_id = $this->_testTaskQuestion->id;
        }
        return $this->_testTaskQuestion;
    }

    public function getQuestionType() {
        $testTaskQuestion = $this->getTestTaskQuestion();
        assert($testTaskQuestion);
        return $testTaskQuestion->type;
    }

    public function trimChoice()
    {
        if (!$this->getQuestionType()) return;
        $this->choice = trim(str_replace('ё', 'е', $this->choice));
    }
}
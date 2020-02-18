<?php


namespace app\models\form;


use app\models\TestTaskQuestion;
use app\models\VocabularyWord;
use yii\base\Model;
use yii\db\Expression;

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
     * @var TestTaskQuestion
     */
    public $testTaskQuestion;

    public function init()
    {
        parent::init();
        assert($this->testTaskQuestion && $this->testTaskQuestion instanceof TestTaskQuestion);
    }

    public function rules()
    {
        return [
            [['choice'], 'required', 'message' => 'Необходимо выбрать хотя бы один вариант'],
            [['choice'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'choice' => 'Выберите вариант',
        ];
    }


    public function mapQuesions(){
        $word = VocabularyWord::findOne($this->testTaskQuestion->vocabulary_word_id);
        $res = [$word->title];
        foreach ($word->getVocabularyWordVariants()->orderBy(new Expression('rand()'))->limit(2)->asArray()->all() as $word) {
            $res[] = $word['title'];
        }
        shuffle($res);
        $res = array_combine($res, $res);
        return $res;
    }

    public function checkResult() {
        if (is_null($this->result)) {
            $word = VocabularyWord::findOne($this->testTaskQuestion->vocabulary_word_id);
            $this->result = $word->title == $this->choice;
        }
        return $this->result;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $this->checkResult();
        $this->testTaskQuestion->answer = $this->choice;
        $this->testTaskQuestion->result = $this->result;
        return $this->testTaskQuestion->save(false);
    }

}
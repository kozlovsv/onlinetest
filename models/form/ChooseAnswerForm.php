<?php


namespace app\models\form;


use app\models\TestTaskQuestion;
use app\models\VocabularyWord;
use yii\base\Model;

class ChooseAnswerForm extends Model
{
    /**
     * @var string
     */
    public $choice;

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
        foreach ($word->getVocabularyWordVariants()->asArray()->all() as $word) {
            $res[] = $word['title'];
        }
        shuffle($res);
        $res = array_combine($res, $res);
        return $res;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $word = VocabularyWord::findOne($this->testTaskQuestion->vocabulary_word_id);
        $result = $word->title == $this->choice;
        $this->testTaskQuestion->answer = $this->choice;
        $this->testTaskQuestion->result = $result;
        $this->testTaskQuestion->save(false);
        return true;
    }

}
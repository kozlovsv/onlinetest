<?php


namespace app\models\form;


use app\models\TestTaskQuestion;
use yii\base\Model;

class TrainingForm extends Model
{
    /**
     * @var int
     */
    public $choice = 1;

    public function rules()
    {
        return [
            [['choice'], 'safe'],
        ];
    }

    /**
     * @var TestTaskQuestion
     */
    public $testTaskQuestion;

    public function init()
    {
        parent::init();
        assert($this->testTaskQuestion && $this->testTaskQuestion instanceof TestTaskQuestion);
    }

    public function save()
    {
        $this->testTaskQuestion->training_result = 1;
        return $this->testTaskQuestion->save(false);
    }
}
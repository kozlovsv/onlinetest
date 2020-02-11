<?php


namespace app\models\form;


use app\models\Letter;
use app\models\TestTask;
use app\models\TestTaskQuestion;
use app\models\VocabularyWord;
use Exception;
use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class CreateTestTaskForm extends Model
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $cnt_words = 10;

    /**
     * @var array
     */
    public $letters = [];

    const CNT_WORDS_RANGE = [10, 20, 30];

    public function rules()
    {
        return [
            [['letters'], 'required', 'message' => 'Выберите хотябы одну букву'],
            [['cnt_words'], 'integer'],
            ['cnt_words', 'in', 'range' => self::CNT_WORDS_RANGE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cnt_words' => 'Выберите количество слов',
            'letters' => 'Выберите буквы',
        ];
    }


    public static function mapLetters()
    {
        $items = Letter::find()->orderBy(['id' => SORT_ASC])->all();
        return ArrayHelper::map($items, 'id', 'title');
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $words = VocabularyWord::find()->select(['id'])->where(['letter_id' => array_values($this->letters)])->orderBy(new Expression('rand()'))->limit($this->cnt_words)->asArray()->all();
            assert($words);
            $testTask = new TestTask();
            $testTask->user_id = Yii::$app->user->id;
            $testTask->status = TestTask::STATUS_NEW;
            if (!$testTask->save(false)) throw new Exception('Не удалось сохранить новый тест в БД');
            $this->id = $testTask->id;
            foreach ($words as $word)  {
                $testTaskQuestion = new TestTaskQuestion();
                $testTaskQuestion->test_task_id = $testTask->id;
                $testTaskQuestion->vocabulary_word_id = $word['id'];
                if (!$testTaskQuestion->save(false)) throw new Exception('Не удалось сохранить вопрос нового теста в БД');
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error('Создание нового теста.' . $e->getMessage());
            return false;
        }
    }
}
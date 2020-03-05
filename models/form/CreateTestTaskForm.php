<?php


namespace app\models\form;


use app\models\Letter;
use app\models\TestTask;
use app\models\UserAchievement;
use app\models\VocabularyWord;
use yii\base\Model;
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
    public $cnt_words = 20;

    /**
     * @var array
     */
    public $letters = [];

    const CNT_WORDS_RANGE = [0, 20, 30];

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
        $items = Letter::find()->where(['exists', UserAchievement::find()->own()->select('letter_id')->andWhere('user_achievement.letter_id = letter.id')])->orderBy(['id' => SORT_ASC])->all();
        return ArrayHelper::map($items, 'id', 'title');
    }

    public static function mapCntWords()
    {
        $map = array_combine(CreateTestTaskForm::CNT_WORDS_RANGE, CreateTestTaskForm::CNT_WORDS_RANGE);
        $map[0] = 'Все';
        return $map;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $words = $this->getWords();
        $testTask = TestTask::createTestTaskForCurrentUser($words, true, $this->letters[0]);
        if (!$testTask) return false;
        $this->id = $testTask->id;
        return true;
    }

    public function getWords()
    {
        return VocabularyWord::geLearnedWords($this->letters, $this->cnt_words);
    }
}
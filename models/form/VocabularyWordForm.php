<?php

namespace app\models\form;

use app\models\VocabularyWord;
use app\models\VocabularyWordVariant;
use Exception;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "vocabulary_word".
 *
 * @property int $id
 * @property string $title Слово
 * @property string $variant1 Неправильный вариант 1
 * @property string $variant2 Неправильный вариант 2
 * @property string $variant3 Неправильный вариант 3
 * @property string $variant4 Неправильный вариант 4
 * @property string $variant5 Неправильный вариант 5
**/

class VocabularyWordForm extends Model
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $variant1;
    /**
     * @var string
     */
    public $variant2;
    /**
     * @var string
     */
    public $variant3;
    /**
     * @var string
     */
    public $variant4;
    /**
     * @var string
     */
    public $variant5;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'variant1'], 'required'],
            [['title', 'variant1', 'variant2', 'variant3', 'variant4', 'variant5'], 'string', 'max' => 255],
            [['title'], 'unique', 'message' => 'Данное слово уже есть в базе', 'targetClass' => VocabularyWord::class, 'targetAttribute' => 'title'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'title' => 'Слово',
            'variant1' => 'Неправильный вариант 1',
            'variant2' => 'Неправильный вариант 2',
            'variant3' => 'Неправильный вариант 3',
            'variant4' => 'Неправильный вариант 4',
            'variant5' => 'Неправильный вариант 5',
        ];
    }

    public function save()
    {
        function insertWordVariant($title, $word_id) {
            if (!$title) return;
            $wordVariant = new VocabularyWordVariant();
            $wordVariant->vocabulary_word_id = $word_id;
            $wordVariant->title = $title;
            if (!$wordVariant->save(false)) throw new Exception('Не удалось добавить неправильный вариант слова');
        }

        if (!$this->validate()) {
            Yii::warning('Model not inserted due to validation error.', __METHOD__);
            return false;
        }

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $word = new VocabularyWord();
            $word->title = $this->title;
            if (!$word->save(false)) throw new Exception('Не удалось добавить слово');
            insertWordVariant($this->variant1, $word->id);
            insertWordVariant($this->variant2, $word->id);
            insertWordVariant($this->variant3, $word->id);
            insertWordVariant($this->variant4, $word->id);
            insertWordVariant($this->variant5, $word->id);
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}

<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "letter_level".
 *
 * @property int $id
 * @property int $letter_id Буква
 * @property int $cnt_word_in_level Количество слов в уровне
 * @property int $cnt_level Количество уровней
 *
 * @property Letter $letter
 */
class LetterLevel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'letter_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['letter_id', 'cnt_word_in_level', 'cnt_level'], 'required'],
            [['letter_id', 'cnt_word_in_level', 'cnt_level'], 'integer'],
            [['letter_id'], 'unique'],
            [['letter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::class, 'targetAttribute' => ['letter_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'letter_id' => 'Буква',
            'cnt_word_in_level' => 'Количество слов в уровне',
            'cnt_level' => 'Количество уровней',
        ];
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

    public static function autoFill($cntWordsInLevel = 10)
    {
        self::deleteAll();
        $levels = VocabularyWord::find()->select(['letter_id', 'count(*) as cnt'])->orderBy('letter_id')->groupBy('letter_id')->asArray()->all();
        foreach ($levels as $level) {
            $cntWords = $level['cnt'];
            if (!$cntWords) continue;
            $l = new LetterLevel();
            $l->letter_id = $level['letter_id'];
            $l->cnt_level = self::calcCntLevel($cntWords, $cntWordsInLevel);
            $l->cnt_word_in_level = $cntWordsInLevel;
            $l->save(false);
        }
    }

    public static function calcCntLevel($cntWords, $cntWordsInLevel) {
        return intval(ceil($cntWords / $cntWordsInLevel));
    }

    public static function mapCntLevel(){
        $items = self::find()->select(['letter_id', 'cnt_level'])->asArray()->all();
        return ArrayHelper::map($items, 'letter_id', 'cnt_level');
    }
}

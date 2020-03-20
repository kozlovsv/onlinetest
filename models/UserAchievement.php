<?php

namespace app\models;

use app\models\query\UserAchievementQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_achievement".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property int $letter_id Буква
 * @property string $created_at Дата создания
 * @property int $test_task_id Тест
 *
 * @property Letter $letter
 * @property TestTask $testTask
 * @property User $user
 * @property UserAchievementWord[] $userAchievementWords
 *
 * @see UserAchievementQuery
 */
class UserAchievement extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_achievement';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'letter_id'], 'required'],
            [['user_id', 'letter_id', 'test_task_id'], 'integer'],
            [['created_at'], 'safe'],
            [['letter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::class, 'targetAttribute' => ['letter_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'letter_id' => 'Буква',
            'created_at' => 'Дата создания',
            'test_task_id' => 'Тест',
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

    /**
     * Gets query for [[TestTask]].
     *
     * @return ActiveQuery
     */
    public function getTestTask()
    {
        return $this->hasOne(TestTask::class, ['id' => 'test_task_id']);
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
     * Gets query for [[UserAchievementWords]].
     *
     * @return ActiveQuery
     */
    public function getUserAchievementWords()
    {
        return $this->hasMany(UserAchievementWord::class, ['user_achievement_id' => 'id']);
    }

    public static function getLevelsForLetters()
    {
        $items = self::find()->own()->select(['letter_id', 'count(*) as cnt'])->groupBy('letter_id')->asArray()->all();
        return ArrayHelper::map($items, 'letter_id', 'cnt');
    }

    public static function getLevel($letterId)
    {
        return self::find()->own()->andWhere(['letter_id' => $letterId])->count();
    }

    /**
     * @inheritdoc
     * @return UserAchievementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserAchievementQuery(get_called_class());
    }

    /**
     * @param $testTask
     * @return UserAchievement
     */
    private static function createAchievment($testTask)
    {
        $userAchievment = new self();
        $userAchievment->user_id = Yii::$app->user->id;
        $userAchievment->letter_id = $testTask->letter_id;
        $userAchievment->test_task_id = $testTask->id;
        $userAchievment->save(false);
        return $userAchievment;
    }

    /**
     * @param TestTask $testTask
     */
    public static function addAchievement($testTask)
    {
        if ($testTask->is_repetition || empty($testTask->letter_id)) return;

        $words = $testTask->getTestTaskQuestions()->select('vocabulary_word_id')->asArray()->all();
        $cnt_word_in_level = $testTask->letter->letterLevel->cnt_word_in_level;

        $userAchievment = self::createAchievment($testTask);

        $cntWords = 0;
        foreach ($words as $word) {
            $cntWords ++;
            if ($cntWords > $cnt_word_in_level) {
                $cntWords = 1;
                $userAchievment = self::createAchievment($testTask);
            }
            $rec = new UserAchievementWord();
            $rec->user_achievement_id = $userAchievment->id;
            $rec->vocabulary_word_id = $word['vocabulary_word_id'];
            $rec->save(false);
        }
    }
}

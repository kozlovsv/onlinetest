<?php

namespace app\models\search;

use app\models\Letter;
use app\models\TestTask;
use app\models\UserAchievement;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;


/**
 * TestTaskSearch represents the model behind the search form of `app\models\TestTask`.
 */
class UserRatingViewSearch extends ActiveRecord
{
    public $corona_cnt;
    public $repetition_cnt;
    public $average_rating;
    public $cnt_level;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Буква',
            'corona_cnt' => 'Корон',
            'repetition_cnt' => 'Панд',
            'average_rating' => 'Рейтинг',
            'averageGrade' => 'Оценка',
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getAverageGrade() {
        return TestTask::ratingToGrade($this->average_rating);
    }

    /**
     * @return bool
     */
    public function getLevelIsFull(){
        return ($this->corona_cnt >= $this->cnt_level);
    }

    public static function tableName()
    {
        return Letter::tableName();
    }

    /**
     * Creates data provider instance with search query applied
     * @param int $user_id
     * @return ArrayDataProvider
     */
    public function search($user_id)
    {

        $query = self::find()
            ->select([
                'letter.id',
                'letter.title',
                'cnt_level',
                'corona_cnt' => UserAchievement::find()->select('COUNT(*)')->where(['user_id' => $user_id])->andWhere('letter_id = letter.id'),
                'repetition_cnt' => TestTask::find()->select('COUNT(*)')->where(['user_id' => $user_id])->andWhere('letter_id = letter.id')->andWhere(['is_repetition' => 1, 'status' => TestTask::STATUS_FINISHED]),
                'average_rating' => TestTask::find()->select('AVG(rating)')->where(['user_id' => $user_id])->andWhere('letter_id = letter.id')->andWhere(['is_repetition' => 1, 'status' => TestTask::STATUS_FINISHED]),
            ])
            ->innerJoin('letter_level', 'letter_level.letter_id = letter.id')
            ->having(['>', 'corona_cnt', 0])
            ->orderBy('letter.id');


        return new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => false,
            'pagination' => false,
        ]);
    }
}

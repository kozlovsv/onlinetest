<?php

namespace app\models\search;

use app\models\TestTask;
use app\models\User;
use app\models\UserAchievement;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;


/**
 * TestTaskSearch represents the model behind the search form of `app\models\TestTask`.
 */
class UserRatingSearch extends ActiveRecord
{
    public $corona_cnt;
    public $repetition_cnt;
    public $average_rating;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ученик',
            'corona_cnt' => 'Корон',
            'repetition_cnt' => 'Контрольных',
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

    public static function primaryKey()
    {
        return ['id'];
    }

    public static function tableName()
    {
        return User::tableName();
    }

    public function getAverageGrade() {
        return TestTask::ratingToGrade($this->average_rating);
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = self::find()
            ->select([
                'user.id',
                'name',
                'corona_cnt' => UserAchievement::find()->select('COUNT(*)')->where('user_id = user.id'),
                'repetition_cnt' => TestTask::find()->select('COUNT(*)')->where('user_id = user.id')->andWhere(['is_repetition' => 1, 'status' => TestTask::STATUS_FINISHED]),
                'average_rating' => TestTask::find()->select('AVG(rating)')->where('user_id = user.id')->andWhere(['is_repetition' => 1, 'status' => TestTask::STATUS_FINISHED]),
            ])
            ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->andWhere(['auth_assignment.item_name' => User::ROLE_STUDENT]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['corona_cnt'=>SORT_DESC],
                'attributes' => [
                    'name' => [
                        'asc' => ['user.name' => SORT_ASC],
                        'desc' => ['user.name' => SORT_DESC],
                    ],
                    'corona_cnt' => [
                        'asc' => ['corona_cnt' => SORT_ASC],
                        'desc' => ['corona_cnt' => SORT_DESC],
                    ],
                    'repetition_cnt' => [
                        'asc' => ['repetition_cnt' => SORT_ASC],
                        'desc' => ['repetition_cnt' => SORT_DESC],
                    ],
                    'average_rating' => [
                        'asc' => ['average_rating' => SORT_ASC],
                        'desc' => ['average_rating' => SORT_DESC],
                    ],
                    'averageGrade' => [
                        'asc' => ['average_rating' => SORT_ASC],
                        'desc' => ['average_rating' => SORT_DESC],
                    ]

                ]
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
}

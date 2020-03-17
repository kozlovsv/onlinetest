<?php

namespace app\models\search;

use app\models\User;
use kozlovsv\crud\helpers\DateTimeHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TestTask;


/**
 * TestTaskSearch represents the model behind the search form of `app\models\TestTask`.
 */
class StudentTestTaskSearch extends TestTask
{
    public $is_repetition = 1;
    public $status = TestTask::STATUS_FINISHED;
    public $userName;
    public $grade;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'letter_id', 'is_repetition', 'status'], 'integer'],
            [['created_at', 'passed_at', 'userName', 'grade'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TestTask::find()->byUserRole(User::ROLE_STUDENT)->with('letter', 'user');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['passed_at'=>SORT_DESC],
                'attributes' => [
                    'id',
                    'passed_at',
                    'userName' => [
                        'asc' => ['user.name' => SORT_ASC],
                        'desc' => ['user.name' => SORT_DESC],
                    ],
                    'grade' => [
                        'asc' => ['rating' => SORT_ASC],
                        'desc' => ['rating' => SORT_DESC],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'test_task.user_id' => $this->user_id,
            'letter_id' => $this->letter_id,
            'is_repetition' => $this->is_repetition,
            'DATE(passed_at)' => DateTimeHelper::convertBySave($this->passed_at),
            'DATE(created_at)' => DateTimeHelper::convertBySave($this->created_at),
        ]);
        return $dataProvider;
    }
}

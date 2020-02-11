<?php

namespace app\models\search;

use kozlovsv\crud\helpers\DateTimeHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TestTask;


/**
 * TestTaskSearch represents the model behind the search form of `app\models\TestTask`.
 */
class TestTaskSearch extends TestTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['status', 'created_at'], 'safe'],
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
        $query = TestTask::find()->own();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'DATE(created_at)' => DateTimeHelper::convertBySave($this->created_at)
        ]);
        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}

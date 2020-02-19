<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VocabularyWord;


/**
 * VocabularyWordSearch represents the model behind the search form of `app\models\VocabularyWord`.
 */
class VocabularyWordSearch extends VocabularyWord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'letter_id'], 'integer'],
            [['title'], 'safe'],
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
        $query = VocabularyWord::find()->with('letter');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
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
            'letter_id' => $this->letter_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}

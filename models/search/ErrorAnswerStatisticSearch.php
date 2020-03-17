<?php

namespace app\models\search;

use app\models\TestTaskQuestion;
use kozlovsv\crud\helpers\DateTimeHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;



class ErrorAnswerStatisticSearch extends ActiveRecord
{
    public $cnt;
    public $word_title;
    public $letter_id;
    public $passed_at;

    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['letter_id', 'cnt'], 'integer'],
            ['passed_at', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cnt' => 'Кол-во',
            'word_title' => 'Слово',
            'letter_id' => 'Буква',
            'passed_at' => 'Дата теста',
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

    public static function tableName()
    {
        return TestTaskQuestion::tableName();
    }

    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()
            ->select(['COUNT(*) as cnt', 'vocabulary_word_id as id', 'vocabulary_word.title as word_title', 'letter.title as letter_title'])
            ->andWhere(['test_task_question.result' => 0])
            ->innerJoin('vocabulary_word', 'test_task_question.vocabulary_word_id = vocabulary_word.id')
            ->innerJoin('letter', 'vocabulary_word.letter_id = letter.id')
            ->innerJoin('test_task', 'test_task_question.test_task_id = test_task.id')
            ->groupBy(['vocabulary_word_id']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['cnt'=>SORT_DESC],
                'attributes' => [
                    'cnt' => [
                        'asc' => ['cnt' => SORT_ASC],
                        'desc' => ['cnt' => SORT_DESC],
                    ],
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
            'vocabulary_word.letter_id' => $this->letter_id,
            'DATE(test_task.passed_at)' => DateTimeHelper::convertBySave($this->passed_at),
        ]);
        return $dataProvider;
    }
}

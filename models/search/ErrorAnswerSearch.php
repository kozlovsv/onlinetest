<?php

namespace app\models\search;

use app\models\TestTask;
use app\models\TestTaskQuestion;
use kozlovsv\crud\helpers\DateTimeHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;


/**
 * TestTaskSearch represents the model behind the search form of `app\models\TestTask`.
 * @property int type
 */
class ErrorAnswerSearch extends ActiveRecord
{

    public $passed_at;
    public $user_id;
    public $user_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type'], 'integer'],
            ['passed_at', 'safe'],
        ];
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function formName()
    {
        return '';
    }

    public static function tableName()
    {
        return TestTaskQuestion::tableName();
    }

    public static function getSafeParams()
    {
        $model = new self();
        $params = Yii::$app->getRequest()->getQueryParams();
        $attributes = array_flip($model->safeAttributes());
        $new_params = [];
        foreach ($params as $name => $value) {
            if (isset($attributes[$name])) {
                $new_params[$name] = $value;
            }
        }
        return $new_params;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'passed_at' => 'Дата теста',
            'user_name' => 'Ученик',
            'answer' => 'Ответ',
            'test_task_id' => 'Тест',
            'user_id' => 'Ученик',
            'type' => 'Тип',
            'typeLabel' => 'Тип',
        ]);
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
     * @return int|mixed
     */
    public function getTypeLabel()
    {
        $map = TestTaskQuestion::typeMap();
        return isset($map[$this->type]) ? $map[$this->type] : $this->type;
    }

    /**
     * Creates data provider instance with search query applied
     * @param $vocabulary_word_id
     * @param array $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function search($vocabulary_word_id, $params)
    {
        $query = self::find()
            ->select(['user_name' => 'user.name' , 'answer', 'passed_at', 'test_task_id', 'test_task_question.id', 'test_task_question.type'])
            ->innerJoin('test_task', 'test_task_question.test_task_id = test_task.id')
            ->innerJoin('user', 'test_task.user_id = user.id')
            ->andWhere([
                'test_task_question.result' => 0,
                'test_task.status' => TestTask::STATUS_FINISHED,
                'vocabulary_word_id' => $vocabulary_word_id
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['test_task_id'=>SORT_DESC],
                'attributes' => [
                    'test_task_id',
                    'answer',
                    'passed_at' => [
                        'asc' => ['test_task.passed_at' => SORT_ASC],
                        'desc' => ['test_task.passed_at' => SORT_DESC],
                    ],
                    'user_name' => [
                        'asc' => ['user.name' => SORT_ASC],
                        'desc' => ['user.name' => SORT_DESC],
                    ],
                    'typeLabel' => [
                        'asc' => ['test_task_question.type' => SORT_ASC],
                        'desc' => ['test_task_question.type' => SORT_DESC],
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
            'test_task.user_id' => $this->user_id,
            'test_task_question.type' => $this->type,
            'DATE(test_task.passed_at)' => DateTimeHelper::convertBySave($this->passed_at),
        ]);
        return $dataProvider;
    }
}

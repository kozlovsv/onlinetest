<?php
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator app\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->getModelClass());
$searchModelClass = StringHelper::basename($generator->getSearchModelClass());
$searchConditions = $generator->generateSearchConditions();

$rules = $generator->generateSearchRules();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->getSearchModelClass(), '\\')) ?>;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use <?= ltrim($generator->getModelClass(), '\\') ?>;


/**
 * <?= $searchModelClass ?> represents the model behind the search form of `<?= $generator->getModelClass() ?>`.
 */
class <?= $searchModelClass ?> extends <?= $modelClass ?>

{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
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
        $query = <?= $modelClass ?>::find();

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
        <?= implode("\n        ", $searchConditions) ?>

        return $dataProvider;
    }
}

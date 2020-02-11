<?php
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator app\generators\crud\Generator */


echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->getControllerClass(), '\\')) ?>;

use <?= ltrim($generator->getSearchModelClass(), '\\') ?>;
use kozlovsv\crud\controllers\CrudController;
use yii\db\ActiveRecord;

/**
 * <?= StringHelper::basename($generator->getControllerClass()) ?> implements the CRUD actions for <?= StringHelper::basename($generator->getModelClass()) ?> model.
 */
class <?= StringHelper::basename($generator->getControllerClass()) ?> extends CrudController
{
    /**
     * Возвращает модель для поиска
     * @return ActiveRecord
     */
    public function getSearchModel()
    {
        return new <?= StringHelper::basename($generator->getSearchModelClass()) ?>();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return '<?= $generator->getModelClass() ?>';
    }
}
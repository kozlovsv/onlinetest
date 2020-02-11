<?php

namespace app\controllers;

use app\models\search\VocabularyWordVariantSearch;
use app\models\VocabularyWordVariant;
use Exception;
use kozlovsv\crud\controllers\CrudController;
use kozlovsv\crud\helpers\ReturnUrl;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * VocabularyWordVariantController implements the CRUD actions for VocabularyWordVariant model.
 */
class VocabularyWordVariantController extends CrudController
{

    public $addFlashMessages = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['verbs']);
        return $behaviors;
    }

    /**
     * Возвращает модель для поиска
     * @return VocabularyWordVariantSearch
     */
    public function getSearchModel()
    {
        return new VocabularyWordVariantSearch();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return 'app\models\VocabularyWordVariant';
    }

    /**
     * @return ActiveRecord
     */
    public function createModel(){
        /** @var VocabularyWordVariant $model */
        $model = parent::createModel();
        $model->vocabulary_word_id = intval(Yii::$app->request->getQueryParam('uid'));
        if (!$model->vocabulary_word_id) throw new Exception('При создании записи не задан ID словарного слова');
        ReturnUrl::setReturnUrlParam('/vocabulary-word/' . $model->vocabulary_word_id);
        return $model;
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = parent::findModel($id);
        ReturnUrl::setReturnUrlParam('/vocabulary-word/' . $model->vocabulary_word_id);
        return $model;
    }

    /**
     * Возврат после добавления записи
     * @return Response
     */
    public function goBackAfterDelete() {
        return ReturnUrl::goBack($this, $this->defaultBackUrl, true);
    }

    /**
     * @return string
     */
    public function getPermissionCategory()
    {
        return 'vocabulary_word';
    }
}
<?php

namespace app\controllers;

use app\models\form\VocabularyWordForm;
use app\models\search\VocabularyWordSearch;
use Exception;
use kozlovsv\crud\controllers\CrudController;
use kozlovsv\crud\helpers\ModelPermission;
use Yii;
use yii\db\ActiveRecord;

/**
 * VocabularyWordController implements the CRUD actions for VocabularyWord model.
 */
class VocabularyWordController extends CrudController
{
    public function init()
    {
        parent::init();
        $permissionCategory = $this->getPermissionCategory();
        $this->accessRules = [
            [
                'actions' => ['create-variant'],
                'allow' => ModelPermission::canCreate($permissionCategory),
            ],
        ];
    }

    /**
     * Возвращает модель для поиска
     * @return ActiveRecord
     */
    public function getSearchModel()
    {
        return new VocabularyWordSearch();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return 'app\models\VocabularyWord';
    }

    public function actionCreateVariant()
    {
        try {
            $model = new VocabularyWordForm();
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
                return $this->goBackAfterCreate();
            }
            return $this->renderIfAjax('create-variant', compact('model'));
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            $message = 'При создании записи произошла ошибка. Обратитесь в службу поддержки.';
            Yii::$app->session->setFlash('error', $message);
            return $this->goBackAfterCreate();
        }
    }
}
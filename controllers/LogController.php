<?php

namespace app\controllers;

use app\models\Log;
use app\models\search\LogSearch;
use Exception;
use kozlovsv\crud\controllers\CrudController;
use Yii;
use yii\db\ActiveRecord;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends CrudController
{

    public function init()
    {
        parent::init();
        $this->accessRules = [
            [
                'actions' => ['delete-all'],
                'allow' => '@',
            ],
        ];
    }



    /**
     * Возвращает модель для поиска
     * @return ActiveRecord
     */
    public function getSearchModel()
    {
        return new LogSearch();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return 'app\models\Log';
    }

    /**
     * Deletes an existing Log model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDeleteAll()
    {
        try {
            $ids = Yii::$app->request->post('ids');
            $count = Log::deleteAll([
                'id' => $ids,
            ]);
            if ($count) {
                Yii::$app->session->setFlash('success', 'Записи успешно удалены');
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $message = 'При удалении записей произошла ошибка.';
            Yii::$app->session->setFlash('error', $message);
        }
        return '';
    }
}
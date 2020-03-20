<?php

namespace app\controllers;

use app\models\Log;
use app\models\search\LogSearch;
use app\models\TestTask;
use Exception;
use kozlovsv\crud\controllers\CrudController;
use kozlovsv\crud\helpers\ModelPermission;
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
        $permissionCategory = $this->getPermissionCategory();
        $this->accessRules = [
            [
                'actions' => ['delete-all', 'delete-empty-test'],
                'allow' => ModelPermission::canDelete($permissionCategory),
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

    /**
     * Deletes an existing Log model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDeleteEmptyTest()
    {
        try {
            $count = TestTask::deleteAll([
                'is_repetition' => 0,
                'status' => TestTask::STATUS_NEW,
            ]);
            Yii::$app->session->setFlash('success', "Удалено {$count} тестов");

        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $message = 'При удалении тестов произошла ошибка.';
            Yii::$app->session->setFlash('error', $message);
        }
        return $this->goBackAfterDelete();
    }
}
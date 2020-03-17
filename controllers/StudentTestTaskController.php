<?php

namespace app\controllers;


use app\models\search\StudentTestTaskSearch;
use app\models\TestTask;
use kozlovsv\crud\controllers\CrudController;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;


/**
 * TestTaskController implements the CRUD actions for TestTask model.
 */
class StudentTestTaskController extends CrudController
{
    /**
     * Возвращает модель для поиска
     * @return StudentTestTaskSearch|ActiveRecord
     */
    public function getSearchModel()
    {
        return new StudentTestTaskSearch();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return 'app\models\TestTask';
    }

    /**
     * @param $id
     * @return TestTask
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = TestTask::find()->andWhere(['id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        $this->model = $model;
        return $model;
    }

    /**
     * @return string
     */
    public function getPermissionCategory()
    {
        return 'student_test_task';
    }
}
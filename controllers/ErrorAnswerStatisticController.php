<?php

namespace app\controllers;

use app\models\search\ErrorAnswerStatisticSearch;
use app\models\search\StudentTestTaskSearch;
use app\models\search\ErrorAnswerSearch;
use app\models\VocabularyWord;
use kozlovsv\crud\controllers\CrudController;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
  * @package app\controllers
 */
class ErrorAnswerStatisticController extends CrudController
{

    /**
     * @return string
     */
    public function getPermissionCategory()
    {
        return 'student_test_task';
    }

    /**
     * Возвращает модель для поиска
     * @return StudentTestTaskSearch|ActiveRecord
     */
    public function getSearchModel()
    {
        return new ErrorAnswerStatisticSearch();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return 'app\models\TestTaskQuestion';
    }


    public function findVocabularyWordModel($id)
    {
        $model = VocabularyWord::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        return $model;
    }

    public function actionView($id)
    {
        $model = $this->findVocabularyWordModel($id);
        $searchModel = new ErrorAnswerSearch();
        /** @noinspection PhpUndefinedMethodInspection */
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            ''
        ]);
    }


}

<?php

namespace app\controllers;

use app\models\form\ChooseAnswerForm;
use app\models\form\CreateTestTaskForm;
use app\models\search\TestTaskSearch;
use app\models\TestTask;
use Exception;
use kozlovsv\crud\controllers\CrudController;
use kozlovsv\crud\helpers\ModelPermission;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TestTaskController implements the CRUD actions for TestTask model.
 */
class TestTaskController extends CrudController
{
    public function init()
    {
        parent::init();
        $permissionCategory = $this->getPermissionCategory();
        $this->accessRules = [
            [
                'actions' => ['next', 'repass'],
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
        return new TestTaskSearch();
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
        $model = TestTask::find()->own()->andWhere(['id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        $this->model = $model;
        return $model;
    }

    /**
     * @return CreateTestTaskForm
     */
    public function createModel()
    {
        $this->addFlashMessages = false;
        $this->model =  new CreateTestTaskForm();
        return $this->model;
    }

    /**
     * @return Response
     */
    public function goBackAfterCreate()
    {
        return $this->redirect(Url::to(['next', 'id' => $this->model->id]));
    }

    public function actionNext($id)
    {
        try {
            $testTask = $this->findModel($id);
            $question = $testTask->getTestTaskQuestions()->andWhere(['answer' => ''])->orderBy('id')->one();

            if (!$question) {
                $testTask->status = TestTask::STATUS_FINISHED;
                $testTask->passed_at = new Expression('NOW()');
                $testTask->save(false);
                if ($this->addFlashMessages) Yii::$app->session->setFlash('success', 'Тест успешно пройден');
                return $this->redirect(Url::to(['view', 'id' => $id]));
            }
            $post = Yii::$app->request->post();
            $model = new ChooseAnswerForm(['testTaskQuestion' => $question]);
            if ($model->load($post) && $model->save()) {
                return $this->redirect(Url::to(['next', 'id' => $id]));
            }
            return $this->renderIfAjax('next', compact('model', 'testTask'));
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            if ($this->addFlashMessages) {
                $message = 'При создании записи произошла ошибка. Обратитесь в службу поддержки.';
                Yii::$app->session->setFlash('error', $message);
            }
            return $this->goBackAfterCreate();
        }
    }

    public function actionRepass($id)
    {
        $testTask = $this->findModel($id);
        $testTask->reNew();
        return $this->redirect(Url::to(['next', 'id' => $id]));
    }

}
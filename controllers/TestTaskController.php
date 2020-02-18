<?php

namespace app\controllers;

use app\models\form\ChooseAnswerForm;
use app\models\form\CreateTestTaskForm;
use app\models\form\TrainingForm;
use app\models\search\TestTaskSearch;
use app\models\TestTask;
use app\models\TestTaskQuestion;
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
    public $layout = 'test.php';

    public function init()
    {
        parent::init();
        $permissionCategory = $this->getPermissionCategory();
        $this->accessRules = [
            [
                'actions' => ['test', 're-test', 'error-answer', 'training', 're-training'],
                'allow' => ModelPermission::canCreate($permissionCategory),
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main.php';
        return parent::actionIndex();
    }

    public function actionView($id)
    {
        $this->layout = 'main.php';
        return parent::actionView($id);
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
     * @param $id
     * @return TestTaskQuestion
     * @throws NotFoundHttpException
     */
    public function findQuestionModel($id)
    {
        $model = TestTaskQuestion::find()->andWhere(['id' => $id, 'result' => 0])->one();
        if ($model === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        return $model;
    }

    /**
     * @return CreateTestTaskForm
     */
    public function createModel()
    {
        $this->addFlashMessages = false;
        $this->model = new CreateTestTaskForm();
        return $this->model;
    }

    /**
     * @return Response
     */
    public function goBackAfterCreate()
    {
        return $this->model->training_mode ? $this->redirect(Url::to(['training', 'id' => $this->model->id])) : $this->redirect(Url::to(['test', 'id' => $this->model->id]));
    }

    public function actionTest($id)
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
                if ($model->checkResult())
                    return $this->redirect(Url::to(['test', 'id' => $id]));
                else
                    return $this->redirect(Url::to(['error-answer', 'id' => $question->id]));
            }
            return $this->renderIfAjax('test', compact('model', 'testTask'));
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

    public function actionTraining($id)
    {
        try {
            $testTask = $this->findModel($id);
            $question = $testTask->getTestTaskQuestions()->andWhere(['training_result' => 0])->orderBy('id')->one();

            if (!$question) {
                $testTask->training_status = TestTask::STATUS_FINISHED;
                $testTask->save(false);
                if ($this->addFlashMessages) Yii::$app->session->setFlash('success', 'Обучение успешно пройдено');
                return $this->redirect(Url::to(['view', 'id' => $id]));
            }
            $post = Yii::$app->request->post();
            //dd($post);
            $model = new TrainingForm(['testTaskQuestion' => $question]);
            if ($model->load($post) && $model->save()) {
                return $this->redirect(Url::to(['training', 'id' => $id]));
            }
            return $this->renderIfAjax('training', compact('model', 'testTask'));
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            return $this->goBackAfterCreate();
        }
    }

    public function actionErrorAnswer($id)
    {
        $model = $this->findQuestionModel($id);
        return $this->renderIfAjax('error-answer', compact('model'));
    }

    public function actionReTest($id)
    {
        $testTask = $this->findModel($id);
        $testTask->reNewTest();
        return $this->redirect(Url::to(['test', 'id' => $id]));
    }

    public function actionReTraining($id)
    {
        $testTask = $this->findModel($id);
        $testTask->reNewTraining();
        return $this->redirect(Url::to(['training', 'id' => $id]));
    }

}
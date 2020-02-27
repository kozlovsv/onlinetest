<?php

namespace app\controllers;

use app\models\form\ChooseAnswerForm;
use app\models\form\CreateTestTaskForm;
use app\models\Letter;
use app\models\LetterLevel;
use app\models\search\TestTaskSearch;
use app\models\TestTask;
use app\models\TestTaskQuestion;
use app\models\UserAchievement;
use app\models\VocabularyWord;
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
                'actions' => ['test', 'test-letter', 're-test', 'letter-popup', 'letter-full-popup', 'test-cancel', 'training-letter', 'training-test', 'win'],
                'allow' => ModelPermission::canCreate($permissionCategory),
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main.php';
        return parent::actionIndex();
    }

    public function actionWin($id)
    {
        $model = $this->findModel($id);
        if ($model->status != TestTask::STATUS_FINISHED || empty($model->letter_id)) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        $letterLevel = LetterLevel::find()->andWhere(['letter_id' => $model->letter_id])->one();
        $level = UserAchievement::find()->andWhere(['letter_id' => $model->letter_id])->count();
        return $this->render('win', compact('model', 'letterLevel', 'level'));
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
     * @param $id
     * @return Letter
     * @throws NotFoundHttpException
     */
    public function findLetterModel($id)
    {
        $letter = Letter::findOne($id);
        if ($letter === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        return $letter;
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
        return $this->redirect(Url::to(['test', 'id' => $this->model->id]));
    }

    public function actionTest($id)
    {
        try {
            $testTask = $this->findModel($id);
            $model = new ChooseAnswerForm();
            if ($model->load(Yii::$app->request->post())) {
                if (!$model->save($testTask->is_repetition))
                    return $this->renderIfAjax('test', compact('model', 'testTask'));

                if ($model->checkResult())
                    return $this->redirect(Url::to(['test', 'id' => $id]));
                else {
                    if (!$testTask->is_repetition) {
                        /** @var TestTaskQuestion $penaltyQuestion */
                        $penaltyQuestion = $testTask->getTestTaskQuestions()->andWhere(['<>', 'answer', ''])->orderBy(new Expression('rand()'))->one();
                        if ($penaltyQuestion) {
                            $penaltyQuestion->clearAnswer();
                        }
                    }
                    return $this->renderIfAjax('error-answer', compact('model'));
                }
            }

            $question = $model->getNextQuestion($testTask);


            if (!$question) {
                $transaction = Yii::$app->getDb()->beginTransaction();
                try {
                    $testTask->status = TestTask::STATUS_FINISHED;
                    $testTask->passed_at = new Expression('NOW()');
                    $testTask->save(false);
                    UserAchievement::addAchievement($testTask);
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }

                if ($testTask->is_repetition) {
                    if ($this->addFlashMessages) Yii::$app->session->setFlash('success', 'Тест успешно пройден');
                    return $this->redirect(Url::to(['view', 'id' => $testTask->id]));
                } else {
                    return $this->redirect(Url::to(['win', 'id' => $testTask->id]));
                }
            }

            return $this->renderIfAjax('test', compact('model', 'testTask'));
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            if ($this->addFlashMessages) {
                $message = 'Упс. Возникла ошибка.';
                Yii::$app->session->setFlash('error', $message);
            }
            return $this->goHome();
        }
    }

    public function actionTestLetter($id)
    {
        try {
            $letter = $this->findLetterModel($id);
            $all = Yii::$app->request->get('all', 0);
            if (!$all) {

                $cntWords = $letter->letterLevel->cnt_word_in_level;
            } else {
                $cntWords = 0;
            }
            $words = VocabularyWord::getNotLearnedWords($id, $cntWords);
            if (!$words) {
                Yii::$app->session->setFlash('success', "Все слова на букву {$letter->title} пройдены.");
                return $this->redirect(Url::home());
            }
            $testTask = TestTask::createTestTaskForCurrentUser($words, false, $id);
            return $this->redirect(Url::to(['test', 'id' => $testTask->id]));
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            if ($this->addFlashMessages) {
                $message = 'Упс. При создании теста произошла ошибка.';
                Yii::$app->session->setFlash('error', $message);
            }
            return $this->goHome();
        }
    }

    public function actionTrainingLetter($id)
    {
        $letter = $this->findLetterModel($id);
        $offset = Yii::$app->request->get('offset', 0);
        $word = VocabularyWord::find()->andWhere(['letter_id' => $id])->orderBy('id')->offset($offset)->one();
        if (!$word) {
            if ($this->addFlashMessages) Yii::$app->session->setFlash('success', "Все слова на букву {$letter->title} пройдены.");
            return $this->redirect(Url::home());
        }
        $wordsCount = VocabularyWord::find()->andWhere(['letter_id' => $id])->count();
        return $this->renderIfAjax('training-letter', compact('word', 'letter', 'offset', 'wordsCount'));
    }

    public function actionTrainingTest($id)
    {
        try {
            $testTask = $this->findModel($id);
            $offset = Yii::$app->request->get('offset', 0);
            $question = $testTask->getTestTaskQuestions()->orderBy('id')->offset($offset)->one();
            $questionCount = $testTask->getQuestionsCount();

            if (!$question) {
                if ($this->addFlashMessages) Yii::$app->session->setFlash('success', 'Все слова пройдены');
                return $this->redirect(Url::to(['view', 'id' => $id]));
            }
            return $this->renderIfAjax('training-test', compact('question', 'questionCount', 'offset'));
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            return $this->goBackAfterCreate();
        }
    }

    public function actionReTest($id)
    {
        $testTask = $this->findModel($id);
        if (!$testTask->is_repetition) {
            Yii::$app->session->setFlash('success', 'Данный тест нельзя проходить заново!');
            return $this->redirect(Url::to(['view', 'id' => $id]));
        }
        $testTask->reNewTest();
        return $this->redirect(Url::to(['test', 'id' => $id]));
    }

    public function actionLetterPopup($id)
    {
        $this->layout = 'main.php';
        $letter = Letter::find()->andWhere(['id' => $id])->one();
        if ($letter === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        $level = UserAchievement::getLevel($id);
        return $this->renderAjax('letter-popup', compact('letter', 'level'));
    }

    public function actionLetterFullPopup($id)
    {
        $this->layout = 'main.php';
        $letter = Letter::find()->andWhere(['id' => $id])->one();
        if ($letter === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        $level = UserAchievement::getLevel($id);
        return $this->renderAjax('letter-full-popup', compact('letter', 'level'));
    }
}
<?php

namespace app\controllers;

use app\models\form\ChangePasswordForm;
use app\models\form\RegistrationForm;
use app\models\search\UserSearch;
use Exception;
use kozlovsv\crud\controllers\CrudController;
use kozlovsv\crud\helpers\ModelPermission;
use Yii;
use yii\db\ActiveRecord;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CrudController
{
    public function init()
    {
        parent::init();
        $this->accessRules = [
            [
                'actions' => ['change-password'],
                'allow' => ModelPermission::canUpdate($this->getPermissionCategory()),
            ],
        ];
    }

    /**
     * Возвращает модель для поиска
     * @return ActiveRecord
     */
    public function getSearchModel()
    {
        return new UserSearch();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return 'app\models\User';
    }

    /**
     * @return ActiveRecord
     */
    public function createModel()
    {

        /** @var ActiveRecord $model */
        $model = new RegistrationForm();
        $this->model = $model;
        return $model;
    }

    public function actionChangePassword($id)
    {
        try {
            $user = $this->findModel($id);
            $model = new ChangePasswordForm($user);
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->resetPassword()) {
                if ($this->addFlashMessages) Yii::$app->session->setFlash('success', 'Пароль успешно изменен');
                return $this->goBackAfterUpdate();
            }
            return $this->renderIfAjax('change-password', compact('model'));
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            if ($this->addFlashMessages) {
                $message = 'При сохранении записи произошла ошибка. Обратитесь в службу поддержки.';
                Yii::$app->session->setFlash('error', $message);
            }
            return $this->goBackAfterUpdate();
        }
    }
}
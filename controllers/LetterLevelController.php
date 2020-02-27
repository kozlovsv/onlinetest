<?php

namespace app\controllers;

use app\models\LetterLevel;
use app\models\search\LetterLevelSearch;
use Exception;
use kozlovsv\crud\controllers\CrudController;
use kozlovsv\crud\helpers\ModelPermission;
use Yii;
use yii\db\ActiveRecord;

/**
 * LetterLevelController implements the CRUD actions for LetterLevel model.
 */
class LetterLevelController extends CrudController
{
    public function init()
    {
        parent::init();
        $permissionCategory = $this->getPermissionCategory();
        $this->accessRules = [
            [
                'actions' => ['fill'],
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
        return new LetterLevelSearch();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return 'app\models\LetterLevel';
    }

    public function actionFill()
    {
        try {
            LetterLevel::autoFill(10);
            Yii::$app->session->setFlash('success', 'Данные успешно заполнены');
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            $message = 'При заполнении уровней произошла ошибка. См. лог приложения.';
            Yii::$app->session->setFlash('error', $message);
        }
        return $this->goBackAfterCreate();
    }

}
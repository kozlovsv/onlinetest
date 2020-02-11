<?php

namespace app\controllers;

use app\models\search\UserSearch;
use kozlovsv\crud\controllers\CrudController;
use yii\db\ActiveRecord;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CrudController
{
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
}
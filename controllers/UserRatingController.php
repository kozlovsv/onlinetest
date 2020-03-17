<?php

namespace app\controllers;

use app\models\search\StudentTestTaskSearch;
use app\models\search\UserRatingSearch;
use kozlovsv\crud\controllers\CrudController;
use yii\db\ActiveRecord;

/**
  * @package app\controllers
 */
class UserRatingController extends CrudController
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
        return new UserRatingSearch();
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

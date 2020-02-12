<?php

namespace app\components;

use Yii;
use yii\base\Action;
use yii\filters\AccessControl;

/**
 * Компонента проверки доступа в систему
 */
class RequestAccess extends AccessControl
{
    /**
     * @var array
     */
    public $rules = [
        [
            'allow' => true,
            'actions' => [
                'login',
                'error',
                'registration',
                'request-password-reset',
                'reset-password',
            ],
        ],
        [
            'allow' => true,
            'roles' => ['@'],
        ],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->denyCallback = function () {
            Yii::$app->user->logout();
            return Yii::$app->response->redirect(Yii::$app->user->loginUrl);
        };

        parent::init();
    }

    /**
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
}
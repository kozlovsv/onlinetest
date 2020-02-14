<?php

namespace app\widgets;

use kozlovsv\crud\widgets\Nav;
use Yii;
use yii\bootstrap\Widget;

/**
 *
 * @property string $cacheKey
 * @property array $items
 */
class Menu extends Widget
{
    /**
     * Префикс кэш-ключа
     * @var string
     */
    protected $keyPrefix = 'menu-user';

    /**
     * Собрать меню
     * Пункты меню кэшируются для каждого юзера!
     * @return string
     * @return string
     */
    public function run()
    {
        $key = $this->getCacheKey();

        if (Yii::$app->cache->exists($key)) {
            $items = Yii::$app->cache->get($key);
        } else {
            $items = $this->getItems();
            Yii::$app->cache->add($key, $items);
        }
        return Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $items,
        ]);
    }

    /**
     * Получить кэш-ключ для меню
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->keyPrefix . Yii::$app->user->id;
    }

    /**
     * Получить все пункты меню
     * @return array
     */
    protected function getItems()
    {
        return [
            [
                'label' => 'Пройти тест',
                'url' => ['/test-task/create'],
                'visible' => Yii::$app->user->can('test_task.create'),
            ],
            [
                'label' => 'Мои тесты',
                'url' => ['/test-task/index'],
                'visible' => Yii::$app->user->can('test_task.view'),
            ],
            [
                'label' => 'Управление',
                'items' => [
                    [
                        'label' => 'Пользователи',
                        'url' => ['/user/index'],
                        'visible' => Yii::$app->user->can('user.view'),
                    ],
                    [
                        'label' => 'Словарные слова',
                        'url' => ['/vocabulary-word/index'],
                        'visible' => Yii::$app->user->can('vocabulary_word.view'),
                    ],
                    [
                        'label' => 'Роли и права',
                        'url' => ['/auth/default/index'],
                        'visible' => Yii::$app->user->can('auth.manage'),
                    ],
                    [
                        'label' => 'Логи приложения',
                        'url' => ['/log/index'],
                        'visible' => Yii::$app->user->can('log.view'),
                    ],
                ],
            ],
        ];
    }
}
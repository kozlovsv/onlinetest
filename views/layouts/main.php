<?php

/* @var $this View */

/* @var $content string */

use app\widgets\Menu;
use kozlovsv\crud\widgets\Dialog;
use kozlovsv\crud\widgets\Nav;
use yii\bootstrap\Html;
use yii\bootstrap\NavBar;
use yii\web\View;
use yii\widgets\Breadcrumbs;

?>
<?php $this->beginContent('@app/views/layouts/empty.php'); ?>
    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Html::icon('home'),
            'brandUrl' => Yii::$app->homeUrl,
            'innerContainerOptions' => [
                'class' => 'container-fluid'
            ],
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top nav-stacked navbar',
            ],
        ]);
        echo Menu::widget();
        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav navbar-right'],
            'items' => [
                [
                    'label' => Yii::$app->user->identity->name,
                    'items' => [
                        [
                            'label' => Html::icon('log-out'),
                            'url' => '/site/logout',
                            'linkOptions' => [
                                'data' => ['method' => 'post'],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        NavBar::end();
        ?>
        <div class="container-fluid">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'homeLink' => false,
            ]) ?>
            <?= $content ?>
        </div>
    </div>

<?= Dialog::widget() ?>
<?php
$this->endContent();

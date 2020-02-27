<?php

use app\models\Letter;
use kozlovsv\crud\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $letter Letter */
/* @var $level int */

$this->title = 'Буква ' . $letter->title;
?>
<img src="/images/corona.svg" alt="корона" class="img-responsive center-block" style>
<h1 style="font-size: 20px"><?= Html::encode($this->title); ?></h1>
<h2 style="font-size: 18px"><?= Html::encode("Уровень {$level}/{$level}"); ?></h2>
<h2 style="font-size: 18px"><?= Html::encode("Пройдено 100%"); ?></h2>

<div class="btn-group btn-group-justified" style="margin-bottom: 10px">
    <a class="btn btn-success" href="<?=Url::to(['/test-task/training-letter', 'id' => $letter->id, 'all' => 1])?>" role="button">Повторить слова</a>
</div>
<div class="btn-group btn-group-justified" style="margin-bottom: 10px">
    <a class="btn btn-info" href="<?=Url::to(['/test-task/create'])?>" role="button">Проверить знания</a>
</div>


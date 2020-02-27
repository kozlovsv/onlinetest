<?php

use app\models\Letter;
use kozlovsv\crud\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $letter Letter */
/* @var $level int */

$this->title = 'Буква ' . $letter->title;
?>

<h1 style="font-size: 20px"><?= Html::encode($this->title); ?></h1>
<h2 style="font-size: 18px"><?= Html::encode("Уровень {$level}/{$letter->letterLevel->cnt_level}"); ?></h2>

<div class="btn-group btn-group-justified" style="margin-bottom: 10px">
    <a class="btn btn-success" href="<?=Url::to(['/test-task/training-letter', 'id' => $letter->id])?>">Обучение</a>
</div>
<div class="btn-group btn-group-justified" style="margin-bottom: 10px">
    <a class="btn btn-info" href="<?=Url::to(['/test-task/test-letter', 'id' => $letter->id])?>" role="button">Тест</a>
</div>
<hr/>
<div class="btn-group btn-group-justified" style="margin-bottom: 10px">
    <a class="btn btn-primary" href="<?=Url::to(['/test-task/test-letter', 'id' => $letter->id, 'all' => 1])?>" role="button">Пройти букву целиком</a>
</div>


<?php


namespace app\helpers;


use app\models\TestTask;
use Exception;
use yii\bootstrap\Progress;

class TestTaskHtmlHelper
{
    /**
     * @param TestTask $model
     * @return string
     */
    public static function getGradeColor($model)
    {
        switch ($model->getGrade()) {
            case 5:
                return 'success';
                break;
            case 4:
                return 'info';
                break;
            case 3:
                return 'warning';
                break;
            default:
                return 'danger';
        }
    }

    /**
     * @param TestTask $model
     * @param $statusClass
     * @return array
     * @throws Exception
     */
    public static function getTestStatusAttribute($model, $statusClass) {
        if ($model->status == TestTask::STATUS_FINISHED || $model->getQuestionsPassedTestCount() == 0) {
            return  [
                'attribute' => 'statusLabel',
                'contentOptions' => ['class' => $statusClass],
                'captionOptions' => ['class' => $statusClass],
            ];
        }
        return [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => Progress::widget([
                'label' => $model->getQuestionsPassedTestCount() . ' / ' . $model->getQuestionsCount(),
                'percent' => $model->passedTestPercent,
                'barOptions' => ['class' => 'progress-bar-success'],
            ])
        ];
    }


}
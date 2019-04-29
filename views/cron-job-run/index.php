<?php

use console\components\Console;
use vasadibt\cron\models\CronJobRun;
use kartik\grid\GridView;
use common\helpers\Html;
use vasadibt\cron\Module;

/** @var yii\web\View $this */
/** @var vasadibt\cron\models\CronJobRunSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('vbt-cron', 'Cron Job Runs');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cron-job-run-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'bsVersion' => Module::getInstance()->bsVersion,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'job_id',
                    'vAlign' => 'middle',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'pid',
                    'vAlign' => 'middle',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'in_progress',
                    'vAlign' => 'middle',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'start',
                    'vAlign' => 'middle',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'finish',
                    'vAlign' => 'middle',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'runtime',
                    'vAlign' => 'middle',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'exit_code',
                    'vAlign' => 'middle',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'output',
                    'value' => function (CronJobRun $model) {
                        return Html::tag('pre', Console::ansiToHtml($model->output), ['style' => 'padding:15px']);
                    },
                    'format' => 'raw',
                    'hAlign' => 'left',
                    'vAlign' => 'middle',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'error_output',
                    'value' => function (CronJobRun $model) {
                        return Html::tag('pre', Console::ansiToHtml($model->error_output), ['style' => 'padding:15px']);
                    },
                    'format' => 'raw',
                    'hAlign' => 'left',
                    'vAlign' => 'middle',
                ],
            ],
            'toolbar' => [
                Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('vbt-cron', 'Reset'), [''], [
                    'class' => 'btn btn-default',
                    'title' => Yii::t('vbt-cron', 'Reset'),
                ]),
            ],
            'panel' => [
                'type' => 'green',
                'heading' => '<i class="glyphicon glyphicon-list"></i> ' . $this->title,
            ]
        ]) ?>
    </div>
</div>

<?php

use vasadibt\cron\models\CronJob;
use vasadibt\cron\Module;
use kartik\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var vasadibt\cron\models\CronJobSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('vbt-cron', 'Cron Jobs');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cron-job-index">
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
                    'attribute' => 'name',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'schedule',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'command',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'active',
                    'format' => 'bool',
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'last.start'
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'last.runtime'
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'last.exit_code'
                ],
                [
                    'class' => '\kartik\grid\DataColumn',
                    'value' => function (CronJob $job) {
                        if ($job->last_id === null) {
                            return null;
                        }
                        return $job->last->exit_code == 0 ? true : false;
                    },
                    'label' => Yii::t('vbt-cron', 'Success'),
                    'format' => 'bool',
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update} {delete} {run}',
                    'buttons' => [
                        'run' => function ($url, CronJob $model) {
                            return Html::a('<i class="glyphicon glyphicon-list"></i>', ['/cron/cron-job-run/index', 'CronJobRunSearch' => ['job_id' => $model->id]], [
                                'title' => Yii::t('vbt-cron', 'Logs'),
                                'data-pjax' => 0,
                            ]);
                        },
                    ]
                ],
            ],
            'toolbar' => [['content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('vbt-cron', 'Create Cron Job'), ['create'], [
                    'class' => 'btn btn-info',
                    'title' => Yii::t('vbt-cron', 'Create Cron Job'),
                    'data-pjax' => 0,
                ]) .
                Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('vbt-cron', 'Reset'), [''], [
                    'class' => 'btn btn-default',
                    'title' => Yii::t('vbt-cron', 'Reset'),
                ])
            ]],
            'panel' => [
                'type' => 'success',
                'heading' => '<i class="glyphicon glyphicon-list"></i> ' . $this->title,
            ]
        ]) ?>
    </div>
</div>

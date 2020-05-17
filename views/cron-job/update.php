<?php

use vasadibt\cron\Module;
use yii\helpers\Html;

/**@var $this yii\web\View */
/**@var $model vasadibt\cron\models\CronJob */

$this->title = Yii::t('vbt-cron', 'Update Cron Job: {nameAttribute}', [
    'nameAttribute' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('vbt-cron', 'Cron Jobs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('vbt-cron', 'Update');

?>
<div class="cron-job-update">
    <?php if (Module::getInstance()->isBs4()): ?>
        <div class="card border-info">
            <div class="card-header text-white bg-info">
                <h5 class="m-0"><?= Html::encode($this->title) ?></h5>
            </div>
            <div class="card-body">
                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5 class="panel-title"><?= Html::encode($this->title) ?></h5>
            </div>
            <div class="panel-body">
                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
        </div>
    <?php endif; ?>
</div>

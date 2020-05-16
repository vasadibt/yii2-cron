<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**@var $this yii\web\View */
/**@var $model fullmvc\cron\models\CronJob */
/**@var $form yii\widgets\ActiveForm */
?>

<div class="cron-job-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'schedule')->textInput(['maxlength' => true, 'placeholder' => '* * * * *']) ?>

    <?= $form->field($model, 'command')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'max_execution_time')->textInput() ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('vbt-cron', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

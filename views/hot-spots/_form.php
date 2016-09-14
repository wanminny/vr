<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\hotspots */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hotspots-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'scene_id')->textInput() ?>

    <?= $form->field($model, 'ath')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'atv')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'linkedscene')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rotate')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

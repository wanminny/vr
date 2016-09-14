<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\hotspotsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hotspots-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'scene_id') ?>

    <?= $form->field($model, 'ath') ?>

    <?= $form->field($model, 'atv') ?>

    <?= $form->field($model, 'linkedscene') ?>

    <?php // echo $form->field($model, 'hname') ?>

    <?php // echo $form->field($model, 'rotate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\hotspots */

$this->title = 'Create Hotspots';
$this->params['breadcrumbs'][] = ['label' => 'Hotspots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotspots-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

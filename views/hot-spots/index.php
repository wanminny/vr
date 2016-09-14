<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\hotspotsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hotspots';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotspots-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Hotspots', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'scene_id',
            'ath',
            'atv',
            'linkedscene',
            // 'hname',
            // 'rotate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

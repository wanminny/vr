<?php

namespace trylife\vue;

class VueAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/vue/';

    public $js = [
        YII_ENV_DEV ? 'dist/vue.js': 'dist/vue.min.js'
    ];
}

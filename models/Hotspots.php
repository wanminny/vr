<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%hotspots}}".
 *
 * @property integer $id
 * @property integer $scene_id
 * @property string $ath
 * @property string $atv
 * @property string $linkedscene
 * @property string $hname
 * @property string $rotate
 */
class Hotspots extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%hotspots}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'scene_id'], 'integer'],
            [['ath', 'atv', 'linkedscene', 'hname', 'rotate'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scene_id' => '所属场景ID',
            'ath' => 'Ath',
            'atv' => 'Atv',
            'linkedscene' => 'Linkedscene',
            'hname' => '热点名称',
            'rotate' => '旋转度',
        ];
    }
}

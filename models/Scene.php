<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%scene}}".
 *
 * @property integer $id
 * @property integer $pro_id
 * @property string $name
 * @property string $title
 * @property string $thumburl
 */
class Scene extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'pro_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['title', 'thumburl'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pro_id' => '所属项目ID',
            'name' => '场景名称',
            'title' => '场景标题',
            'thumburl' => '场景缩略图',
        ];
    }
}

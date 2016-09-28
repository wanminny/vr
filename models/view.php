<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%view}}".
 *
 * @property integer $id
 * @property integer $scene_id
 * @property string $hlookat
 * @property string $vlookat
 */
class view extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%view}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'scene_id'], 'integer'],
            [['hlookat', 'vlookat'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '试图ID',
            'scene_id' => '所属场景ID',
            'hlookat' => 'Hlookat',
            'vlookat' => 'Vlookat',
        ];
    }

    // 设置初始角度
    public static function angle($id,$arr)
    {
       $model_view =  static::findOne(['scene_id'=>$id]);
        //更新操作
       if($model_view)
       {
            if(is_array($arr) && count($arr))
            {
                foreach($arr as $key => $value)
                {
                    //目前只是更新这两个字段
                    if($key == "hlookat" || $key == "vlookat")
                    {
                        $model_view->$key = $value;
                    }
                }
                $model_view->save(false);
            }
       }
    }



}

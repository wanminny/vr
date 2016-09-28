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

    //获取某个场景下的热点信息
    public static function getHotInfo($scene_id)
    {
        $sql = "select * from leju_hotspots where scene_id = ".$scene_id;
        $model = static::findBySql($sql)->asArray()->all();

        return $model;
    }

    //删除热点
    public static function delHot($hots,$arr)
    {
        $ids = [];
        if(is_array($arr) && count($arr))
        {
            foreach($arr as $key =>$value)
            {
                $ids[$key] = $value['id'];
            }
        }
//        var_dump($ids);die;
        //参数热点input
        if(is_array($hots) && count($hots))
        {
            foreach($hots as $k1=>$v1)
            {
                if(!in_array($v1['id'],$ids))
                {
                    //del
                    static::findOne($v1['id'])->delete();
                }
            }
        }
    }

}

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


    //获取场景信息 组成所谓的XML_DATA （实际上为JSON）
    public static function getSeneInfo($proId,$json = 1)
    {

        $where = " and prj.productid=".$proId;
        $sql = "select scene.*,view.*,hotspots.* from leju_product as prj
                  join leju_scene as scene on prj.productid = scene.pro_id ".$where."
                  JOIN  leju_view as view on view.scene_id =  scene.id
                  left JOIN  leju_hotspots as hotspots on hotspots.scene_id = scene.id";

        $data =  Scene::findBySql($sql)->asArray()->all();

        if($json == 1)
        {
            return json_encode($data);
        }else{
            return $data;
        }
    }

    //获取场景的name id 信息 有可能多个；
    public static function getSceneName($proId)
    {
       $sql = "select name,id from leju_scene where pro_id =".$proId;
//        var_dump($sql);
       $data =  Scene::findBySql($sql)->asArray()->all();
//var_dump($data);
       return $data;
    }


    /**
     * 更新对应的HOTSPOTS表.
     *
     * @param integer $id scene_id
     * @param array
     * @retrun mixed
     */
    public static function editInfo($id,$arr)
    {
//        $model_scene = Scene::findOne($id);
        //热点
        $model_hot = Hotspots::findBySql("select * from leju_hotspots WHERE scene_id =".$id)->asArray()->all();
        if(empty($model_hot))
        {
            //新建
            $model_hot = new Hotspots();
            if(count($arr))
            {
                foreach($arr as $key => $value)
                {
                    foreach($value as $kk => $vv)
                    {
                        $model_hot->$kk = $vv;
                    }
                    $model_hot->scene_id = $id;
                    $model_hot->save(false);
                }
            }
        }
//        var_dump($model_hot,$arr);die;
        foreach($model_hot as $kk => $vv)
        {
            //没有ID的
            if(isset($vv['id']) && $vv['id'])
            {

                $hot_model = new Hotspots();
                $hot_model->findOne($vv['id']);
                foreach($arr as $k1 => $v1)
                {
                    //更新
                    if(isset($v1['id']) && $v1['id'])
                    {
                        foreach($v1 as $k2=>$v2)
                        {
                            if($k2 != 'id')
                            {
                                $hot_model->$k2 = $v2;
                            }
                        }
                        $hot_model->save();
                    }
                    //新建
                    else
                    {
                        $model_hot_new = new Hotspots();
                        foreach($v1 as $k2=>$v2)
                        {
                            $model_hot_new->$k2 = $v2;
                        }
                        $model_hot_new->$id;
                        $model_hot_new->save(false);
                    }
                }
            }
//            else{
//                //有ID的为新增的；
//                //新建
//                $model_hot = new Hotspots();
//                foreach($arr as $key => $value)
//                {
//                    foreach($value as $kk => $vv)
//                    {
//                        $model_hot->$kk = $vv;
//                    }
//                    $model_hot->scene_id = $id;
//                    $model_hot->save(false);
//                }
//            }
        }

    }

    //获取工程ID的对应的场景XML
    public static function getScenexml($proId)
    {
        $redis =  Yii::$app->redis;
        $xml =  $redis->get($proId);
        return $xml;
    }


    public static function getSceneById($pid)
    {
        $sql = "select * from leju_scene where pro_id =".$pid;

        return Scene::findBySql($sql)->asArray()->all();
    }

}

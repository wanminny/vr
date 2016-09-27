<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/9
 * Time: 上午10:56
 */

namespace app\modules\controllers;

use yii\web\Controller;
use app\models\Scene;
use app\models\Product;
use app\models\view;
use app\models\Hotspots;
use app\common\libs\image\Verify;


class VrController extends CommonController
{


    public $enableCsrfValidation = false;


    public function composeData($pid)
    {
        $scene_info = [];
        //获取工程名称
        $pro_model = new Product();

        $scene =  $pro_model->getScene($pid);

        //获取指定工程的场景和视图信息；
        $view_scene =  $pro_model->getViewById($pid);
//            var_dump($view_scene);die;
        if(is_array($scene) && count($scene))
        {
            foreach($scene as $key => $value)
            {
                //获取指定视图的热点信息
                if($view_scene)
                {
                    $scene_info[$value['name']]['view'] = $view_scene[$key];
                    //获取改工程下面的场景
                    $scene_id = $view_scene[$key]['scene_id'];
                    if($scene_id)
                    {
                        $scene_info[$value['name']]['hotspots'] = $pro_model->getHotspotsById($scene_id);
                    }
                }
            }
        }

        return $scene_info;
    }


    //获取预览页面
    public function actionIndex()
    {
        $pid = \Yii::$app->request->getQueryParam("productid","");

        if($pid)
        {
            $scene_info = $this->composeData($pid);
            return $this->render("index",["pid"=> $pid]);

        }else{
            echo "项目id不存在";
            exit;
        }
    }


    //获取编辑页面
    public function actionEdit()
    {
        $pid = \Yii::$app->request->getQueryParam("productid","");
        if($pid)
        {
            $scene_info = $this->composeData($pid);

            return $this->render("edit",["pid"=> $pid,"xml"=> json_encode($scene_info)]);

        }else {
            echo "项目id不存在";
            exit;
        }
    }


    //获取制定tour_XML模板
    public function actionXml()
    {
        $pid = \Yii::$app->request->getQueryParam("productid","");

//        $xml = $this->composeData($pid);
//        var_dump($xml);
//        die;
        if($pid)
        {
            $xml = $this->composeData($pid);
            $path = \Yii::$app->basePath."/web/vtour/tour.xml";
            include_once($path);

        }else {
            echo "项目id不存在";
            exit;
        }
    }



    //获取制定editor_XML模板
    public function actionEditxml()
    {
        $pid = \Yii::$app->request->getQueryParam("productid","");
        if($pid)
        {
            $xml = $this->composeData($pid);
            $path = \Yii::$app->basePath."/web/vtour/tour_editor.xml";
            include_once($path);

        }else {
            echo "项目id不存在";
            exit;
        }

    }


    private function getCode($pic) {
        $config = [
            'fontSize' => 12, // 验证码字体大小
//            'length' => 8, // 验证码位数
        ];
        $Verify = new Verify($config); //设置验证码字符为纯数字
        $Verify->word($pic); //验证码
    }

    //获取图片
    public function actionPic()
    {
        $pic = \Yii::$app->request->get("word","");
        if($pic)
        {
            $this->getCode($pic);
        }
        else{
            echo "图片标题为空";
            exit;
        }
    }

}
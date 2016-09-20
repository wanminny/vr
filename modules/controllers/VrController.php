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


class VrController extends CommonController
{


    public $enableCsrfValidation = false;


    //获取预览页面
    public function actionIndex()
    {
        $pid = \Yii::$app->request->getQueryParam("productid","");

        if($pid)
        {
            //获取工程名称
            $pro_model = new Product();
            $pro_info = $pro_model->getProInfoById($pid);
            //获取改工程下面的场景
            $scen_info = Scene::getSeneInfo($pid);
            return $this->render("index",["pid"=> $pid,"product"=> $pro_info,"scene" => $scen_info]);

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
                        $scene_info[$value['name']]['view'] = $view_scene[0];
                        //获取改工程下面的场景
                        $scene_id = $view_scene[0]['scene_id'];
                        if($scene_id)
                        {
                            $scene_info[$value['name']]['hotspots'] = $pro_model->getHotspotsById($scene_id);
                        }
                    }
                }
            }

//            var_dump($scene_info,json_encode($scene_info));die;

            return $this->render("edit",["pid"=> $pid,"xml"=> json_encode($scene_info)]);

        }else {
            echo "项目id不存在";
            exit;
        }
    }


    public function actionXml()
    {
        $pid = \Yii::$app->request->getQueryParam("productid","");
        if($pid)
        {
            $path = \Yii::$app->basePath."/web/vtour/"."tour.xml";
            include_once($path);

        }else {
            echo "项目id不存在";
            exit;
        }
    }

}
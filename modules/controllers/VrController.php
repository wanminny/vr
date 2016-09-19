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
            $pro_model->getProInfoById($pid);
            //获取改工程下面的场景
            $scen_info = Scene::getSeneInfo($pid);
            return $this->render("index",["pid"=> $pid,"product"=> $pro_model,"scene" => $scen_info]);

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
            //获取工程名称
            $pro_model = new Product();

            $pro_model->getProInfoById($pid);
            //获取改工程下面的场景
            $scen_info = Scene::getSeneInfo($pid);
            return $this->render("edit",["pid"=> $pid,"product"=> $pro_model,"scene" => $scen_info]);

        }else {
            echo "项目id不存在";
            exit;
        }
    }

}
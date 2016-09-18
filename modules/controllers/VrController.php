<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/9
 * Time: 上午10:56
 */

namespace app\modules\controllers;

use yii\web\Controller;


class VrController extends CommonController
{


    public $enableCsrfValidation = false;


    //获取预览页面
    public function actionIndex()
    {
        $pid = \Yii::$app->request->getQueryParam("productid","");

        if($pid)
        {
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
//        var_dump($pid);die;
        if($pid)
        {
            return $this->render("edit",["pid"=> $pid]);

        }else {
            echo "项目id不存在";
            exit;
        }
    }

}
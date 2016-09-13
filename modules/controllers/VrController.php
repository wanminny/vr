<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/9
 * Time: 上午10:56
 */

namespace app\modules\controllers;

use yii\web\Controller;

use app\common\libs\lss\Array2XML;
use app\common\libs\lss\XML2Array;


class VrController extends CommonController
{


    public $enableCsrfValidation = false;


    private $upload_path = "/www/leju/vr/"; //上传文件的存放路径


    //获取预览页面
    public function actionIndex()
    {
        $pid = \Yii::$app->request->getQueryParam("productid","");

        if($pid)
        {
            return $this->render("index",["pid"=> $pid]);

        }else{
            return $this->render("index");
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
            return $this->render("edit");
        }
    }

    function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
                $xml.="<".$key.">".$val."</".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    //test
    public function actionTest()
    {
        $demo = \Yii::$app->basePath."/web/36/vtour/tour.xml";
        $xml = file_get_contents($demo);

       $demo =  $this->xmlToArray($xml);
        var_dump($demo);
//        $arr = XML2Array::createArray($xml);
        $a = Array2XML::createXml("krpano",$demo);
//        var_dump($a);die;
        $c =$a->saveXML($a);
        var_dump($c);

    }


    public function actionUp1()
    {
        $path = $this->upload_path;

        if (!empty($_FILES)) {

            //得到上传的临时文件流
            $tempFile = $_FILES['Filedata']['tmp_name'];

            //允许的文件后缀
            $fileTypes = array('jpg','jpeg','gif','png');

            //得到文件原名
            $fileName = iconv("UTF-8","GB2312",$_FILES["Filedata"]["name"]);
            $fileParts = pathinfo($_FILES['Filedata']['name']);

            //接受动态传值
            $files=$_POST['typeCode'];

            //最后保存服务器地址
            if(!is_dir($path))
                mkdir($path);
            if (move_uploaded_file($tempFile, $path.$fileName)){
                echo $fileName."上传成功！";
            }else{
                echo $fileName."上传失败！";
            }
        }
        else{
            return $this->render("up");
        }
    }



    //上传图片
    public function actionUp()
    {
        if($_FILES)
        {
            $file = $_FILES['file'];//得到传输的数据
//            var_dump($file);
            $name = $file['name'];
            $type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
            $allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
            //判断文件类型是否被允许上传
            if(!in_array($type, $allow_type)){
                //如果不被允许，则直接停止程序运行
                return ;
            }
            //判断是否是通过HTTP POST上传的
            if(!is_uploaded_file($file['tmp_name'])){
                //如果不是通过HTTP POST上传的
                return ;
            }

            //开始移动文件到相应的文件夹
            if(move_uploaded_file($file['tmp_name'],$this->upload_path.$file['name'])){

//                echo ($this->upload_path.$file['name'])."<br>";
                echo "ok!";
                return $file['name'];
            }else{
                echo "Failed!";
            }
        }
        else{
            return $this->render("up");
        }
    }


    //sudo /Users/wanmin/Desktop/krpano-1.19-pr5/krpanoTools makepano
    //  -config=templates/vtour-multires.config /Users/wanmin/Desktop/logoo.jpg -panotype=cylinder -hfov=360
    public function actonGen()
    {

        $imgName = $_POST['imagename']?$_POST['imagename']:"";
        if(empty($imgName))
        {
            return 1;
        }

        $toolPath = "sudo /Users/wanmin/Desktop/krpano-1.19-pr5/krpanoTools makepano ";
        $config = " -config=templates/vtour-multires.config ";
//        $image = "/Users/wanmin/Desktop/logoo.jpg";

        $image = $this->upload_path.$imgName;

        $parameters = " -panotype=cylinder -hfov=360 ";

        $command = $toolPath.$config.$image.$parameters;
//		die;
        $returnValue = '';
        if(file_exists($image))
        {
            $output = [];

            if(file_exists("/Users/wanmin/Desktop/vtour"))
            {
                (exec("sudo  rm -rf vtour<<<EOF

EOF"));
            }
            (exec($command,$output,$returnValue));
        }

        if($returnValue === 0)
        {
            echo "ok!";
        }
        else{
            echo "vr failure!";
        }
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/8
 * Time: 下午12:00
 */

namespace app\controllers;

use yii\web\Controller;

class PanoController extends Controller
{

//    public $layout = '';
    public $redis;


    public function actionIndex()
    {
        return $this->render("index");
    }


    public function actionVtour()
    {
        return $this->render("vtour");

    }



    public function actionEdit()
    {
        return $this->render("edit");

    }


    public function actionTour()
    {
        return $this->render("tour");

    }


    public function actionRetrive()
    {
        //
        $pid = "";
        if(!empty($_POST['data']))
        {
            $xml =  $this->create_xml_array($_POST);
            var_dump($xml);
            file_put_contents("1.xml",$xml);

        }
    }

    //数组生成XML
    private function create_xml_array( &$arr )
    {
        $xml = '';

        if( is_object( $arr ) )
            $arr = get_object_vars( $arr );

        foreach( (array)$arr as $k => $v ) {
            if( is_object( $v ) )
                $v = get_object_vars( $v );
            //nodes must contain letters
            if( is_numeric( $k ) )
                $k = 'id-'.$k;
            if( is_array( $v ) )
                $xml .= "<$k>\n". $this->create_xml_array( $v ). "</$k>\n";
            else
                $xml .= "<$k>$v</$k>\n";
        }

        return $xml;
    }

    // 生产VR图
    public function actionGenerate()
    {

        $toolPath = "sudo /Users/wanmin/Desktop/krpano-1.19-pr5/krpanoTools makepano ";
        $config = " -config=templates/vtour-multires.config ";
        $image = "/Users/wanmin/Desktop/logoo.jpg";
        $parameters = " -panotype=cylinder -hfov=360 ";

        $command = $toolPath.$config.$image.$parameters;
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

    //redis instance;
    public function actionConn()
    {
        $this->redis = new \Redis();
        $this->redis->connect("127.0.0.1","6379");
        $this->redis->set("aa","bb");
        echo $this->redis->get("aa");
    }

    //保存XML
    public function saveXml()
    {
        $sceneID= isset($_GET['scene'])?$_GET['scene']:__FUNCTION__;

        $xml = [];
        $this->redis->set($sceneID,$xml);

    }


    //获取指定的scene的XML
    public function getXml()
    {
        $sceneID= isset($_GET['scene'])?$_GET['scene']:__FUNCTION__;
        return  $this->redis->get($sceneID);
    }


    public function post($url, array $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $tips = curl_exec($ch); //返回信息
        curl_close($ch);
        return $tips;
    }

}
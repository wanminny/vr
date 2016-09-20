<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/9
 * Time: 上午10:58
 */


use app\models\Scene;

?>

    <title>krpano - jiema1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <script src="http://cdn.bootcss.com/jquery/2.0.0/jquery.js"></script>
    <style>
        @-ms-viewport { width:device-width; }
        @media only screen and (min-device-width:800px) { html { overflow:hidden; } }
        html { height:100%; }
        body { height:100%; overflow:hidden; margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FFFFFF; background-color:#000000; }
    </style>


<script src="vtour/tour.js"></script>

<div id="pano" style="width:100%;height:100%;">
    <noscript><table style="width:100%;height:100%;"><tr style="vertical-align:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>

    <?php

    $data = Scene::getSceneById($pid);
    if(is_array($data)) {
        foreach ($data as $k => $v) {
            $name = $v['name'];

            if ($name) {
//                $tmpname = "scene_" . $name;
                $name = "<scene name=" . "\"" . $name . "\"";
                $filename = \Yii::$app->basePath . \Yii::$app->params['xml_path'];
                $file = file_get_contents($filename);
                if (strpos($file, $name) === false) {
                    $xml = Scene::getScenexml($pid) . "</krpano>";

                    //动态生成文件内容；
                    $fp = fopen($filename, 'r+');
                    $len =  strlen("</krpano>");
//                    $int = ($k==0)?(-($len+2)):(-$len);
                    $int = -$len;
                    fseek($fp, $int, SEEK_END); // int 为你想写的位置距离文件开头的位置
                    fwrite($fp, $xml);
                }
            }
        }
    }

    ?>


    <script>
        var base_path = "vtour/";
        var swf_path = base_path+"tour.swf";
        var xml_path = "<?php echo yii\helpers\Url::to(['vr/xml', 'productid' => $pid]); ?>";

        embedpano({swf:swf_path, xml:xml_path, target:"pano", html5:"auto", mobilescale:1.0, passQueryParameters:true});

    </script>
</div>


<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/8
 * Time: 下午2:08
 */

?>



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


<script src="tour.js"></script>

<div id="pano" style="width:100%;height:100%;">
    <noscript><table style="width:100%;height:100%;"><tr style="vertical-align:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
    <script>
        embedpano({swf:"tour.swf", xml:"tour.xml", target:"pano", html5:"auto", mobilescale:1.0, passQueryParameters:true});
    </script>
</div>

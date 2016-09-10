<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/9
 * Time: 上午10:52
 */

?>

    <title>krpano - MAKE VTOUR - Virtual Tour Editor</title>
    <meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <style>
        @-ms-viewport { width: device-width; }
        @media only screen and (min-device-width: 800px) { html { overflow:hidden; } }
        html { height:100%; }
        body { height:100%; overflow: hidden; margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FFFFFF; background-color:#000000; }
        a{ color:#AAAAAA; text-decoration:underline; }
        a:hover{ color:#FFFFFF; text-decoration:underline; }
    </style>


<script src="vtour/tour.js"></script>

<div id="title" style="width:100%; height:20px; padding:5px; font-size:16px;"><b>krpano - MAKE VTOUR - Virtual Tour Editor</b> <span style="font-size:10px; font-style:italic; color:#777777;">// 1. set the startup views / 2. add hotspots / 3. edit the titles / 4. save and overwrite the original tour.xml</span></div>
<div id="pano" style="width:100%; height:95%;">
    <noscript><table style="width:100%;height:100%;"><tr style="vertical-align:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
    <script>
        var vars = {};
        vars["plugin[vtoureditor].url"] = "vtour/plugins/vtoureditor.swf";
        vars["plugin[vtoureditor].keep"] = true;

        embedpano({swf:"vtour/tour.swf", xml:"vtour/tour.xml", target:"pano", flash:"only", passQueryParameters:false, vars:vars});

        function resize()
        {
            var th = document.getElementById("title").clientHeight;
            var ph = (typeof(window.innerHeight) == 'number') ? window.innerHeight : ((document.documentElement && document.documentElement.clientHeight) ? document.documentElement.clientHeight : ((document.body && document.body.clientHeight) ? document.body.clientHeight : 500));

            document.getElementById("pano").style.height = (ph-th)+"px"
        }

        window.onresize = resize;

        resize();
    </script>
</div>


<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/9
 * Time: 上午10:58
 */
use app\models\Scene;

?>

    <title>优视全景 - 场景设置</title>
    <meta name="viewport"
          content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <meta http-equiv="x-ua-compatible" content="IE=edge"/>
    <style>
        @-ms-viewport {
            width: device-width;
        }

        @media only screen and (min-device-width: 800px) {
            html {
                overflow: hidden;
            }
        }

        html {
            height: 100%;
        }

        body {
            height: 100%;
            overflow: hidden;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #FFFFFF;
            background-color: #000000;
        }

        a {
            color: #AAAAAA;
            text-decoration: underline;
        }

        a:hover {
            color: #FFFFFF;
            text-decoration: underline;
        }
    </style>



<script src="http://cdn.bootcss.com/jquery/2.0.0/jquery.js"></script>
<script type="text/javascript" src="vtour/tour.js"></script>

<div id="title" style="width:100%; height:20px; padding:5px; font-size:16px;"><b>优视全景 - 设置场景</b></div>
<div id="pano" style="width:100%; height:95%;">
    <noscript>
        <table style="width:100%;height:100%;">
            <tr style="vertical-align:middle;">
                <td>
                    <div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div>
                </td>
            </tr>
        </table>
    </noscript>

    <?php

        $filename = \Yii::$app->basePath.\Yii::$app->params['edit_xml_path'];
        $file = file_get_contents($filename);
        if(strpos($file,"<scene name") === false)
        {
            $xml = Scene::getScenexml($pid)."</krpano>";
            //动态生成文件内容；
//            $filename = \Yii::$app->basePath.\Yii::$app->params['edit_xml_path'];
//        $filename = \Yii::$app->params['xml_path'];
            $fp = fopen($filename, 'r+');
            // $int = -strlen("</krpano>");
            $int = -(strlen("</krpano>")+3);
            fseek($fp, $int,SEEK_END); // int 为你想写的位置距离文件开头的位置
            fwrite($fp, $xml);
        }


    ?>

    <script type="text/javascript">

        var base_path = "vtour/";
        var swf_path = base_path+"tour.swf";
        var xml_path = base_path+"tour_editor.xml";

        embedpano({
            swf: swf_path,
            xml: xml_path,
            target: "pano",
            html5: "prefer",
            mobilescale: 1.0,
            passQueryParameters: false
        });
    </script>
</div>

<script type="text/javascript">
    //声明相关变量
    var xml_data = new Object();
    xml_data = {
        "s_826823": {
            "view": {"hlookat": "-133.36177199318357", "vlookat": "0.1360865824582751"},
            "hotspots": [{
                "ath": "-43.71962670315895",
                "atv": "8.601800484623901",
                "linkedscene": "s_816391",
                "hname": "hotspot_1",
                "rotate": "360"
            }, {
                "ath": "-121.73997680853083",
                "atv": "8.67509938542318",
                "linkedscene": "s_826823",
                "hname": "hotspot_12"
            }, {
                "ath": "-78.41522414859054",
                "atv": "10.490137848372068",
                "linkedscene": "s_254773",
                "hname": "hotspot_122"
            }]
        },
        "s_816391": {
            "view": {"hlookat": "226.47251277639035", "vlookat": "20.838315064524608"},
            "hotspots": [{
                "ath": "-137.07809699885559",
                "atv": "25.700593088512584",
                "linkedscene": "s_254773",
                "hname": "hotspot_14"
            }]
        },
        "s_254773": {
            "view": {"hlookat": "-48.04810923388179", "vlookat": "1.7079378106166534"},
            "hotspots": [{
                "ath": "-65.92861818118888",
                "atv": "29.180624824642884",
                "linkedscene": "s_816391",
                "hname": "hotspot_16"
            }]
        }
    };
    var pid = 469;
</script>
<script src= <?= $pid;?>+"/vtour/js/jquery.rotate.js"></script>
<style>
    .radar-container {
        position: absolute;
        width: 264px;
        height: 340px;
        right: 30px;
        top: 80px;
        background-color: rgba(80, 80, 80, 0.5);
        border: 1px solid #000;
        display: none;

    }

    .sandtable-img-margin {
        position: relative;
        height: 264px;
        width: 264px;
        margin-top: 10px;
        margin-bottom: 10px;
        background-color: rgba(80, 80, 80, 0.5);
    }

    .sandtable-window-img {
        display: block;
        width: 100%;
        height: 100%;
    }

    .radar-control {
        position: absolute;
        width: 60px;
        height: 60px;
    }

    .radar-control-img {
        position: absolute;
        top: 20px;
        left: 20px;
        cursor: pointer;
    }

    .radar-circle {
        width: 60px;
        height: 60px;
        position: absolute;
        background: url('images/radar-back.png') no-repeat;
    }

    .radar-point {
        position: absolute;
        left: 20px;
        top: -5px;
        width: 20px;
        height: 20px;
        background: url('images/radar-out.png') no-repeat;
        cursor: move;
    }

    .radar-center-point {
        position: absolute;
        left: 18px;
        top: 18px;
        width: 24px;
        height: 24px;
        cursor: move;
        background: url('images/radar-center.png') no-repeat;

    }

    .radar-title {
        font-size: 16px;
        height: 20px;
        line-height: 20px;
    }

    .radar-btn-group {
        height: 30px;
    }

    .radar-add-btn {
        float: left;
        margin-left: 10px;
    }

    .radar-del-btn {
        float: right;
        margin-right: 10px;
    }

    .radar-btn-group button {
        background-color: #36b127;
        border: 0;
        height: 24px;
        margin-top: 5px;
        width: 100px;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }

    .radar-scenes-container {
        width: 550px;
        height: 250px;
        position: absolute;
        top: 30%;
        left: 30%;
        border: 1px solid #ddd;
        background-color: rgba(85, 85, 85, 0.7);
        overflow-y: hidden;
        overflow-x: auto;
        white-space: nowrap;
        display: none;
    }

    .radar-scenes-one {
        display: inline-table;
        vertical-align: top;
        width: 200px;
        height: 220px;
        margin-right: 20px;
        margin-top: 10px;
        background-color: #696969;
        border-radius: 10px;
    }

    .scenes-img {
        padding: 10px;
        cursor: pointer;
    }

    .scenes-img img {
        width: 174px;
        height: 174px;
    }

    .scenes-title {
        text-align: center;
    }
</style>

<!-- 点击沙盘编辑中的添加标记按钮 出现选择房间列表-->
<div class="radar-scenes-container">
    <div class="radar-scenes-one">
        <div class="scenes-img" data-scene-name="s_826823">
            <img src="http://cdn.useevr.cn/5/201693dQFhct/thumb.jpg">
        </div>
        <div class="scenes-title">
            <span>店面</span>
        </div>
    </div>
    <div class="radar-scenes-one">
        <div class="scenes-img" data-scene-name="s_816391">
            <img src="http://cdn.useevr.cn/5/201693sCE5me/thumb.jpg">
        </div>
        <div class="scenes-title">
            <span>前台</span>
        </div>
    </div>
    <div class="radar-scenes-one">
        <div class="scenes-img" data-scene-name="s_254773">
            <img src="http://cdn.useevr.cn/5/201693Y7G8Wm/thumb.jpg">
        </div>
        <div class="scenes-title">
            <span>展厅一</span>
        </div>
    </div>

</div>
<div class="radar-container">
    <div class="radar-title">
        沙盘编辑
    </div>
    <div class="sandtable-img-margin">
        <img class="sandtable-window-img" src="">

    </div>
    <div class="radar-btn-group">
        <div class="radar-add-btn">
            <button>添加标记</button>
        </div>
        <div class="radar-del-btn">
            <button data-original-title="">删除标记</button>
        </div>
    </div>
</div>
<script>

    function calcAngle(ev) {
        var containerOffset = $('.sandtable-window-img').offset();
        var offsetX = containerOffset['left'];
        var offsetY = containerOffset['top'];
        //var offsetX = 0;
        //var offsetY = 0;
        var mouseX = ev.pageX - offsetX;//计算出鼠标相对于画布顶点的位置,无pageX时用clientY + body.scrollTop - body.clientTop代替,可视区域y+body滚动条所走的距离-body的border-top,不用offsetX等属性的原因在于，鼠标会移出画布
        var mouseY = ev.pageY - offsetY;
        var cx = $(dragElm).parents(".radar-control").position().left + 30;
        var cy = $(dragElm).parents(".radar-control").position().top + 30;
        var ox = mouseX - cx;//cx,cy为圆心
        var oy = mouseY - cy;
        var to = Math.abs(ox / oy);
        var angle = Math.atan(to) / ( 2 * Math.PI ) * 360;//鼠标相对于旋转中心的角度
        if (ox < 0 && oy < 0)//相对在左上角，第四象限，js中坐标系是从左上角开始的，这里的象限是正常坐标系
        {
            angle = -angle;
        } else if (ox < 0 && oy > 0)//左下角,3象限
        {
            angle = -( 180 - angle )
        } else if (ox > 0 && oy < 0)//右上角，1象限
        {
            angle = angle;
        } else if (ox > 0 && oy > 0)//右下角，2象限
        {
            angle = 180 - angle;
        }
        //console.log(angle);
        $(dragElm).parent(".radar-circle").css('rotate', angle);
    }

    var dragElm;
    var radar_img = "http://cdn.useevr.cn/5/sandtable/201696bSd66T.png";
    function dragStart(event) {
        dragElm = event.target;
        $(document).mousemove(calcAngle);
        $(document).mouseup(dragEnd);
    }
    function showRadar() {
        if (radar_img == "") {
            alert("请先在编辑项目中上传户型图");
        } else
            $(".radar-container").slideDown();
    }
    function dragEnd(e) {
        //记录雷达初始角度偏移量
        var rotate = $(dragElm).parent(".radar-circle").css('rotate');
        $(dragElm).parent(".radar-circle").data('rotate', rotate);
        // var krpano = document.getElementById('panoSettingObject');
        // var hlookat = krpano.get('view.hlookat');
        var hlookat = 10;
        $(dragElm).parent(".radar-circle").data('hlookat', hlookat);
        var sceneTitle = $(dragElm).parent(".radar-circle").prev().data('original-title');
        // putSandTableData(krpano.get('xml.scene'),sceneTitle,rotate,hlookat);
        $(document).unbind('mousemove', calcAngle);
        $(document).unbind('mouseup', dragEnd);
    }
    function moveRadar(ev) {
        var containerOffset = $('.sandtable-window-img').offset();
        var offsetX = containerOffset['left'];
        var offsetY = containerOffset['top'];
        var mouseX = ev.pageX - offsetX;//计算出鼠标相对于画布顶点的位置,无pageX时用clientY + body.scrollTop - body.clientTop代替,可视区域y+body滚动条所走的距离-body的border-top,不用offsetX等属性的原因在于，鼠标会移出画布
        var mouseY = ev.pageY - offsetY;
        var containerWidth = $('.sandtable-window-img').outerWidth();
        var containerHeight = $('.sandtable-window-img').outerHeight();
        if (mouseX > 10 && mouseX < (containerWidth - 10)) {
            $(dragElm).parents('.radar-control').css('left', (mouseX - 30) + 'px');
        } else if (mouseX < 10) {
            $(dragElm).parents('.radar-control').css('left', '-20px');
        } else if (mouseX > (containerWidth - 10)) {
            $(dragElm).parents('.radar-control').css('left', (containerWidth - 10 - 30) + 'px');
        }
        if (mouseY > 10 && mouseY < (containerHeight - 10)) {
            $(dragElm).parents('.radar-control').css('top', (mouseY - 30) + 'px');
        } else if (mouseY < 10) {
            $(dragElm).parents('.radar-control').css('top', '-20px');
        } else if (mouseY > (containerHeight - 10)) {
            $(dragElm).parents('.radar-control').css('top', (containerHeight - 10 - 30) + 'px');
        }
    }
    $(document).on('mousedown', '.radar-center-point', function (event) {
        dragElm = event.target;
        $(document).mousemove(moveRadar);
        $(document).mouseup(moveRadarEnd);
    });


    function moveRadarEnd(e) {
        // var leftPx = $(dragElm).parents('.radar-control').position().left;
        // var topPx = $(dragElm).parents('.radar-control').position().top;
        // var krpano = document.getElementById('panoSettingObject');
        // putSandTableData(krpano.get('xml.scene'),null,null,null,topPx,leftPx);
        $(document).unbind('mousemove', moveRadar);
        $(document).unbind('mouseup', moveRadarEnd);
    }

    function putSandTableData(sceneName, sceneTitle, rotate, hlookat, topPx, leftPx) {
        var sandData = $('#sandImg .sand-img-clicked').data('sand-table-data');
        if (!sandData) {
            sandData = {};
            sandData[sceneName] = {};
            sandData[sceneName].rotate = 0;
            sandData[sceneName].hlookat = 0;
            sandData[sceneName].top = '40%';
            sandData[sceneName].left = '40%';
            sandData[sceneName].krpTop = '48%';
            sandData[sceneName].krpLeft = '48%';
        } else {
            if (!sandData[sceneName]) {
                sandData[sceneName] = {};
                sandData[sceneName].rotate = 0;
                sandData[sceneName].hlookat = 0;
                sandData[sceneName].top = '40%';
                sandData[sceneName].left = '40%';
                sandData[sceneName].krpTop = '48%';
                sandData[sceneName].krpLeft = '48%';
            }
        }

        if (rotate)sandData[sceneName].rotate = rotate;
        if (hlookat)sandData[sceneName].hlookat = hlookat;
        if (sceneTitle)sandData[sceneName].sceneTitle = sceneTitle;
        if (topPx) {
            var imgHeight = $('.sandtable-img-margin').height();
            var top = percentNum(topPx, imgHeight);
            var krpTop = percentNum(topPx + 20, imgHeight);
            sandData[sceneName].top = top;
            sandData[sceneName].krpTop = krpTop;
        }
        if (leftPx) {
            var imgWidth = $('.sandtable-img-margin').width();
            var left = percentNum(leftPx, imgWidth);
            var krpLeft = percentNum(leftPx + 20, imgWidth);
            sandData[sceneName].left = left;
            sandData[sceneName].krpLeft = krpLeft;
        }
        $('#sandImg .sand-img-clicked').data('sand-table-data', sandData);
    }
    function getRadars() {
        var radars = {};
        $(".radar-control").each(function () {
            var scene = $(this).children(".radar-control-img").data("original-title");
            radars[scene] = {};
            radars[scene].rotate = $(this).children(".radar-circle").css('rotate');
            radars[scene].x = $(this).position().top;
            radars[scene].y = $(this).position().left;
        });
        return radars;
    }
    function appendRadarCircle(sceneName) {
        var htmlStr = '<div class="radar-control" style="top:102px;left:102px">' +
            '<img class="radar-control-img" src="images/radar-out.png" onclick="triggerCircle(this)"  data-original-title="' + sceneName + '">' +
            '<div class="radar-circle"  style="transform: rotate(0deg);">' +
            '<div class="radar-point" onmousedown="dragStart(event)" ></div>' +
            '<div class="radar-center-point"></div>' +
            '</div>' +
            '</div>';
        hiddenCircle();
        $('.radar-container .sandtable-img-margin').append(htmlStr);

    }


    function hiddenCircle() {
        $(".radar-control").each(function () {
            var $circle = $(this).children(".radar-circle");
            if ($circle.css("display") == 'block') {
                $circle.css("display", "none");
            }
        });
    }

    function triggerCircle(obj) {

        var sceneName = $(obj).data('original-title');
        if (sceneName != krpano.get('xml.scene')) {
            krpano.call('loadscene(' + sceneName + ', null, MERGE);');
        }
        hiddenCircle();
        $(obj).siblings(".radar-circle").css("display", "block");
        $(".radar-del-btn").data("original-title", sceneName);
    }

    function delRadar(sceneName) {
        $(".radar-control-img[data-original-title='" + sceneName + "']").parent().remove();
    }
    $(".radar-del-btn").click(function () {
        delRadar($(this).data("original-title"));
        $(this).data("original-title", "");
    })
    $(".radar-add-btn").click(function () {
        $(".radar-scenes-container").show();
    });
    $(".scenes-img").click(function () {
        var sceneName = $(this).data("scene-name");
        delRadar(sceneName);
        appendRadarCircle(sceneName);
        $(".radar-del-btn").data("original-title", sceneName);
        $(".radar-scenes-container").hide();
        if (sceneName != krpano.get('xml.scene')) {
            krpano.call('loadscene(' + sceneName + ', null, MERGE);');
        }
    });

</script>
<script type="text/javascript" src= <?= $pid;?>+"/vtour/js/addtoureditor.js"></script>


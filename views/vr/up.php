<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/9/9
 * Time: 下午2:02
 */
?>

<script src="jquery-1.8.0.min.js" type="text/javascript"></script>



<hr/>
<h1>VR图片上传与项目生成</h1><br/><br/>

<!--<form id= "uploadForm"  method= "post" enctype ="multipart/form-data">-->
    <p >指定文件名： <input type ="text" name="filename" /></p>
    <p >上传文件： <input type ="file" name="file" /></p>
    <input type ="submit" value="上传" id= "uploadForm"/>
<!--</form>-->




<hr/>

<form action="/index.php?r=vr/gen" name="form" method="post">

    <input  type="hidden"  name="imagename">
    <input type="submit" name="submit" value="生产项目" />
</form>

<hr/>

<script>
    $(function({

        $("#uploadForm").click(function({

            $.ajax({
                url : "index.php?r=vr/up",
                type : "POST",
                data : $( '#postForm').serialize(),
                success : function(data) {

                     alert(data);
//                    $( '#serverResponse').html(data);
                },
                error : function(data) {

//                    $( '#serverResponse').html(data.status + " : " + data.statusText + " : " + data.responseText);
                }
            });

        }))
    }));

</script>



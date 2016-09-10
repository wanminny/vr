// JavaScript Document
//取得krpano对象
var krpano = document.getElementById("krpanoSWFObject");

//动态保存设置的xml
//@param scene:场景名, type:view|hotspots
function save_xml_data(scene,type,ath,atv,linkedscene,hname){
    if(xml_data[scene]==null){
        xml_data[scene]= new Object();
    }
    //设置初始角度
    if(type=="view"){
        xml_data[scene]['view'] = {hlookat:ath,vlookat:atv};
    }
    //添加热点
    if(type=="hotspots"){
        if(xml_data[scene]['hotspots']==null){
            xml_data[scene]['hotspots'] = new Array();
        }
        var n_hotspot = {ath:ath,atv:atv,linkedscene:linkedscene,hname:hname};
        xml_data[scene]['hotspots'].push(n_hotspot);
    }
    //旋转热点
    if(type=='rotate'){
        if(xml_data[scene]['hotspots']!=null){
            for(var i=0;i<xml_data[scene]['hotspots'].length;i++){
                if(xml_data[scene]['hotspots'][i].hname==ath){
                    xml_data[scene]['hotspots'][i].rotate = atv;
                }
            }
        }
    }
    console.log('js接收到数据：'+JSON.stringify(xml_data));
}
//删除热点
function del_xml_h(scene,hname){
    if(xml_data[scene]['hotspots']!=null){
        for(var i=0;i<xml_data[scene]['hotspots'].length;i++){
            if(xml_data[scene]['hotspots'][i].hname==hname){
                xml_data[scene]['hotspots'].splice(i,1);
            }
        }
    }
    console.log('js接收到数据：'+JSON.stringify(xml_data));
}
//移动热点
function move_xml_h(scene,hname,ath,atv){
    if(xml_data[scene]['hotspots']!=null){
        for(var i=0;i<xml_data[scene]['hotspots'].length;i++){
            if(xml_data[scene]['hotspots'][i].hname==hname){
                xml_data[scene]['hotspots'][i].ath = ath;
                xml_data[scene]['hotspots'][i].atv = atv;
            }
        }
    }
    console.log('js接收到数据：'+JSON.stringify(xml_data));
}

//初始化当前scene保存的xml
function init_xml_data(scene){
    //隐藏layer相关按钮
    krpano.call("set(layer[skin_visit_btn_container].visible,false);set(layer[skin_zan_btn_container].visible,false);set(layer[skin_co_btn_container].visible,false);");
    //取得当前scene
    var scene_name = krpano.get("scene[get(xml.scene)].name");
    console.log('js取得当前scene：'+scene_name);
    console.log('js取得当前scene的xml:'+JSON.stringify(xml_data[scene_name]));
    var xml_data_n = xml_data[scene_name];
    if(typeof(xml_data_n)!='undefined' && xml_data_n!=null && xml_data_n!=''){
        //还原保存的初始角度
        if(typeof(xml_data_n.view)!='undefined' && xml_data_n.view!=null && xml_data_n.view!=''){
            var xml_data_v = xml_data_n.view;
            console.log("js定位当前scene视角："+xml_data_v.hlookat+'/'+xml_data_v.vlookat);
            krpano.call("set(view[get(xml.view)].hlookat,"+xml_data_v.hlookat+");set(view[get(xml.view)].vlookat,"+xml_data_v.vlookat+");");
        }
        //还原保存的热点
        var xml_data_h = xml_data_n.hotspots;
        if(typeof(xml_data_n.hotspots)!='undefined' && xml_data_n.hotspots!=null && xml_data_n.hotspots!=''){
            for(var i=0;i<xml_data_h.length;i++){
                var hname = xml_data_h[i].hname;
                var ath = xml_data_h[i].ath;
                var atv = xml_data_h[i].atv;
                var rotate = xml_data_h[i].rotate!=null ? xml_data_h[i].rotate : 0;
                var hs_n = hname.split("_");
                krpano.call("set(hs_n,"+(hs_n[1]+1)+")");
                var linkedscene = xml_data_h[i].linkedscene;
                console.log("js生成当前scene热点："+hname);
                krpano.call("addhotspot("+hname+");set(hotspot["+hname+"].url,%SWFPATH%/skin/vtourskin_hotspot.png);set(hotspot["+hname+"].crop,'0|0|128|128');set(hotspot["+hname+"].ath,"+ath+");set(hotspot["+hname+"].atv,"+atv+");set(hotspot["+hname+"].rotate,"+rotate+");set(hotspot["+hname+"].scale,0.5);set(hotspot["+hname+"].zoom,false);set(hotspot["+hname+"].distorted,true);set(hotspot["+hname+"].depth,10000);set(hotspot["+hname+"].scale,6);set(hotspot["+hname+"].vr_timeout, 2000);set(hotspot["+hname+"].onclick,gotoscene("+linkedscene+"));");
            }
        }
    }
}
//向服务器端post设置的xml
function post_xml_data(){
    var radars = getRadars();
    $.post("/member/project",{act:'edit',pid:pid,data:xml_data,radars:radars},function(data){
        var data = eval('('+data+')');
        if(data.status==1){
            alert('保存成功');
        }
        if(data.status==0){
            alert(data.msg);
        }
    });
}
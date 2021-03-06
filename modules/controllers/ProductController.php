<?php

namespace app\modules\controllers;
use app\models\Category;
use app\models\Product;
use Think\Exception;
use yii\web\Controller;
use Yii;
use yii\data\Pagination;
//use crazyfd\qiniu\Qiniu;
use app\common\libs\Qiniu;
use app\modules\controllers\CommonController;
use app\models\Scene;
use app\models\view;
use app\models\Hotspots;
use app\common\libs\lss\Array2XML;
use app\common\libs\lss\XML2Array;


class ProductController extends CommonController
{

    public $upload_path = '';

    public $xml_path = '';


    public function actionList()
    {
        $model = Product::find();
        $count = $model->count();
//        var_dump(Yii::$app->params);die;
        $pageSize = Yii::$app->params['pageSize']['product'];
//        var_dump($pageSize);die;
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $products = $model->offset($pager->offset)->limit($pager->limit)->all();
        $this->layout = "layout1";
        return $this->render("products", ['pager' => $pager, 'products' => $products]);
    }

    public function actionAdd()
    {
        $this->layout = "layout1";
        $model = new Product;
        $cate = new Category;
        $list = $cate->getOptions();
        unset($list[0]);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $pics = $this->upload();
            if (!$pics) {
                $model->addError('cover', '封面不能为空');
            } else {
                $post['Product']['cover'] = $pics['cover'];
                $post['Product']['pics'] = $pics['pics'];
                $post['Product']['pics_name'] = $pics['pics_name'];
                $post['Product']['cover_name'] = $pics['cover_name'];
            }

            $proId = 0;
            if ($pics && $model->add($post,$proId)) {
//                var_dump($proId);die;
                $reslt = $this->gen($proId);
                echo $reslt;
                Yii::$app->session->setFlash('info', '添加成功');
            } else {
                Yii::$app->session->setFlash('info', '添加失败');
            }

        }

        return $this->render("add", ['opts' => $list, 'model' => $model]);
    }

    private function upload()
    {
        //户型图
        if ($_FILES['Product']['error']['cover'] > 0) {
            //户型图可以为空
            return false;
        }
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $key = uniqid();
//        $cover = '';
//        var_dump($_FILES);die;

        $qiniu->uploadFile($_FILES['Product']['name']['cover'],$_FILES['Product']['tmp_name']['cover'], $key);
//        var_dump($_FILES['Product']['name']['cover']);
        $cover = $qiniu->getLink($key);
        $pics = [];
        $pics_name = '';
        foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
            if ($_FILES['Product']['error']['pics'][$k] > 0) {
                continue;
            }
            $key = uniqid();
//            var_dump($_FILES['Product']['name']['pics'][$k]);
            $qiniu->uploadFile($_FILES['Product']['name']['pics'][$k],$file, $key);

            $pics[$key] = $qiniu->getLink($key);
            $pics_name .= $_FILES['Product']['name']['pics'][$k];
        }
        $cover_name = $_FILES['Product']['name']['cover'];
//        $pics_name =
        return ['cover' => $cover, 'pics' => json_encode($pics),'cover_name' => $cover_name,"pics_name" => $pics_name];
    }


    //sudo /Users/wanmin/Desktop/krpano-1.19-pr5/krpanoTools makepano
    //  -config=templates/vtour-multires.config /Users/wanmin/Desktop/logoo.jpg -panotype=cylinder -hfov=360
    //./krpanotools makepano -config=templates/vtour-multires.config /tmp/baidu.jpeg -panotype=cylinder -hfov=360
    public function gen($proId)
    {

        $this->upload_path = \Yii::$app->params['dir_path'];

        ///获取图片
        $pro = new Product();
        $info = $pro->getProInfoById($proId);

        if($info)
        {
            $cover_name = $info['cover_name'];
            $pics_name  = $info['pics_name'];

            //用处？ 先不处理
            $cover_path = $this->upload_path.$cover_name;

            $pic_arr = explode(",",$pics_name);
            $pics_path = '';
            if(is_array($pic_arr) && count($pic_arr))
            {
                $pics_path .= $this->upload_path.$pics_name." ";
            }
            else{
                $pics_path = $this->upload_path.$pics_name;
            }
        }
//        var_dump($this->upload_path,$pics_path);
//        $toolPath = "sudo /Users/wanmin/Desktop/krpano-1.19-pr5/krpanoTools makepano ";
        $toolPath = "/tmp/vr/krpano-1.19-pr5/krpano_Tools makepano ";
        $config = " -config=templates/vtour-multires.config ";
//        $image = "/Users/wanmin/Desktop/logoo.jpg";
        $imgName = $pics_path;

//        $image = $this->upload_path.$imgName;
        $image = $pics_path;
        $parameters = " -panotype=cylinder -hfov=360 ";

        $command = $toolPath.$config.$image.$parameters;
var_dump($image,file_exists($image),$command);
        $returnValue = '';
        if(file_exists($image))
        {
            $output = [];

//            if(file_exists("/Users/wanmin/Desktop/vtour"))
//            {
//                (exec("sudo  rm -rf vtour<<<EOF
//
//EOF"));
//            }

            (exec($command,$output,$returnValue));
        }

        if($returnValue === 0)
        {

            //todb and genxml
            $this->actionConv($proId);
            return  "ok!";
        }
        else{
            return "vr failure!";
        }
    }


    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    //根据生成的tour.xml -> tour_edit.xml
    public function actionConv()
    {
        $proId = "";
        //入库
        $xml_path = \Yii::$app->basePath.\Yii::$app->params['xml_path'];
        $xml = file_get_contents($xml_path);
        $demo =  $this->xmlToArray($xml);

        var_dump($demo);die;
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try
        {
            if(is_array($demo))
            {
                //scene库
                $scene = $demo['scene'];
                //views库
                $pro_view = $scene['view'];
                if($pro_view && is_array($pro_view))
                {
                    $pro_view_attr = $pro_view['@attributes'];
                    if(is_array($pro_view_attr) && count($pro_view_attr))
                    {
                        $view_hlookat = $pro_view_attr['hlookat'];
                        $view_vlookat =  $pro_view_attr['vlookat'];
                        $scene_id = "";

                        $view = new View();
                        $view->hlookat = $view_hlookat;
                        $view->vlookat = $view_vlookat;
                        $view->scene_id = $scene_id;
                    }
                }
                //只有一个场景
                if(isset($scene['@attributes']))
                {

                    $scene_name = $scene['@attributes']['name'];
                    $scene_title = $scene['@attributes']['title'];
                    $scene_thumburl = $scene['@attributes']['thumburl'];
                    $scene_pro_id = $proId;

                    $scene = new Scene();
                    $scene->name = $scene_name;
                    $scene->title = $scene_title;
                    $scene->thumburl = $scene_thumburl;
                    $scene->pro_id = $scene_pro_id;
                    $scene->save();

                    //hotspots库
                    $pro_view_hotspot = $scene['hotspot'];
                    if(is_array($pro_view_hotspot))
                    {
                        //只有一个热点
                        if(isset($pro_view_hotspot['@attributes']))
                        {
                            $pro_view_hotspot_ath = $pro_view_hotspot['@attributes']['ath'];
                            $pro_view_hotspot_atv = $pro_view_hotspot['@attributes']['atv'];
                            $pro_view_hotspot_linkedscene = $pro_view_hotspot['@attributes']['linkedscene'];
                            $pro_view_hotspot_hname = $pro_view_hotspot['@attributes']['hname'];
                            $pro_view_hotspot_rotate = $pro_view_hotspot['@attributes']['rotate'];
                            $pro_view_hotspot_scene_id = "";

                            $hotspots = new Hotspots();
                            $hotspots->ath = $pro_view_hotspot_ath;
                            $hotspots->atv = $pro_view_hotspot_atv;
                            $hotspots->hname = $pro_view_hotspot_hname;
                            $hotspots->rotate = $pro_view_hotspot_rotate;
                            $hotspots->linkedscene = $pro_view_hotspot_linkedscene;
                            $hotspots->scene_id = $pro_view_hotspot_scene_id;
                            $hotspots->save();


                        }
                        //若干个热点
                        else{
                            foreach($pro_view_hotspot as $k => $v)
                            {
                                $pro_view_hotspot_ath = $pro_view_hotspot['@attributes']['ath'];
                                $pro_view_hotspot_atv = $pro_view_hotspot['@attributes']['atv'];
                                $pro_view_hotspot_linkedscene = $pro_view_hotspot['@attributes']['linkedscene'];
                                $pro_view_hotspot_hname = $pro_view_hotspot['@attributes']['hname'];
                                $pro_view_hotspot_rotate = $pro_view_hotspot['@attributes']['rotate'];
                                $pro_view_hotspot_scene_id = "";

                                $hotspots = new Hotspots();
                                $hotspots->ath = $pro_view_hotspot_ath;
                                $hotspots->atv = $pro_view_hotspot_atv;
                                $hotspots->hname = $pro_view_hotspot_hname;
                                $hotspots->rotate = $pro_view_hotspot_rotate;
                                $hotspots->linkedscene = $pro_view_hotspot_linkedscene;
                                $hotspots->scene_id = $pro_view_hotspot_scene_id;
                                $hotspots->save();
                            }
                        }
                    }
                }
                else{
                    //多个场景
                    foreach($scene as $key => $value)
                    {
                        $scene_name = $scene['@attributes']['name'];
                        $scene_title = $scene['@attributes']['title'];
                        $scene_thumburl = $scene['@attributes']['thumburl'];
                        $scene_pro_id = $proId;

                        $scene = new Scene();
                        $scene->name = $scene_name;
                        $scene->title = $scene_title;
                        $scene->thumburl = $scene_thumburl;
                        $scene->pro_id = $scene_pro_id;
                        $scene->save();


                        //hotspots库
                        $pro_view_hotspot = $scene['hotspot'];
                        if(is_array($pro_view_hotspot))
                        {
                            //只有一个热点
                            if(isset($pro_view_hotspot['@attributes']))
                            {
                                $pro_view_hotspot_ath = $pro_view_hotspot['@attributes']['ath'];
                                $pro_view_hotspot_atv = $pro_view_hotspot['@attributes']['atv'];
                                $pro_view_hotspot_linkedscene = $pro_view_hotspot['@attributes']['linkedscene'];
                                $pro_view_hotspot_hname = $pro_view_hotspot['@attributes']['hname'];
                                $pro_view_hotspot_rotate = $pro_view_hotspot['@attributes']['rotate'];
                                $pro_view_hotspot_scene_id = "";

                                $hotspots = new Hotspots();
                                $hotspots->ath = $pro_view_hotspot_ath;
                                $hotspots->atv = $pro_view_hotspot_atv;
                                $hotspots->hname = $pro_view_hotspot_hname;
                                $hotspots->rotate = $pro_view_hotspot_rotate;
                                $hotspots->linkedscene = $pro_view_hotspot_linkedscene;
                                $hotspots->scene_id = $pro_view_hotspot_scene_id;

                                $hotspots->save();

                            }
                            //若干个热点
                            else{
                                foreach($pro_view_hotspot as $k => $v)
                                {
                                    $pro_view_hotspot_ath = $pro_view_hotspot['@attributes']['ath'];
                                    $pro_view_hotspot_atv = $pro_view_hotspot['@attributes']['atv'];
                                    $pro_view_hotspot_linkedscene = $pro_view_hotspot['@attributes']['linkedscene'];
                                    $pro_view_hotspot_hname = $pro_view_hotspot['@attributes']['hname'];
                                    $pro_view_hotspot_rotate = $pro_view_hotspot['@attributes']['rotate'];
                                    $pro_view_hotspot_scene_id = "";

                                    $hotspots = new Hotspots();
                                    $hotspots->ath = $pro_view_hotspot_ath;
                                    $hotspots->atv = $pro_view_hotspot_atv;
                                    $hotspots->hname = $pro_view_hotspot_hname;
                                    $hotspots->rotate = $pro_view_hotspot_rotate;
                                    $hotspots->linkedscene = $pro_view_hotspot_linkedscene;
                                    $hotspots->scene_id = $pro_view_hotspot_scene_id;

                                    $hotspots->save();
                                }
                            }
                        }
                    }
                }

                $transaction->commit();
            }
        }catch (Exception $e) {
            $transaction->rollBack();
        }

        $scene = $demo['scene'];
        //视图节点；
        $xml_new = Array2XML::createXml("scene",$scene);
        $c =$xml_new->saveXML($xml_new);

        $xml_head = '<?xml version="1.0" encoding="UTF-8"?>';
        $append_xml = substr($c,strlen($xml_head));
        var_dump($append_xml);
        //同时生成edit.xml;


    }

    /// 保存设置
    public function actionSave()
    {
        $data = Yii::$app->request->post();
        //更新数据库


    }

    //获取XML
    public function actionGetxml()
    {

    }

    public function actionMod()
    {
        $this->layout = "layout1";
        $cate = new Category;
        $list = $cate->getOptions();
        unset($list[0]);

        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :id', [':id' => $productid])->one();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
            $post['Product']['cover'] = $model->cover;

//            if($model->cover)
            {
                if ($_FILES['Product']['error']['cover'] == 0) {
                    $key = uniqid();
                    $qiniu->uploadFile($_FILES['Product']['name']['cover'] ,$_FILES['Product']['tmp_name']['cover'], $key);
                    $post['Product']['cover'] = $qiniu->getLink($key);
                    $qiniu->delete(basename($model->cover));

                }
            }
            $pics = [];
            foreach($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
                if ($_FILES['Product']['error']['pics'][$k] > 0) {
                    continue;

                }
                $key = uniqid();
                $qiniu->uploadfile($_FILES['Product']['name']['pics'][$k],$file, $key);
                $pics[$key] = $qiniu->getlink($key);

            }
            $post['Product']['pics'] = json_encode(array_merge((array)json_decode($model->pics, true), $pics));
            //一个场景图片图片都没有是不可以的！
//            if(count($pics) < 1)
//            {
//                Yii::$app->session->setFlash('info', '至少要有一个场景图片！');
//            }
//            else
            {
                if ($model->load($post) && $model->save()) {
                    Yii::$app->session->setFlash('info', '修改成功');

                }
            }


        }
        return $this->render('add', ['model' => $model, 'opts' => $list]);

    }

    public function actionRemovepic()
    {
        $key = Yii::$app->request->get("key");
        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);
        $pics = json_decode($model->pics, true);
        unset($pics[$key]);
        Product::updateAll(['pics' => json_encode($pics)], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/mod', 'productid' => $productid]);
    }

    public function actionDel()
    {
        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $key = basename($model->cover);
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);
        $pics = json_decode($model->pics, true);
        foreach($pics as $key=>$file) {
            $qiniu->delete($key);
        }
        Product::deleteAll('productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }

}
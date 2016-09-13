<?php

namespace app\modules\controllers;
use app\models\Category;
use app\models\Product;
use yii\web\Controller;
use Yii;
use yii\data\Pagination;
//use crazyfd\qiniu\Qiniu;
use app\common\libs\Qiniu;
use app\modules\controllers\CommonController;

class ProductController extends CommonController
{

    public $upload_path = '';


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
        $toolPath = "/tmp/vr/krpano-1.19-pr5/krpanoTools makepano ";
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
            return  "ok!";
        }
        else{
            return "vr failure!";
        }
    }



    public function actionMod()
    {
        $this->layout = "layout1";
        $cate = new Category;
        $list = $cate->getOptions();
        unset($list[0]);

        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :id', [':id' => $productid])->one();


//        var_dump($_POST,$_FILES);DIE;

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
<?php

namespace app\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{


    const AK = 'Ygc_usWA7Yp2AZfo5LlSkrUC5sGVvpDhaEyo65iw';
    const SK = 'id2AWZIch6Yz4JC0Xmb7mp9ugTTLxRTK_-vsiW0_';
    const DOMAIN = 'http://ocll9mrvs.bkt.clouddn.com';
    const BUCKET = 'leju';

    public $cate;

    public function rules()
    {
        return [
            ['title', 'required', 'message' => '标题不能为空'],
//            ['descr', 'required', 'message' => '描述不能为空'],
            ['cateid', 'required', 'message' => '分类不能为空'],
            [['pics'],'required','message' => '场景图片不能为空'],
            [['pics_name'],'safe'],
            [['cover_name'],'safe'],
            //户型图不是必须
            [['cover'], 'required','message' => '户型图不能为空'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cateid' => '分类名称',
            'title'  => '项目名称',
            'pics_name' => 'pics_name',
            'cover_name' => 'cover_name',
//            'descr'  => '项目描述',
            'cover'  => '户型图',
            'pics'   => '场景图片',
        ];
    }

    public static function tableName()
    {
        return "{{%product}}";
    }

    public function add($data)
    {
//        if($data['Product']['pics'] != "[]")
//        {

            if ($this->load($data) && $this->save()){
//                var_dump($data);
                $id =  \Yii::$app->db->getLastInsertId();
                if($id)
                {
                    $dir = \Yii::$app->params['dir_path'].$id;
                    var_dump($dir);
                    var_dump(mkdir($id,755));

                }
                return true;
            }
//        }
        return false;
    }



}

<?php

namespace app\common\libs\partner;

//require dirname(__FILE__) . '/sinaweibo/SinaweiboCall.php';

use app\common\libs\partner\sinaweibo\SinaweiboCall;

use app\common\libs\partner\qqconect\QqconnectCall;
use yii\helpers\VarDumper;

isset($_SESSION) || session_start();
defined('DS') || define('DS', '/');

/**
 * 抽象类: 第三方接口都会继承该类
 * 
 * @name Lib_Api_Partner
 * @package lib/api
 * @copyright yoho.inc
 * @version 4.0 (2013-12-19 13:41:10)
 * @author fei.hong <fei.hong@yoho.cn>
 */
abstract class Factory
{
    /**
     * 接口名称
     * 
     * @var string
     */
    protected $apiName = '';
    /**
     * 接口配置
     * 
     * @var array
     */
    protected $apiConfig = array();
    /**
     * 接口对象
     * 
     * @var array
     */
    protected static $apiObjs = array();
    
    /**
     * 单例模式: 实例化需要调用的接口对象
     * 
     * @param string $apiName  接口名称
     * @return object
     */
    public static function create($apiName)
    {
        $apiName = strtolower($apiName);
        
        if (!isset(self::$apiObjs[$apiName]))
        {

            require (dirname(__FILE__) . DS . $apiName . DS . $apiName.'Call.php');
//            var_dump(dirname(__FILE__) . DS . $apiName . DS . $apiName.'Call.php');
            $apiNameCase = ucfirst($apiName);
            $apiClass = "{$apiNameCase}Call";
            $apiClass = "app\\common\\libs\\partner\\sinaweibo\\".$apiClass;

//            var_dump($apiClass);die;
            self::$apiObjs[$apiName] = new $apiClass();
            self::$apiObjs[$apiName]->apiName = $apiName;
            self::$apiObjs[$apiName]->configure();
            self::$apiObjs[$apiName]->init();
        }
        
        return self::$apiObjs[$apiName];
    }
    
    /**
     * 应用的配置
     * 
     * @return void
     */
    protected function configure()
    {
        $this->apiConfig = require(dirname(__FILE__) . DS . $this->apiName . DS . 'Config.inc.php');
    }
    
    /**初始化*/
    abstract protected function init();
    
    /**获取接口*/
    abstract public function getAuthorizeUrl();
    abstract public function getAccessToken();
    abstract public function getUserInfo($token);
    abstract public function getFriends($token, $params);
    
}
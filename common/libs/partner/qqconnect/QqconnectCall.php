<?php


namespace app\common\libs\partner\qqconect;


use app\common\libs\partner\Factory;

define('QC_CLASS_PATH', dirname (__FILE__) . '/class/');
require QC_CLASS_PATH . 'QC.class.php';

/**
 * 腾讯QQ互联的调用接口
 * 
 * @name Lib_Partner_Qqconnect_Call
 * @package lib/partner/qqconnect
 * @copyright yoho.inc
 * @version 4.0 (2013-12-19 11:42:11)
 * @author fei.hong <fei.hong@yoho.cn>
 */
class QqconnectCall extends Factory
{
    /*QQ互联对象*/
    protected $qc;
    
    /**
     * 初始化
     */
    protected function init() 
    {
        $this->qc = new QC();
    }
    
    /**
     * 获取授权URL
     * 
     * @return string
     */
    public function getAuthorizeUrl()
    {
        return $this->qc->qq_login();
    }
    
    /**
     * 获取授权的TOKEN
     * 
     * @return array
     */
    public function getAccessToken()
    {
        $token = array();
        
        try 
        {
        	$token = $this->qc->qq_callback();
        	$token['openid'] = $this->qc->get_openid();
        }
        catch (Exception $e)
        {
            // do nothing
        }
    
        return $token;
    }
    
    /**
     * 获取当前用户的基本资料
     *
     * @param array $token  授权成功的TOKEN, 默认为NULL
     * @return array
     */
    public function getUserInfo($token)
    {
        $userInfo = array();
        
        if (is_array($token) && isset($token['openid']))
        {
            $this->qc = new QC($token['access_token'], $token['openid']);
            
            $userInfo = $this->qc->get_user_info();
            
            if (isset($userInfo['ret']) && $userInfo['ret'] != 0)
            {
                $userInfo = array();
            }
        }
    
        return $userInfo;
    }
    
    /**
     * 获取当前用户的偶像(关注)列表
     *
     * @see http://wiki.connect.qq.com/get_idollist
     * @param string $access_token  访问令牌
     * @param string $openid  腾讯唯一的对应QQ号
     * @param array $params  参数列表
     *    format 是 返回数据的格式（json或xml）
     *    reqnum 是 请求个数(1-30)
     *    startindex 是 起始位置（第一页：填0，继续向下翻页：填上次请求返回的nextstartpos）
     *    mode 是 获取模式，默认为0
     *       mode=0，新粉丝在前，只能拉取1000个
     *       mode=1，最多可拉取一万粉丝，暂不支持排序
     *    install 否  过滤安装应用好友（可选）
     *       0-不考虑该参数，1-获取已安装应用好友，2-获取未安装应用好友
     *    sex 否 按性别过滤标识，1-男，2-女，0-不进行性别过滤，默认为0，支持排序
     * @return array
     */
    public function getFriends($token, $params)
    {
        $friends = array();
    
        if (is_array($token) && isset($token['openid']))
        {
            $this->qc = new QC($token['access_token'], $token['openid']);
            
            $friends = $this->qc->get_idollist($params);

            if (isset($friends['ret']) && $friends['ret'] != 0)
            {
                $friends = array();
            }
        }
    
        return $friends;
    }
    
    /**
     * 同步分享
     *
     * @param array $token  授权成功的TOKEN
     * @param string $content  要更新的微博信息。信息内容不超过140个汉字, 为空返回400错误。
     * @param string $image  要发布的图片路径, 支持url。[只支持png/jpg/gif三种格式, 增加格式请修改get_image_mime方法]
     * @param string $link  URL地址, 通过该地址链接回来
     * @return boolean  false:失败, true:成功
     */
    public function syncShare($token, $content, $image, $link)
    {
        $result = false;
    
        if (is_array($token) && isset($token['openid']))
        {
            $this->qc = new QC($token['access_token'], $token['openid']);
            
            $param = array('title' => '来自YOHO.CN的分享', 'url' => $link, 'summary' => $content, 
                           'images' => $image, 'site' => 'yoho.cn', 'fromurl' => SITE_MAIN,);
            
            $response = $this->qc->add_share($param);

            if (isset($response['ret']) && $response['ret'] == 0)
            {
                $result = true;
            }
        }
    
        return $result;
    }
}
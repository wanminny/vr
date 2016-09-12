<?php


namespace app\common\libs\partner\sinaweibo;

require dirname(__FILE__) . '/class/Saev2.class.php';


use app\common\libs\partner\Factory;


class SinaweiboCall extends Factory
{
    /*OAuth2.0对象*/
    protected $oauth;
    
    /*客户端操作对象*/
    protected $client;
    
    /**
     * 初始化
     */
    protected function init() 
    {
        $this->oauth = new \SaeTOAuthV2($this->apiConfig['appId'], $this->apiConfig['appKey']);
    }
    
    /**
     * 获取授权URL
     */
    public function getAuthorizeUrl()
    {
        return $this->oauth->getAuthorizeURL($this->apiConfig['appCallbackUrl']);
    }
    
    /**
     * 获取授权的TOKEN
     */
    public function getAccessToken()
    {
        $token = array();
        
        if (isset($_REQUEST['code'])) 
        {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = $this->apiConfig['appCallbackUrl'];
            
            try 
            {
                $token = $this->oauth->getAccessToken('code', $keys);
            } 
            catch (Exception $e) 
            {
                // do nothing
            }
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
        
        if (is_array($token) && isset($token['uid']))
        {
            $this->client = new \SaeTClientV2($this->apiConfig['appId'], $this->apiConfig['appKey'], $token['access_token']);
            
            $userInfo = $this->client->show_user_by_id($token['uid']);
            
            if (isset($userInfo['error_code']) && $userInfo['error_code'] > 0)
            {
                $userInfo = array();
            }
        }
        
        return $userInfo;
    }
    
    /**
     * 获取当前用户的关注列表
     *
     * @see http://open.weibo.com/wiki/2/friendships/friends
     *
     * @param string $token  已授权过的访问TOKEN
     * @param array $params  参数列表，如下:
     * @param integer $uid  (要获取的用户的ID)
     * @param integer count	(单页返回的记录条数，默认为50，最大不超过200。)
     * @param integer cursor (返回结果的游标，下一页用返回值里的next_cursor，上一页用previous_cursor，默认为0。
     * @return array
     */
    public function getFriends($token, $params)
    {
        $result = array();
        
        if (is_array($token) && isset($token['access_token']))
        {
            $this->client = new \SaeTClientV2($this->apiConfig['appId'], $this->apiConfig['appKey'], $token['access_token']);

            $result = $this->client->friends_by_id($params['uid'], $params['cursor'], $params['count']);

            if (isset($result['error_code']) && $result['error_code'] > 0)
            {
                $result = array();
            }
        }
        
        return $result;
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
        
        if (is_array($token) && isset($token['access_token']))
        {
            $content .= $link;
            
            $this->client = new \SaeTClientV2($this->apiConfig['appId'], $this->apiConfig['appKey'], $token['access_token']);
            
            $response = $this->client->upload($content, $image);
            
            if (!isset($response['error_code']) || $response['error_code'] == 0)
            {
                $result = true;
            }
        }
        
        return $result;
    }
    
}
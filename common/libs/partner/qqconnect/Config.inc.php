<?php
defined('SITE_MAIN') || define('SITE_MAIN', $_SERVER['HTTP_HOST']);

return array(
	'appid' => '100229394',
	'appkey' => 'c0af9c29e0900813028c2ccb42021792',
	'callback' => SITE_MAIN . '/oauth/qqcallback',
	'scope' => 'get_user_info,add_share,upload_pic,get_idollist,get_fanslist',
	'errorReport' => false,
);
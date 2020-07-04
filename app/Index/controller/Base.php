<?php


namespace app\Index\controller;


use app\BaseController;
use think\App;

class Base extends BaseController
{

    public function initialize()
    {
        if (!$this->isMobile()){
            header('Location:http://www.baidu.com');
            exit();
        }
        if ($this->isWeiXin()){
            header('Location:http://www.baidu.com');
            exit();
        }
        $array = array(
            'www.theuccu.site','ewrejmr.theuccu.site','emr.theuccu.site',
            'www.theuccu001.site','ewrejmr.theuccu001.site','emr.theuccu001.site',
            'www.theuccu002.site','ewrejmr.theuccu002.site','emr.theuccu002.site',
            'www.theuccu003.site','ewrejmr.theuccu003.site','emr.theuccu003.site');
        $domain= $array[mt_rand(0,11)];
        $host = explode('.',$_SERVER['HTTP_HOST']);
        if ($host[0]!='has'){
            header('Location:http://'."has.".$domain);exit();
        }
    }

    public function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;

        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
                return true;
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }

    public function isWeiXin(){
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (strpos(strtolower($ua), 'micromessenger')){
            return true;
        }
        return false;
    }

}
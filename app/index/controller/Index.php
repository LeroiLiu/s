<?php


namespace app\index\controller;

use app\BaseController;
use think\App;
use think\facade\Db;
use think\facade\View;

class Index extends BaseController
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
            'ewrejmr.theuccu001.site','emr.theuccu001.site',
            'ewrejmr.theuccu002.site','emr.theuccu002.site',
            'ewrejmr.theuccu003.site','emr.theuccu003.site');
        $domain= $array[mt_rand(0,5)];
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

    public function index(){
        //标题
        View::assign('title','小月牙儿');
        //tabs
        $tabs = Db::table('tabs')->field('tab_id,tab_name')->cache(true,3600)->select()->toArray();
        array_unshift($tabs,array('tab_id'=>0,'tab_name'=>'火爆'));
        View::assign('tabs',$tabs);
        //轮播图
        //轮播图
        $sliders = Db::table('videos')->field('title,img')->cache(true,3600)->select()->toArray();
        shuffle($sliders);
        View::assign('sliders',$sliders);
        //图片通知
        $notice = array('img'=>"https://yye-diaosi.oss-accelerate.aliyuncs.com/app/newui/images/gg01.jpg");
        View::assign('notice',$notice);

        //内容列表
        $keyword = input('keyword','火爆');
        View::assign('keyword',$keyword);
        $videos = Db::table('videos')->where('title','like',"%$keyword%")->field('title,img')->cache(true,3600)->select()->toArray();
        $keys = $videos?array_rand($videos,10):array();
        $tmp_videos = array();
        foreach ($keys as $key){
            array_push($tmp_videos,$videos[$key]);
        }
        View::assign('videos',$tmp_videos);

        return view();

    }

    public function pay(){

        View::assign('orderNum',date("YmdHis").mt_rand(10000,99999));
        View::assign('pay',number_format(29-mt_rand(0,99)/100,2));

        $urls = (array)Db::table('pay_qr')->cache(true,3600)->column('url');
        View::assign('pay_qr',$urls?$urls[array_rand($urls)]:array());

        return view();
    }

    public function test(){

        $videos = Db::table('videos')->select()->toArray();
        foreach ($videos as $video){
            $img = str_replace('http://player.yueyaer.club/','http://yueyar.oss-cn-shanghai.aliyuncs.com/',$video['img']);
            try {
                file_get_contents($img);
            }catch (\Exception $exception){}
        }



    }



}
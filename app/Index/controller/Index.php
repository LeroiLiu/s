<?php


namespace app\Index\controller;

use think\App;
use think\facade\Db;
use think\facade\View;

class Index extends Base
{


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
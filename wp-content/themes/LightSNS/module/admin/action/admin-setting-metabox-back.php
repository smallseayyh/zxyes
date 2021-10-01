<?php 
//后台设置
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}

if(isset($_GET['download'])){
$type=$_GET['type'];
$post_id=$_GET['post_id'];
}else{
$type=$_POST['type'];
$post_id=$_POST['post_id'];	
}

if($type=='page/case.php'){//案例
$option='case_option';
$option_name='案例';
}else if($type=='page/layout-bbs.php'){//论坛大厅
$option='bbs_show_page_data';	
$option_name='论坛大厅';
}else if($type=='page/leaderboard.php'){//排行榜
$option='page_leaderboard_data';
$option_name='排行榜';	
}else if($type=='page/single.php'){//文章/帖子专题
$option='single_show_page_data';	
$option_name='文章/帖子专题';
}else if($type=='page/video.php'){//视频专题
$option='video_show_page_data';	
$option_name='视频专题';
}else if($type=='page/topic.php'){//话题中心
$option='topic_show_page_data';	
$option_name='话题中心';
}else if($type=='page/shop.php'){//商城
$option='shop_page_data';	
$option_name='商城';
}else if($type=='page/live.php'){//直播
$option='video_live_page_data';	
$option_name='直播';
}else if($type=='page/select.php'){//筛选
$option='page_select_option';	
$option_name='筛选';
}else if($type=='page/luck-draw.php'){//抽奖
$option='page_luckdraw_option';	
$option_name='抽奖';
}else if($type=='jinsom-workbook'){//文档文库
$option='jinsom_custom_bbs_show_data';	
$option_name='文档文库';
}



if(isset($_GET['download'])){//下载设置数据
$backup=get_post_meta($post_id,$option,true);
$backup=json_encode($backup,true);
$backup=trim($backup,'[');
$backup=trim($backup,']');
header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Content-Disposition:attachment;filename=".$option_name."页面_设置数据_".date("Y-m-d_H-i-s").".json");
header("Expires: 0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Pragma:public");
echo $backup;

}else{

if(isset($_POST['backup'])){
$backup=$_POST['backup'];
if($backup=='delete'){
$status=delete_post_meta($post_id,$option);
if($status){
$data_arr['code']=1;
$data_arr['msg']='设置数据已恢复默认！';
}else{
$data_arr['code']=0;
$data_arr['msg']='清空失败！';	
}
}else{
$backup_arr=json_decode(stripslashes(trim($backup)),true);
if(is_array($backup_arr)){
$status=update_post_meta($post_id,$option,$backup_arr);
if($status){
$data_arr['code']=1;
$data_arr['msg']='导入成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='导入失败！';	
}
}else{
$data_arr['code']=0;
$data_arr['msg']='导入的数据格式有误！';
}
}

}

header('content-type:application/json');
echo json_encode($data_arr);

}


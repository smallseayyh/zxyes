<?php
//批量导入
require( '../../../../../../wp-load.php' );

//判断是否登录
if (!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否管理员
if (!current_user_can('level_10')){ 
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(isset($_POST['ids'])){
$ids=trim($_POST['ids']);
$content=trim($_POST['content']);
$id_arr=explode(",",$ids);
$content_arr=explode("\n",$content);
$id_count=count($id_arr);
$id_count=$id_count-1;


foreach ($content_arr as $data) {

$rand=rand(0,$id_count);
$author_id=$id_arr[$rand];
	
$video_arr=explode("#",$data);
$video_content=$video_arr[0];
if($video_content==''){$video_content='分享视频';}
$video_url=$video_arr[1];
$video_cover=$video_arr[2];
$video_topic_str=$video_arr[3];//话题字符串

$post_arr=array(
'post_content' => $video_content,
'post_author' => $author_id,
'post_status' => 'publish',
'comment_status'=>'open'
);
$post_id=wp_insert_post($post_arr);
if($post_id){
update_post_meta($post_id, 'video_url',$video_url);//视频地址
if($video_cover){
update_post_meta($post_id, 'video_img',$video_cover);//视频封面
}
update_post_meta($post_id, 'post_type','video');

wp_set_post_tags($post_id,$video_topic_str,false);//更新话题
}



}
$data_arr['code']=1;
$data_arr['msg']='导入成功！';




}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';	
}
header('content-type:application/json');
echo json_encode($data_arr);
<?php
//更新子论坛设置信息
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
if(isset($_POST['topic_id'])){
$topic_id=$_POST['topic_id'];

$desc=strip_tags($_POST['desc']);
$data_type=$_POST['data_type'];
$seo_title=$_POST['seo-title'];
$seo_desc=$_POST['seo-desc'];
$seo_keywords=$_POST['seo-keywords'];
$topic_views=$_POST['topic-views'];
$topic_avatar=$_POST['topic-avatar'];
$topic_bg=$_POST['topic-bg'];
$mobile_topic_bg=$_POST['mobile_topic_bg'];
$power=$_POST['power'];
$power_user=$_POST['power_user'];

$ad_header=$_POST['ad_header'];
$ad_footer=$_POST['ad_footer'];

$desc_num=mb_strlen($desc,'utf-8');
$max_number=800;
if($desc_num>$max_number){
$data_arr['code']=0;
$data_arr['msg']=sprintf(__( '话题描述不能超过%s字！','jinsom'),$max_number);
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}


$menu_arr=array();
if($_POST['all']){
array_push($menu_arr,'all');
}
if($_POST['commend']){
array_push($menu_arr,'commend');
}
if($_POST['words']){
array_push($menu_arr,'words');
}
if($_POST['music']){
array_push($menu_arr,'music');
}
if($_POST['single']){
array_push($menu_arr,'single');
}
if($_POST['video']){
array_push($menu_arr,'video');
}
if($_POST['bbs']){
array_push($menu_arr,'bbs');
}
if($_POST['pay']){
array_push($menu_arr,'pay');
}
$menu= implode(",",$menu_arr);
update_term_meta($topic_id,'menu',$menu);


update_term_meta($topic_id,'topic_desc',$desc);
update_term_meta($topic_id,'topic_data_type',$data_type);
update_term_meta($topic_id,'bbs_seo_title',$seo_title);
update_term_meta($topic_id,'bbs_seo_desc',$seo_desc);
update_term_meta($topic_id,'bbs_seo_keywords',$seo_keywords);
update_term_meta($topic_id,'topic_views',$topic_views);
update_term_meta($topic_id,'bbs_avatar',$topic_avatar);
update_term_meta($topic_id,'topic_bg',$topic_bg);
update_term_meta($topic_id,'mobile_topic_bg',$mobile_topic_bg);
update_term_meta($topic_id,'power',$power);
update_term_meta($topic_id,'power_user',$power_user);

update_term_meta($topic_id,'bbs_ad_header',$ad_header);
update_term_meta($topic_id,'bbs_ad_footer',$ad_footer);

$data_arr['code']=1;
$data_arr['msg']=__('修改成功！','jinsom');
}else{
$data_arr['code']=0;
$data_arr['msg']=__('参数错误！','jinsom');	
}

}else{
$data_arr['code']=0;
$data_arr['msg']=__('你没有权限！','jinsom');
}
header('content-type:application/json');
echo json_encode($data_arr);
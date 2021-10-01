<?php
//更新子论坛设置信息
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$bbs_id=$_POST['bbs_id'];
$category=get_category($bbs_id);
$cat_parents=$category->parent;//父级论坛id
$admin_a=get_term_meta($cat_parents,'bbs_admin_a',true);
$admin_a_arr=explode(",",$admin_a);
if(jinsom_is_admin($user_id)||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){//管理员或者大版主

if(isset($_POST['bbs_id'])){

$avatar=$_POST['avatar'];
$desc=$_POST['jinsom-bbs-desc'];
$name=$_POST['jinsom-bbs-name'];//子论坛名称
$rank=$_POST['jinsom-bbs-rank'];//子论坛排序
$slug=$_POST['jinsom-bbs-slug'];//子论坛路径
$bbs_seo_title=$_POST['jinsom-bbs-seo-title'];
$bbs_seo_desc=$_POST['jinsom-bbs-seo-desc'];
$bbs_seo_keywords=$_POST['jinsom-bbs-seo-keywords'];

update_term_meta($bbs_id,'bbs_avatar',$avatar);
update_term_meta($bbs_id,'desc',$desc);
update_term_meta($bbs_id,'bbs_seo_title',$bbs_seo_title);
update_term_meta($bbs_id,'bbs_seo_desc',$bbs_seo_desc);
update_term_meta($bbs_id,'bbs_seo_keywords',$bbs_seo_keywords);

$status=wp_update_term($bbs_id, 'category', 
array(
'description' => $rank,
'name' => $name,
'slug' => $slug
));

if($status){
$data_arr['code']=1;
$data_arr['msg']='更新成功！';	
}else{
$data_arr['code']=0;
$data_arr['msg']='更新失败！';		
}


}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';	
}

}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
}
header('content-type:application/json');
echo json_encode($data_arr);

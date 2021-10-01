<?php
//转移板块
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$post_id=$_POST['post_id'];
$bbs_id=$_POST['bbs_id'];
$new_bbs_id=$_POST['new_bbs_id'];

$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
if(empty($admin_a)){$admin_a=9909999;}
$admin_a_arr=explode(",",$admin_a);

if(jinsom_is_bbs_admin_a($user_id,$admin_a_arr)||jinsom_is_admin($user_id)){
$new_bbs_id=rtrim($new_bbs_id,',');
$new_bbs_id_arr=explode(",",$new_bbs_id);

$post_arr=array(
'ID' => $post_id, 
'post_category' =>$new_bbs_id_arr
);

$status=wp_update_post($post_arr);
if($status){
$data_arr['code']=1;
$data_arr['msg']='已经转移成功！';	
}else{
$data_arr['code']=0;
$data_arr['msg']='板块转移失败！';		
}

}else{
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';	
}


header('content-type:application/json');
echo json_encode($data_arr);
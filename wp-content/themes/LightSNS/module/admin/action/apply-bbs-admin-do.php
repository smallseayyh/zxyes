<?php 
//版主申请操作
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$ID=$_POST['ID'];
$type=$_POST['type'];
global $wpdb;
$table_name = $wpdb->prefix.'jin_bbs_note';
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name WHERE ID='$ID' limit 1;");
foreach ($bbs_data as $data) {
$author_id=$data->user_id;
$bbs_id=$data->bbs_id;
$note_type=$data->note_type;
}

if($type=='agree'){//通过
$wpdb->query("UPDATE $table_name SET status = 1 WHERE ID=$ID;");

if($note_type=='a'){
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);	
if($admin_a){
$admin_a_arr=explode(",",$admin_a);
array_push($admin_a_arr,$author_id);
$str=implode(",",$admin_a_arr);
$str=rtrim($str,',');
update_term_meta($bbs_id,'bbs_admin_a',$str);
}else{
update_term_meta($bbs_id,'bbs_admin_a',$author_id);	
}
}else{
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);	
if($admin_b){
$admin_b_arr=explode(",",$admin_b);
array_push($admin_b_arr,$author_id);
$str=implode(",",$admin_b_arr);
$str=rtrim($str,',');
update_term_meta($bbs_id,'bbs_admin_b',$str);
}else{
update_term_meta($bbs_id,'bbs_admin_b',$author_id);		
}
}

$admin_arr=explode(",",$admin);
array_push($admin_arr,$user_id);
jinsom_im_tips($author_id,__('你的版主申请已经通过','jinsom'));//IM提醒
$data_arr['code']=1;
$data_arr['msg']='申请已通过！';
}else if($type=='refuse'){
$wpdb->query("UPDATE $table_name SET status = 2 WHERE ID=$ID;");
jinsom_im_tips($author_id,__('你的版主申请被拒绝','jinsom').'，拒绝原因：'.$_POST['reason']);//IM提醒

$data_arr['code']=1;
$data_arr['msg']='拒绝成功！';
}else if($type=='del'){
$wpdb->query( "DELETE FROM $table_name where ID=$ID;" );
$data_arr['code']=1;
$data_arr['msg']='删除成功！';
}


header('content-type:application/json');
echo json_encode($data_arr);
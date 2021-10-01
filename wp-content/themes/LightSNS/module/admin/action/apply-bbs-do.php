<?php 
//论坛申请操作
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$bbs_name=jinsom_get_option('jinsom_bbs_name');//论坛名称

$ID=$_POST['ID'];
$type=$_POST['type'];
global $wpdb;
$table_name = $wpdb->prefix.'jin_bbs_note';
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name WHERE ID='$ID' limit 1;");
foreach ($bbs_data as $data) {
$author_id=$data->user_id;
$bbs_id=$data->bbs_id;
$note_type=$data->note_type;
$title=$data->title;
}

if($type=='agree'){//通过
$status_arr=wp_insert_term($title,"category");
if($status_arr['term_id']){
$bbs_id=$status_arr['term_id'];
$wpdb->query("UPDATE $table_name SET status = 1,bbs_id=$bbs_id WHERE ID=$ID;");
jinsom_add_bbs_like($author_id,$bbs_id);
update_term_meta($bbs_id,'bbs_admin_a',$author_id);//更新为版主
jinsom_im_tips($author_id,__('你的申请的'.$bbs_name.'已经审核通过','jinsom').'<br>'.$bbs_name.'名称：'.get_category($bbs_id)->name.'<br><a href="'.get_category_link($bbs_id).'" target="_blank" style="color:#1E9FFF;">>>--请点击这里访问进行管理--<<</a>');

$data_arr['code']=1;
$data_arr['msg']=__('申请已通过！','jinsom');
}else{
$data_arr['code']=0;
$data_arr['msg']=__('已经存在相同名称的'.$bbs_name.'！','jinsom');	
}


}else if($type=='refuse'){
$wpdb->query("UPDATE $table_name SET status = 2 WHERE ID=$ID;");

jinsom_im_tips($author_id,__('你的申请的'.$bbs_name.'已被拒绝','jinsom').'，拒绝原因：'.$_POST['reason']);//IM提醒

$data_arr['code']=1;
$data_arr['msg']=__('拒绝成功！','jinsom');
}else if($type=='del'){
$wpdb->query( "DELETE FROM $table_name where ID=$ID;" );
$data_arr['code']=1;
$data_arr['msg']=__('删除成功！','jinsom');
}


header('content-type:application/json');
echo json_encode($data_arr);
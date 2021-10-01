<?php 
//消息通知操作
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$type=$_POST['type'];

//发表通知
if($type=='add'){
$content=$_POST['content'];
jinsom_add_tips(999999,9999999,9999999,'notice',$content,'');
$data_arr['code']=1;
$data_arr['msg']='发表成功！';
}

//编辑通知
if($type=='edit'){
$id=$_POST['id'];
$content=$_POST['content'];
global $wpdb;
$table_name = $wpdb->prefix . 'jin_notice';
$data=$wpdb->query("UPDATE $table_name SET notice_content = '$content' WHERE ID='$id';");
if($data){
$data_arr['code']=1;
$data_arr['msg']='编辑成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='编辑失败！';	
}
}

//删除通知
if($type=='delete'){
$id=$_POST['id'];
global $wpdb;
$table_name = $wpdb->prefix . 'jin_notice';
$data=$wpdb->query( "DELETE FROM $table_name WHERE ID = $id;" );
if($data){
$data_arr['code']=1;
$data_arr['msg']='删除成功！';
}else{
$data_arr['code']=0;
$data_arr['msg']='删除失败！';	
}
}




header('content-type:application/json');
echo json_encode($data_arr);
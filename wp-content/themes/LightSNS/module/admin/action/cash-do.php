<?php 
//提现记录操作
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$ID=$_POST['ID'];
$type=$_POST['type'];
global $wpdb;
$table_name = $wpdb->prefix.'jin_cash';
$cash_data = $wpdb->get_results("SELECT * FROM $table_name WHERE ID='$ID' limit 1;");
foreach ($cash_data as $data) {
$author_id=$data->user_id;
$number=$data->credit;
}

if($type=='agree'){//通过提现
$wpdb->query("UPDATE $table_name SET status = 1 WHERE ID=$ID;");
jinsom_add_tips($author_id,$user_id,0,'cash','你的提现已审核通过','提现已通过');
$data_arr['code']=1;
$data_arr['msg']='提现审核已通过！';
}else if($type=='refuse'){
$reason=$_POST['reason'];
if($reason==''){
$data_arr['code']=0;
$data_arr['msg']='请输入拒绝原因！';	
header('content-type:application/json');
echo json_encode($data_arr);
exit();
}
$wpdb->query("UPDATE $table_name SET status = 2,mark='$reason' WHERE ID=$ID;");
jinsom_update_credit($author_id,$number,'add','cash-refuse','提现未通过返还',1,'');  
jinsom_add_tips($author_id,$user_id,0,'cash','你的提现已被拒绝','提现被拒绝');

$data_arr['code']=1;
$data_arr['msg']='提现审核已经拒绝！';
}


header('content-type:application/json');
echo json_encode($data_arr);
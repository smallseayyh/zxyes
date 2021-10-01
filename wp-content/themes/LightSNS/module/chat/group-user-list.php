<?php
require( '../../../../../wp-load.php' );
if (is_user_logged_in()) {
$user_id=$current_user->ID;

//获取侧栏群组成员	
if(isset($_POST['bbs_id'])){
$bbs_id=$_POST['bbs_id'];

$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr = explode(',',$admin_a);
$admin_b_arr = explode(',',$admin_b);

//输出大版主
if(!empty($admin_a)){
foreach ($admin_a_arr as $admin_a_arrs) {
echo '
<li onclick="jinsom_open_user_chat('.$admin_a_arrs.',this)">
<m>'.jinsom_avatar($admin_a_arrs,'30',avatar_type($admin_a_arrs)).'</m>
<span>'.jinsom_nickname($admin_a_arrs).'</span>
<i class="jinsom-icon jinsom-guanliyuan1 big"></i>
</li>
';
}
}

//输出小版主
if(!empty($admin_b)){
foreach ($admin_b_arr as $admin_b_arrs) {
echo '
<li onclick="jinsom_open_user_chat('.$admin_b_arrs.',this)">
<m>'.jinsom_avatar($admin_b_arrs,'30',avatar_type($admin_b_arrs)).'</m>
<span>'.jinsom_nickname($admin_b_arrs).'</span>
<i class="jinsom-icon jinsom-guanliyuan1 small"></i>
</li>
';
}
}



global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
$get_bbs = $wpdb->get_results("SELECT * FROM $table_name where bbs_id=$bbs_id limit 250;");
if($get_bbs){
foreach ($get_bbs as $bbs) {
$group_user_id=$bbs->user_id;
if(! in_array($group_user_id,$admin_a_arr)&&! in_array($group_user_id,$admin_b_arr)){
echo '
<li onclick="jinsom_open_user_chat('.$group_user_id.',this)">
<m>'.jinsom_avatar($group_user_id,'30',avatar_type($group_user_id)).'</m>
<span>'.jinsom_nickname($group_user_id).'</span>
</li>
';
}

}
echo '<li class="jinsom-chat-empty-user"></li>';	
}

}


}
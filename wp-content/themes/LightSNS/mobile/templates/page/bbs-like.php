<?php 
//我的关注的论坛
require( '../../../../../../wp-load.php');
$type=$_GET['type'];
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
$jinsom_publish_bbs_user_select_type = jinsom_get_option('jinsom_publish_bbs_user_select_type');
?>
<div data-page="bbs-like" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>

<?php if($type=='follow-bbs'||$type=='commend-bbs'){?>
<div class="center sliding"><?php _e('发表选择','jinsom');?></div>
<?php }else{?>
<div class="center sliding"><?php _e('我关注的','jinsom');?></div>
<?php }?>


<div class="right">
<?php if($type!='commend-bbs'){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/apply-bbs.php" class="link icon-only"><?php _e('申请','jinsom');?></a>
<?php }else{?>
<a href="#" class="link icon-only"></a>
<?php }?>
</div>

</div>
</div>

<div class="page-content jinsom-bbs-like-content publish">

<?php 

if($type=='commend-bbs'){//推荐的论坛


$jinsom_bbs_commend_list = jinsom_get_option('jinsom_bbs_commend_list');
if($jinsom_bbs_commend_list){
echo '<div class="jinsom-chat-user-list bbs-commend list-block">';
$jinsom_bbs_commend_list_arr=explode(",",$jinsom_bbs_commend_list);
foreach ($jinsom_bbs_commend_list_arr as $data) {

echo '
<li onclick=\'jinsom_publish_power("bbs",'.$data.',"")\'>
<div class="item-content">
<div class="item-media">
<a href="#" class="link">
'.jinsom_get_bbs_avatar($data,0).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="#" class="link">
<div class="name">'.get_category($data)->name.'</div>
<div class="desc">'.strip_tags(get_term_meta($data,'desc',true)).'</div>
</a>
</div>
</div>
</div>
</li>
';



}
echo '</div>';
}else{
echo jinsom_empty('管理员还没有设置任何论坛，请在发表模块-帖子设置-快捷发帖显示的论坛 进行设置。');
}

}else{//我关注的论坛


global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id ORDER BY ID DESC limit 50;");
if($bbs_data){
echo '<div class="jinsom-chat-user-list bbs-commend list-block">';
foreach ($bbs_data as $data) {
$bbs_id=$data->bbs_id;
$category=get_category($bbs_id);
$bbs_parents=$category->parent;
if($bbs_parents==0){

if($type=='follow-bbs'){
$publish_action='javascript:jinsom_publish_power("bbs",'.$bbs_id.',"")';
}else{
$publish_action=jinsom_mobile_bbs_url($bbs_id);	
}


echo '
<li id="jinsom-bbs-like-'.$bbs_id.'">
<div class="item-content">
<div class="item-media">
<a href=\''.$publish_action.'\' class="link">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href=\''.$publish_action.'\' class="link">
<div class="name">'.get_category($bbs_id)->name.'</div>
<div class="desc">'.strip_tags(get_term_meta($bbs_id,'desc',true)).'</div>
</a>
</div>
</div>
</div>
</li>
';


}


}
echo '</div>';
}else{
echo jinsom_empty();
echo '<div style="text-align:center;"><a href="'.$theme_url.'/mobile/templates/page/bbs-commend.php" class="link jinsom-follow-more-bbs">'.__('马上关注','jinsom').'</a></div>';
}


}//用户关注的论坛

?>
</div>


</div>
</div>        
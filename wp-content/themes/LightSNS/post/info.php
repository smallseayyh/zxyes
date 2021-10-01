<?php 
$time_a=time ()- (1*24*60*60);
if(get_the_time('Y-m-d')==date('Y-m-d',time())){
$time= __('今天','jinsom').' '.get_the_time('H:i');
}else if(get_the_time('Y-m-d')==date('Y-m-d',$time_a)){
$time= __('昨天','jinsom').' '.get_the_time('H:i');
}else{
$time= get_the_time('m-d H:i');
}
?>

<?php if($post_type!='single'&&!$is_bbs_post||(is_single()&&$post_type=='single')){?>
<div class="jinsom-post-user-info">
<div class="jinsom-post-user-info-avatar" user-data="<?php echo $author_id; ?>">
<a href="<?php echo jinsom_userlink($author_id);?>" style="display: inline-block;">
<?php echo jinsom_vip_icon($author_id);?>
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
<?php echo jinsom_verify($author_id);?>
</a>
<div class="jinsom-user-info-card"></div>
</div>

<div class="jinsom-post-user-info-name">
<?php echo jinsom_nickname_link($author_id);?>
<?php echo jinsom_sex($author_id);?>
<?php echo jinsom_lv($author_id);?>
<?php echo jinsom_vip($author_id);?>
<?php echo jinsom_honor($author_id);?>
<?php 
if(!$title){
if(isset($_GET['author_id'])||isset($_POST['author_id'])||$is_author){
if(get_user_meta($author_id,'sticky',true)==$post_id){echo '<span class="jinsom-mark jinsom-member-top"></span>';}
}else{
if($sticky_post){echo '<span class="jinsom-mark jinsom-top"></span>';}	
}
if($commend_post){echo '<span class="jinsom-mark jinsom-commend-icon"></span>';}
}
?>
</div>

<div class="jinsom-post-user-info-time" title="<?php echo $post_time;?>">
<?php
echo $time;//输出时间
echo jinsom_post_from($post_id);//来自
if($reprint){
echo '<i class="jinsom-icon jinsom-zhuanzai" title="'.__('转发','jinsom').'"></i>';
}else{
if($post_power==1){
echo '<i class="jinsom-icon jinsom-fufei" title="'.__('付费内容','jinsom').'"></i>';
}else if($post_power==2){
echo '<i class="jinsom-icon jinsom-mima" title="'.__('密码内容','jinsom').'"></i>';
}else if($post_power==3){
echo '<i class="jinsom-icon jinsom-biyan" title="'.__('私密内容','jinsom').'"></i>';
}else if($post_power==4){
echo '<i class="jinsom-icon jinsom-vip-type" title="'.__('VIP可见','jinsom').'"></i>';
}else if($post_power==5){
echo '<i class="jinsom-icon jinsom-denglu" title="'.__('登录可见','jinsom').'"></i>';
}else if($post_power==6){
echo '<i class="jinsom-icon jinsom-pinglun2" title="'.__('回复可见','jinsom').'"></i>';
}else if($post_power==7){
echo '<i class="jinsom-icon jinsom-dagou" title="'.__('认证可见','jinsom').'"></i>';
}else if($post_power==8){
echo '<i class="jinsom-icon jinsom-qunzu" title="'.__('粉丝可见','jinsom').'"></i>';
}else{
echo '<i class="jinsom-icon jinsom-gongkai1" title="'.__('公开内容','jinsom').'"></i>';
}
}

?>
</div>
</div><!-- 作者信息 -->
<?php }else{?>
<div class="jinsom-post-user-info single">
<div class="jinsom-post-user-info-avatar" user-data="<?php echo $author_id; ?>">
<a href="<?php echo jinsom_userlink($author_id);?>" style="display: inline-block;">
<?php echo jinsom_vip_icon($author_id);?>
<?php echo jinsom_avatar($author_id, '40' , avatar_type($author_id) );?>
<?php echo jinsom_verify($author_id);?>
</a>
<div class="jinsom-user-info-card"></div>
</div>	
<div class="jinsom-post-user-info-name">
<?php echo jinsom_nickname_link($author_id);?>
</div>
</div>
<?php }?>





<div class="jinsom-post-setting">
<i class="jinsom-icon jinsom-xiangxia2"></i>
<div  class="jinsom-post-setting-box">
<?php if(!is_single()&&!is_page()){?>
<li onclick="jinsom_post_link(this);" data="<?php echo $permalink; ?>"><?php _e('查看全文','jinsom');?></li>
<?php }?>
<li onclick="jinsom_post_link(this);" data="<?php echo jinsom_userlink($author_id);?>"><?php _e('查看作者','jinsom');?></li>
<?php 

if(jinsom_is_admin($user_id)&&$post_status=='publish'&&$post_power!=3){
if($sticky_post){
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-all",this)\'>'.__('取消全局','jinsom').'</li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-all",this)\'>'.__('全局置顶','jinsom').'</li>';
}

if($post_type!='redbag'){
if($commend_post){
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"commend-post",this)\'>'.__('取消推荐','jinsom').'</li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"commend-post",this)\'>'.__('推荐内容','jinsom').'</li>';
}
}
}


if (is_user_logged_in()) { 

if($post_power!=3){

if(jinsom_is_admin_x($user_id)&&!$is_bbs_post&&$post_status=='publish'&&$post_type!='redbag'&&!is_page()){
echo '<li onclick="jinsom_content_management_refuse('.$post_id.',0,1,this)">'.__('驳回内容','jinsom').'</li>';
}

if($user_id==$author_id){//主页置顶
if((int)get_user_meta($user_id,'sticky',true)==$post_id){
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-member",this)\'>'.__('取消主页','jinsom').'</li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-member",this)\'>'.__('主页置顶','jinsom').'</li>';
}
}

}

if($user_id!=$author_id){
if(jinsom_is_blacklist($user_id,$author_id)){
echo '<li onclick=\'jinsom_add_blacklist("remove",'.$author_id.',this)\'>'.__('取消拉黑','jinsom').'</li>';	
}else{
echo '<li onclick=\'jinsom_add_blacklist("add",'.$author_id.',this)\'>'.__('拉黑名单','jinsom').'</li>';	
}
}

if(jinsom_is_admin_x($user_id)||$user_id==$author_id){
if(!$reprint&&$post_type!='redbag'){//转发和红包的内容不允许编辑
$edit_time=(int)jinsom_get_option('jinsom_edit_time_max');
$single_time=strtotime(get_the_time('Y-m-d H:i:s'));
if(time()-$single_time<=60*60*$edit_time||jinsom_is_admin_x($user_id)){
if(is_page()){
if(current_user_can('level_10')){
echo '<li><a href="/wp-admin/post.php?post='.$post_id.'&action=edit" target="_blank">'.__('编辑页面','jinsom').'</a></li>';
}
}else{
echo '<li onclick=\'jinsom_editor_form("'.$post_type.'",'.$post_id.')\'>'.__('编辑内容','jinsom').'</li>';	
}
}
}


echo '<li onclick="jinsom_delete_post('.$post_id.',this)">'.__('删除内容','jinsom').'</li>';
}
}?>
</div>
</div>
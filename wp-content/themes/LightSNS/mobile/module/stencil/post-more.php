<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$post_id=$_POST['post_id'];
$bbs_id=$_POST['bbs_id'];
$type=$_POST['type'];
$is_bbs_post=is_bbs_post($post_id);
$commend_post=get_post_meta($post_id,'jinsom_commend',true);
$author_id=jinsom_get_post_author_id($post_id);
$post_power=get_post_meta($post_id,'post_power',true);
$post_type=get_post_meta($post_id,'post_type',true);
if($is_bbs_post){
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
}


$referral_link_name = jinsom_get_option('jinsom_referral_link_name');
$url=get_the_permalink($post_id);
if(strpos($url,'?')){
$url=$url.'&'.$referral_link_name.'='.$user_id;
}else{
$url=$url.'?'.$referral_link_name.'='.$user_id;  
}
//$url=jinsom_share_url($url);
$title=get_the_title($post_id);
if(!$title){
$content=htmlspecialchars(strip_tags(jinsom_get_post_content($post_id)));
$title=mb_substr($content,0,100,'utf-8');	
}

?>
<div class="jinsom-post-more-form">
<div class="box share clear">
<li onclick="window.open('http://service.weibo.com/share/share.php?title=<?php echo $title;?>&url=<?php echo $url;?>');"><i class="jinsom-icon jinsom-weibo"></i><p><?php _e('微博','jinsom');?></p></li>
<li onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=<?php echo $title;?>&url=<?php echo $url;?>');"><i class="jinsom-icon jinsom-qqkongjian2"></i><p><?php _e('空间','jinsom');?></p></li>

<?php if($type!='no'){?>
<li class="playbill" onclick="jinsom_content_playbill_page(<?php echo $post_id;?>,'<?php echo $url;?>')"><i class="jinsom-icon jinsom-haibao"></i><p><?php _e('海报','jinsom');?></p></li>
<?php }?>

<li data-clipboard-target="#jinsom-sidebar-share-link" id="jinsom-copy-share-link"><i class="jinsom-icon jinsom-lianjie"></i><p><?php _e('分享链接','jinsom');?></p></li>
<li style="opacity: 0;height: 0;width: 0;" id="jinsom-sidebar-share-link">【<?php echo $title;?>-<?php echo jinsom_get_option('jinsom_site_name');?>】<?php echo $url;?></li>
</div>


<?php 


if(is_user_logged_in()){

echo '<div class="box do clear">';

if($post_power!=1&&$post_power!=2&&$post_power!=3&&$post_power!=4&&$post_power!=5&&$post_type!='redbag'&&jinsom_get_option('jinsom_publish_reprint_on_off')){
echo '<li class="reprint" onclick=\'jinsom_reprint_form('.$post_id.')\'><i class="jinsom-icon jinsom-zhuanfa"></i><p>'.__('转发','jinsom').'</p></li>';
}

if(jinsom_is_admin_x($user_id)&&$post_power!=3){
if(is_sticky($post_id)){//全局置顶
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-all",this)\'><i class="jinsom-icon jinsom-zhiding1"></i><p>'.__('取消全局','jinsom').'</p></li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-all",this)\'><i class="jinsom-icon jinsom-zhiding1"></i><p>'.__('全局置顶','jinsom').'</p></li>';
}
}


if($post_type!='redbag'&&$post_power!=3){

if(!$is_bbs_post){
if(jinsom_is_admin_x($user_id)){

if(jinsom_is_admin($user_id)){
if($commend_post){
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"commend-post",this)\'><i class="jinsom-icon jinsom-dianzan"></i><p>'.__('取消推荐','jinsom').'</p></li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"commend-post",this)\'><i class="jinsom-icon jinsom-dianzan"></i><p>'.__('推荐','jinsom').'</p></li>';
}
}

echo '<li onclick=\'jinsom_content_management_refuse('.$post_id.',0,"'.$type.'",this)\'><i class="jinsom-icon jinsom-tanhao"></i><p>'.__('驳回','jinsom').'</p></li>';
}
}else{//帖子
if(jinsom_is_admin_x($user_id)||(in_array($user_id,$admin_a_arr)||(in_array($user_id,$admin_b_arr))&&$user_id)){

if(get_user_meta($user_id,'user_power',true)!=3){//巡查员不显示
if($commend_post){
echo '<li onclick=\'jinsom_sticky('.$post_id.','.$bbs_id.',"commend-bbs",this)\'><i class="jinsom-icon jinsom-dianzan"></i><p>'.__('取消加精','jinsom').'</p></li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.','.$bbs_id.',"commend-bbs",this)\'><i class="jinsom-icon jinsom-dianzan"></i><p>'.__('加精','jinsom').'</p></li>';
}
if(get_post_meta($post_id,'jinsom_sticky',true)){//板块置顶
echo '<li onclick=\'jinsom_sticky('.$post_id.','.$bbs_id.',"sticky-bbs",this)\'><i class="jinsom-icon jinsom-zhiding"></i><p>'.__('取消版顶','jinsom').'</p></li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.','.$bbs_id.',"sticky-bbs",this)\'><i class="jinsom-icon jinsom-zhiding"></i><p>'.__('板块置顶','jinsom').'</p></li>';
}

}

echo '<li onclick=\'jinsom_content_management_refuse('.$post_id.','.$bbs_id.',"'.$type.'",this)\'><i class="jinsom-icon jinsom-tanhao"></i><p>'.__('驳回','jinsom').'</p></li>';	
}
}
}

if(jinsom_is_collect($user_id,'post',$post_id,'')){
echo '<li onclick=\'jinsom_collect('.$post_id.',"post",this)\' class="collect-post-'.$post_id.'"><i class="jinsom-icon jinsom-shoucang"></i><p>'.__('已收藏','jinsom').'</p></li>';
}else{
echo '<li onclick=\'jinsom_collect('.$post_id.',"post",this)\' class="collect-post-'.$post_id.'"><i class="jinsom-icon jinsom-shoucang1"></i><p>'.__('收藏','jinsom').'</p></li>';
}

if($user_id!=$author_id){
if(jinsom_is_blacklist($user_id,$author_id)){
echo '<li onclick=\'jinsom_add_blacklist("remove",'.$author_id.',this)\'><i class="jinsom-icon jinsom-lahei"></i><p>'.__('取消拉黑','jinsom').'</p></li>';
}else{
echo '<li onclick=\'jinsom_add_blacklist("add",'.$author_id.',this)\'><i class="jinsom-icon jinsom-lahei"></i><p>'.__('拉黑','jinsom').'</p></li>';
}


//举报
echo '<li onclick=\'layer.open({content:"'.__('暂未开启','jinsom').'",skin:"msg",time:2});\'><i class="jinsom-icon jinsom-jubao"></i><p>'.__('举报','jinsom').'</p></li>';
}


if($user_id==$author_id&&$post_power!=3){
if((int)get_user_meta($user_id,'sticky',true)==$post_id){
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-member",this)\'><i class="jinsom-icon jinsom-hebingxingzhuang"></i><p>'.__('取消主页','jinsom').'</p></li>';
}else{
echo '<li onclick=\'jinsom_sticky('.$post_id.',0,"sticky-member",this)\'><i class="jinsom-icon jinsom-hebingxingzhuang"></i><p>'.__('主页置顶','jinsom').'</p></li>';	
}

}

if(!$is_bbs_post){
if(jinsom_is_admin_x($user_id)||$user_id==$author_id){
echo '<li onclick=\'jinsom_delete_post('.$post_id.',this,"'.$type.'")\'><i class="jinsom-icon jinsom-shanchu"></i><p>'.__('删除','jinsom').'</p></li>';
}
}else{
if(jinsom_is_admin_x($user_id)||$user_id==$author_id||(in_array($user_id,$admin_a_arr)&&$user_id)){
echo '<li onclick=\'jinsom_delete_bbs_post('.$post_id.','.$bbs_id.',this,"'.$type.'")\'><i class="jinsom-icon jinsom-shanchu"></i><p>'.__('删除','jinsom').'</p></li>';	
}
}

echo '</div>';

}

?>

<div class="cancel" onclick="layer.closeAll()"><?php _e('取消操作','jinsom');?></div>
</div>


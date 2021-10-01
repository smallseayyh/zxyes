<?php 
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');
}else{
if(get_option('LightSNS_Module_pc/topic')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/pc/topic/index.php');
}else{
get_header(); 
$require_url=get_template_directory();
$user_id=$current_user->ID;
$topic_name=single_tag_title('', false);
$topic_data=get_term_by('name',$topic_name,'post_tag');
$topic_id=$topic_data->term_id;
$desc=get_term_meta($topic_id,'topic_desc',true);//话题描述
if(!$desc){$desc=jinsom_get_option('jinsom_topic_default_desc');}

$topic_number=$topic_data->count;
$topic_url=get_tag_link($topic_id);
$data_type=get_term_meta($topic_id,'topic_data_type',true);
if(empty($data_type)){$data_type='all';}

//更新标签浏览量
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
update_term_meta($topic_id,'topic_views',$topic_views+1);

$menu=get_term_meta($topic_id,'menu',true);
if(!$menu){$menu='all,commend,words,music,single,video,bbs,pay';}
$menu_arr=explode(",",$menu);
$power=get_term_meta($topic_id,'power',true);

$topic_bg=jinsom_topic_bg($topic_id);
if($topic_bg){
$topic_bg='style="background-image:url('.$topic_bg.')"';
}else{
$topic_bg='';
}
?>

<!-- 主内容 -->
<div class="jinsom-main-content tag clear">
<?php require($require_url.'/sidebar/sidebar-tag.php');?>
<div class="jinsom-content-left"><!-- 左侧 -->

<div class="jinsom-topic-header">
<div class="shadow"></div>
<?php 
// if($power=='admin'){
// echo '<div class="type admin"><span>'.__('官方','jinsom').'</span></div>';
// }else if($power=='vip'){
// echo '<div class="type vip"><span>VIP</span></div>';
// }else if($power=='verify'){
// echo '<div class="type verify"><span>'.__('认证','jinsom').'</span></div>';
// }else if($power=='user'){
// echo '<div class="type user"><span>'.__('私人','jinsom').'</span></div>';
// }
?>

<?php if(jinsom_is_admin($user_id)){?>
<i class="jinsom-icon setting jinsom-shezhi" onclick="jinsom_topic_setting_form(<?php echo $topic_id;?>);"></i>
<?php }?>
<div class="jinsom-topic-header-bg" <?php echo $topic_bg;?>></div>
<div class="jinsom-topic-header-main">
<div class="jinsom-topic-header-content">
<i class="jinsom-icon a jinsom-shuangyin1"></i>
<?php echo $desc;?>
<i class="jinsom-icon b jinsom-shuangyin2"></i> 
</div>
</div>
</div>

<div class="jinsom-topic-info" data="<?php echo $topic_id;?>">
<div class="jinsom-topic-info-content clear">
<span class="name"># <?php echo $topic_name; ?> #</span>
<?php if(jinsom_is_topic_like($user_id,$topic_id)){?>
<span class="jinsom-topic-follow-btn had opacity" onclick="jinsom_topic_like(<?php echo $topic_id;?>,this)"><i class="jinsom-icon jinsom-yiguanzhu"></i> <?php _e('已关注','jinsom');?></span>
<?php }else{?>
<span class="jinsom-topic-follow-btn opacity" onclick="jinsom_topic_like(<?php echo $topic_id;?>,this)"><i class="jinsom-icon jinsom-guanzhu"></i> <?php _e('关注','jinsom');?></span>
<?php }?>
<span class="jinsom-topic-publish-btn opacity" onclick="jinsom_join_topic('<?php echo $topic_name;?>');"><i class="jinsom-icon jinsom-fabiao1"></i> <?php _e('发表','jinsom');?></span>
<span class="right">
<span><?php echo jinsom_views_show($topic_views);?><?php _e('浏览','jinsom');?></span>
<span><?php echo $topic_number;?><?php _e('内容','jinsom');?></span>
<span><i><?php echo jinsom_topic_like_number($topic_id);?></i><?php _e('关注','jinsom');?></span>
</span>
</div>
</div>



<?php 
//话题菜单
$jinsom_topic_menu = jinsom_get_option('jinsom_topic_menu');
$enabled=$jinsom_topic_menu['enabled'];
if($enabled){
if(count($menu_arr)>1){
echo '<div class="jinsom-topic-menu clear">';
foreach($enabled as $x=>$x_value) {
switch($x){
case 'all': 
if(in_array("all",$menu_arr)){
if($data_type=='all'){$a='class="on"';}else{$a='';}
echo '<li onclick=\'jinsom_topic_data("all",this)\' '.$a.'>'.__('全部','jinsom').'</li>'; 
} 
break;
case 'commend': 
if(in_array("commend",$menu_arr)){
if($data_type=='commend'){$a='class="on"';}else{$a='';}
echo '<li type="commend" onclick=\'jinsom_topic_data("commend",this)\' '.$a.'>'.__('推荐','jinsom').'</li>';  
}
break;
case 'words':   
if(in_array("words",$menu_arr)){
if($data_type=='words'){$a='class="on"';}else{$a='';}
echo '<li type="words" onclick=\'jinsom_topic_data("words",this)\' '.$a.'>'.__('动态','jinsom').'</li>';  
}
break;
case 'music':    
if(in_array("music",$menu_arr)){
if($data_type=='music'){$a='class="on"';}else{$a='';}
echo '<li type="music" onclick=\'jinsom_topic_data("music",this)\' '.$a.'>'.__('音乐','jinsom').'</li>';  
}
break;
case 'single': 
if(in_array("single",$menu_arr)){
if($data_type=='single'){$a='class="on"';}else{$a='';}
echo '<li type="single" onclick=\'jinsom_topic_data("single",this)\' '.$a.'>'.__('文章','jinsom').'</li>';
}
break;
case 'video': 
if(in_array("video",$menu_arr)){
if($data_type=='video'){$a='class="on"';}else{$a='';}
echo '<li type="video" onclick=\'jinsom_topic_data("video",this)\' '.$a.'>'.__('视频','jinsom').'</li>';
}
break;  
case 'bbs':    
if(in_array("bbs",$menu_arr)){
echo '<li type="bbs" onclick=\'jinsom_topic_data("bbs",this)\'>'.__('帖子','jinsom').'</li>';  
}
break; 
case 'pay':    
if(in_array("pay",$menu_arr)){
echo '<li type="pay" onclick=\'jinsom_topic_data("pay",this)\'>'.__('付费','jinsom').'</li>'; 
} 
break; 
}}
echo '</div>';
}}
?>

<?php echo get_term_meta($topic_id,'bbs_ad_header',true);?>

<div class="jinsom-topic-post-list">
<?php 
$paged = max( 1, get_query_var('page') );
$number = get_option('posts_per_page', 10);
$offset = ($paged-1)*$number;
if($data_type=='all'){
$args = array(
'post_status' => array('publish'),
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);
}else if($data_type=='words'||$data_type=='music'||$data_type=='single'||$data_type=='video'){
$args = array(
'post_status' => array('publish'),
'meta_key' => 'post_type',
'meta_value' => $data_type,
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}else{
$args = array(
'post_status' => array('publish'),
'meta_key' => 'jinsom_commend',
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}
$args['no_found_rows']=true;
query_posts($args);
if(have_posts()){
while (have_posts()) : the_post();
require($require_url.'/post/post-list.php');
endwhile;
if(ceil($topic_number/$number)>=2){
echo '<div class="jinsom-more-posts" data="2" onclick=\'jinsom_topic_data_more("'.$data_type.'",this);\'>'.__('加载更多','jinsom').'</div>';	
} 
}else{
echo jinsom_empty();
}

?>
</div>

<?php echo get_term_meta($topic_id,'bbs_ad_footer',true);?>

</div><!-- 左侧结束 -->



</div>

<?php 
get_footer();
}//话题开发地址

 ?>
<?php }//判断是否移动端
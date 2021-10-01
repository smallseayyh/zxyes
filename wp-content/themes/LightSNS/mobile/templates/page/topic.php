<?php 
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$topic_id=$_GET['topic_id'];
$topic_data=get_term_by('id',$topic_id,'post_tag');
$topic_name=$topic_data->name;
$topic_number=$topic_data->count;
$desc=get_term_meta($topic_id,'topic_desc',true);//话题描述
if(!$desc){$desc=jinsom_get_option('jinsom_topic_default_desc');}
$topic_bg=jinsom_topic_bg($topic_id);//移动端背景封面
if($topic_bg){
$topic_bg='style="background-image:url('.$topic_bg.');"';
}else{
$topic_bg='';
}


$data_type=get_term_meta($topic_id,'topic_data_type',true);
if(empty($data_type)){$data_type='all';}

//更新话题浏览量
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
update_term_meta($topic_id,'topic_views',$topic_views+1);

$menu=get_term_meta($topic_id,'menu',true);
if(!$menu){$menu='all,commend,words,music,single,video,bbs,pay';}
$menu_arr=explode(",",$menu);
$power=get_term_meta($topic_id,'power',true);
?>
<div data-page="topic" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center test"><?php echo $topic_name;?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<?php if(is_user_logged_in()){?>
<div class="jinsom-bbs-publish-icon" onclick="jinsom_join_topic('<?php echo $topic_name;?>')"><i class="jinsom-icon jinsom-fabiao-"></i></div>
<?php }else{?>
<div class="jinsom-bbs-publish-icon open-login-screen"><i class="jinsom-icon jinsom-fabiao-"></i></div>
<?php }?>

<div class="page-content jinsom-topic-content">

<div class="jinsom-topic-page-header" <?php echo $topic_bg;?> data="<?php echo $topic_id;?>" topic="<?php echo $topic_name;?>">
<div class="shadow"></div>
<div class="top">
<div class="avatarimg">
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
<?php echo jinsom_get_bbs_avatar($topic_id,1);?>
</div>
<div class="info">
<div class="name"># <?php echo $topic_name;?></div>
<div class="number">
<span><?php _e('内容','jinsom');?><i><?php echo $topic_number;?></i></span>
<span><?php _e('关注','jinsom');?><i><?php echo jinsom_topic_like_number($topic_id);?></i></span>
<span><?php _e('浏览','jinsom');?><i><?php echo jinsom_views_show($topic_views);?></i></span>
</div>
</div>
<?php echo jinsom_topic_like_btn($user_id,$topic_id);?>
</div>
<div class="desc"><?php echo $desc;?>	</div>
</div>


<?php 
//话题菜单
$jinsom_topic_menu = jinsom_get_option('jinsom_topic_menu');

$enabled=$jinsom_topic_menu['enabled'];
if($enabled&&count($menu_arr)>1){
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
}
?>



<div class="jinsom-topic-post-list" page="2" type="<?php echo $data_type;?>">
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
require(get_template_directory().'/mobile/templates/post/post-list.php' );	
endwhile;
}else{
echo jinsom_empty();
}

?>
</div>


</div>
</div>        
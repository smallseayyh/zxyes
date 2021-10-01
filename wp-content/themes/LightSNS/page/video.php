<?php
/*
Template Name:视频专题
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
get_header();


$post_id=get_the_ID();
$user_id = $current_user->ID;
$video_data=get_post_meta($post_id,'video_show_page_data',true);

if(!$video_data){
echo jinsom_empty('请在新建页面的时候配置当前页面的数据！');exit();
}


$jinsom_video_commend=$video_data['jinsom_video_commend'];
$jinsom_video_special_add=$video_data['jinsom_video_special_add'];


?>

<div class="jinsom-video-content">
<div class="jinsom-main-content">

<?php echo do_shortcode($video_data['jinsom_video_special_header_html']);?>


<div class="jinsom-video-header-banner">
<div class="left" id="jinsom-video-banner"></div>

<div class="right">
<?php 
if($jinsom_video_commend=='custom'){//指定的视频数据
$jinsom_video_commend_custom=$video_data['jinsom_video_commend_custom'];
$jinsom_video_commend_custom_arr=explode(",",$jinsom_video_commend_custom);
if($jinsom_video_commend_custom){
$i=1;
foreach ($jinsom_video_commend_custom_arr as $video_post_id){
$video_url=get_post_meta($video_post_id,'video_url',true);
if($i>6){break;}//只显示6条
if($i==1){
$first='class="on"';
$first_video_url=$video_url;
$first_video_img=jinsom_video_cover($video_post_id);
}else{
$first='';
}
$video_title=get_the_title($video_post_id);
if($video_title==''){$video_title=jinsom_get_post_content($video_post_id);}
echo '<li '.$first.'><div onclick=\'jinsom_banner_video_play("'.$video_url.'",this)\' class="shadow"></div><img loading="lazy" src="'.jinsom_video_cover($video_post_id).'" alt="'.strip_tags($video_title).'"><p><a href="'.get_the_permalink($video_post_id).'" target="_blank">'.strip_tags($video_title).'</a></p></li>';
$i++;
}
}
}else{

if($jinsom_video_commend=='new'){
$args = array(
'post_status' => 'publish',
'showposts' => 6,
'meta_key' => 'post_type',
'meta_value'=>'video',
);
}else if($jinsom_video_commend=='comment'){
$date_query=array(
array(
'column' => 'post_date',
'before' => date('Y-m-d',time()+3600*24),
'after' =>date('Y-m-d',time()-3600*24*30)
)
);
$args = array(
'post_status' => 'publish',
'showposts' => 6,
'meta_key' => 'post_type',
'meta_value'=>'video',
'orderby'=>'comment_count',
'date_query' => $date_query,
);
}else if($jinsom_video_commend=='views'){//播放量最多的
$date_query=array(
array(
'column' => 'post_date',
'before' => date('Y-m-d',time()+3600*24),
'after' =>date('Y-m-d',time()-3600*24*30)
)
);
$args = array(
'post_status' => 'publish',
'showposts' => 6,
'meta_key' => 'post_views',
'meta_value_num' => 10,
'meta_compare' => '>=',
'orderby' => 'rand',
'date_query' => $date_query,
'meta_query' => array(
'post_type'=>array(
'key' => 'post_type',
'value' =>'video',
)
)
);
}
//过滤有权限的
$args['meta_query']['post_power']=array(
'key' => 'post_power',
'value' =>0,
);
$args['no_found_rows']=true;
$args['ignore_sticky_posts']=1;
query_posts($args);
if(have_posts()){
$i=1;
while (have_posts()) : the_post();
$video_post_id=get_the_ID();
$video_title=get_the_title();
$video_url=get_post_meta($video_post_id,'video_url',true);
if($i==1){
$first='class="on"';
$first_video_url=$video_url;
$first_video_img=jinsom_video_cover($video_post_id);
}else{
$first='';
}
if($video_title==''){$video_title=get_the_content();}
echo '<li '.$first.'><div onclick=\'jinsom_banner_video_play("'.$video_url.'",this)\' class="shadow"></div><img loading="lazy" src="'.jinsom_video_cover($video_post_id).'" alt="'.strip_tags($video_title).'"><p><a href="'.get_the_permalink().'" target="_blank">'.strip_tags($video_title).'</a></p></li>';
$i++;
endwhile;
}

}

$extend = pathinfo($first_video_url);
$extend = strtolower($extend["extension"]);//文件后缀
if($extend=='mp4'){
$player='Player';
}else if($extend=='m3u8'){
$player='HlsJsPlayer';
}else if($extend=='flv'){
$player='FlvJsPlayer';
}else{
$player='Player';	
}
?>


</div>

</div>

<script type="text/javascript">
var jinsom_banner_video = new <?php echo $player;?>({
id: 'jinsom-video-banner',
url: '<?php echo $first_video_url;?>',
<?php if($video_data['jinsom_video_autoplay_on_off']){?>
autoplay: true,
<?php }?>
height: 365,
enterLogo:{
url: jinsom.video_logo,
width: 120,
height: 50
},
poster: '<?php echo $first_video_img;?>'
});

function jinsom_banner_video_play(url,obj){
$(obj).parent().addClass('on').siblings().removeClass('on');
$('#jinsom-video-banner').empty();
video_type=jinsom_video_type(url);
new window[video_type]({
id: 'jinsom-video-banner',
url: url,
height: 365,
autoplay: true,
playbackRate: [0.5,1,1.5,2,6],
});
}
</script>




<?php 
if($jinsom_video_special_add){
foreach ($jinsom_video_special_add as $data) {
if($data['jinsom_video_special_add_type']=='a'){
?>
<div class="jinsom-video-box">
<div class="header clear">
<div class="title">
<div class="text"><?php echo $data['title'];?></div>
<?php 
if($data['data_add']&&count($data['data_add'])>1){
echo '<div class="subtitle">';
$i=1;
foreach ($data['data_add'] as $sub_data){
if($i==1){$on='class="on"';}else{$on='';}
echo '<span topics="'.$sub_data['topics'].'" '.$on.' number="'.$data['number'].'">'.$sub_data['subtitle'].'</span>';
$i++;
}
echo '</div>';
}
?>
</div>
<?php if($data['more']){?>
<div class="more"><a href="<?php echo $data['more'];?>" target="_blank"><?php _e('更多','jinsom');?><i class="jinsom-icon jinsom-bangzhujinru"></i></a></div>
<?php }?>
</div>
<div class="content clear">
<?php 
$topic_arr=explode(",",$data['data_add'][0]['topics']);
$args = array(
'post_status' => 'publish',
'meta_key' => 'post_type',
'meta_value'=>'video',
'showposts' => $data['number'],
);
if($data['data_add'][0]['topics']){
$args['tag__in']=$topic_arr;
}
$args['no_found_rows']=true;
$args['ignore_sticky_posts']=1;
query_posts($args);
if(have_posts()){
while (have_posts()) : the_post();
$video_post_id=get_the_ID();
$video_author_id=get_the_author_meta('ID');
$video_title=get_the_title();
if($video_title==''){$video_title=get_the_content();}
$video_post_views=(int)get_post_meta($video_post_id,'post_views',true);
$video_time=get_post_meta($video_post_id,'video_time',true);
//if($video_time){
//$video_time='<span class="topic">'.jinsom_secToTime($video_time).'</span>';
//}else{
$video_time='';
//}
echo '
<li>
<a href="'.get_the_permalink().'" target="_blank">
<div class="img">
<div class="shadow"><i class="jinsom-icon jinsom-bofang2"></i></div>
<img loading="lazy" src="'.jinsom_video_cover($video_post_id).'" alt="'.$video_title.'">
'.$video_time.'
</div>
<div class="title">'.$video_title.'</div>
</a>
<div class="bottom">
<a href="'.jinsom_userlink($video_author_id).'" target="_blank">
'.jinsom_avatar($video_author_id,'30',avatar_type($video_author_id)).'
<span class="name">'.get_user_meta($video_author_id,'nickname',true).'</span>
</a>
<span><i class="jinsom-icon jinsom-fire-fill"></i>'.jinsom_views_show($video_post_views).'</span>
</div>
</li>';
endwhile;
}else{
echo jinsom_empty();
}
?>
</div>
</div>
<?php }else{
echo do_shortcode($data['ad']);
}
}
}?>

</div>
</div>

<script type="text/javascript">
$('.jinsom-video-box .header .title .subtitle span').click(function(){
post_list=$(this).parents('.header').next();
$(this).addClass('on').siblings().removeClass('on');
post_list.prepend(jinsom.loading_post);
topic=$(this).attr('topics');
number=$(this).attr('number');
$.ajax({
type: "POST",
url:  jinsom.jinsom_ajax_url+"/data/video-special.php",
data: {topic:topic,number:number},
success: function(msg){
post_list.html(msg);
}
});


});
</script>


<?php get_footer();?>

<?php }?>
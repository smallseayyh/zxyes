<?php
/*
Template Name:文章/帖子专题
*/
if(wp_is_mobile()){
require(get_template_directory().'/mobile/index.php');	
}else{
get_header();


$post_id=get_the_ID();
$user_id = $current_user->ID;
$single_data=get_post_meta($post_id,'single_show_page_data',true);

if(!$single_data){
echo jinsom_empty('请在新建页面的时候配置当前页面的数据！');exit();
}


$jinsom_single_special_slider_on_off=$single_data['jinsom_single_special_slider_on_off'];
$jinsom_single_special_slider_add=$single_data['jinsom_single_special_slider_add'];
$custom_sidebar=$single_data['jinsom_single_special_sidebar'];//侧栏
$style=$single_data['jinsom_single_special_slider_style'];//幻灯片类型
$width=$single_data['jinsom_single_special_slider_width'];//幻灯片宽度
$height=$single_data['jinsom_single_special_slider_height'];//幻灯片宽度
$space=$single_data['jinsom_single_special_slider_box_space'];//幻灯片间隙
$effect=$single_data['jinsom_single_special_slider_effect'];//动画类型


if($style=='eight'){
$commend_number=6;	
}else if($style=='one'||$style=='ten'){
$commend_number=4;
}else if($style=='two'||$style=='four'||$style=='five'||$style=='seven'){
$commend_number=3;	
}else if($style=='nine'){
$commend_number=2;	
}else if($style=='three'){
$commend_number=1;	
}
?>
<style type="text/css">
.jinsom-single-special-slider {
    width: <?php echo $width;?>%;
}
.jinsom-single-special-header-content {
    height: <?php echo $height;?>px !important;
}
.jinsom-single-special-header.one .right li,.jinsom-single-special-header.four .right li,.jinsom-single-special-header.five .right li {
    width: calc((100% - <?php echo $space;?>px)/2);
    height: calc((100% - <?php echo $space;?>px)/2);
    margin-bottom: <?php echo $space;?>px;
}
.jinsom-single-special-header.one .right li:nth-child(2n-1),.jinsom-single-special-header.four .right li:nth-child(2),.jinsom-single-special-header.five .right li:first-child,.jinsom-single-special-header.nine .right li:first-child,.jinsom-single-special-header.ten .ten-list li,.jinsom-single-special-slider,.jinsom-single-special-header.eight .right li:nth-child(2n-1) {
    margin-right: <?php echo $space;?>px;
}

.jinsom-single-special-header.two .right li {
    height: calc((100% - <?php echo $space;?>px)/2);
}
.jinsom-single-special-header.two .right {
    column-gap: <?php echo $space;?>px;
}
.jinsom-single-special-header.two .right li:first-child {
    margin-bottom: <?php echo $space;?>px;
}
.jinsom-single-special-header.seven .right li{
    height: calc((100% - <?php echo $space;?>px * 2)/3);
    margin-bottom: <?php echo $space;?>px;
}
.jinsom-single-special-header.eight .right li {
    width: calc((100% - <?php echo $space;?>px)/2);
    height: calc((100% - <?php echo $space;?>px * 2)/3);
    margin-bottom: <?php echo $space;?>px;
}
.jinsom-single-special-header.nine .right li {
    width: calc((100% - <?php echo $space;?>px)/2);
}
</style>

<div class="jinsom-single-special-content">

<?php echo $single_data['jinsom_single_special_header_html'];?>

<?php if(($jinsom_single_special_slider_on_off&&$style=='six')||($style!='six')){?>

<div class="jinsom-single-special-header <?php echo $style;?>">
<div class="jinsom-single-special-header-content">

<?php if($jinsom_single_special_slider_on_off&&$jinsom_single_special_slider_add){?>
<div class="jinsom-single-special-slider">
<div class="swiper-wrapper">
<?php 
foreach ($jinsom_single_special_slider_add as $slider) {
echo '<a href="'.$slider['link'].'" target="_blank" class="swiper-slide" style="background-image: url('.$slider['images'].');">';
if($slider['title']){
echo '<h2><i></i>'.$slider['title'].'</h2>';  
}
echo '</a>';
}

?>
</div>
<div class="swiper-pagination"></div>
<?php if($single_data['jinsom_single_special_slider_navigation']){?>
<div class="swiper-button-next jinsom-icon jinsom-arrow-right"></div>
<div class="swiper-button-prev jinsom-icon jinsom-arrow-left"></div>
<?php }?>
</div>
<?php }?>


<?php 
$header_commend=$single_data['jinsom_single_special_header_commend'];
$html='';
if($header_commend!='custom'&&$header_commend!='set'){
$date_query=array(
array(
'column' => 'post_date',
'before' => date('Y-m-d',time()+3600*24),
'after' =>date('Y-m-d',time()-3600*24*30)
)
);
if($header_commend=='new'){
$args = array(
'post_status' => 'publish',
'showposts' => $commend_number,
'meta_key' => 'post_type',
'meta_value'=>'single',
);
}else if($header_commend=='new-bbs'){
$args = array(
'post_status' => 'publish',
'showposts' => $commend_number,
'post_parent'=>999999999,
);
}else if($header_commend=='comment'){
$args = array(
'post_status' => 'publish',
'showposts' => $commend_number,
'meta_key' => 'post_type',
'meta_value'=>'single',
'orderby'=>'comment_count',
'date_query' => $date_query,
);
}else if($header_commend=='comment-bbs'){
$args = array(
'post_status' => 'publish',
'showposts' => $commend_number,
'post_parent'=>999999999,
'orderby'=>'comment_count',
'date_query' => $date_query,
);
}else if($header_commend=='views'){
$args = array(
'post_status' => 'publish',
'showposts' => $commend_number,
'meta_key' => 'post_views',
'meta_value_num' => 10,
'meta_compare' => '>=',
'orderby' => 'rand',
'date_query' => $date_query,
'meta_query' => array(
array(
'key' => 'post_type',
'value' =>'single',
)
)
);
}else if($header_commend=='views-bbs'){
$args = array(
'post_status' => 'publish',
'showposts' => $commend_number,
'meta_key' => 'post_views',
'meta_value_num' => 10,
'meta_compare' => '>=',
'orderby' => 'rand',
'date_query' => $date_query,
'post_parent'=>999999999,
);
}
$args['no_found_rows']=true;
$args['ignore_sticky_posts']=1;
query_posts($args);
if(have_posts()){
while (have_posts()):the_post();
if($single_data['jinsom_single_special_header_commend_title']){
$title='<p>'.get_the_title().'</p>';
}else{
$title='';
}
$html.='<li class="opacity"><a href="'.get_the_permalink().'" target="_blank"><img loading="lazy" src="'.jinsom_single_cover(get_the_content()).'" alt="'.strip_tags($title).'">'.$title.'</a></li>';
endwhile;
}
}else if($header_commend=='custom'){
$single_special_header_commend_custom=$single_data['jinsom_single_special_header_commend_custom'];
$single_special_header_commend_custom_arr=explode(",",$single_special_header_commend_custom);
if($single_special_header_commend_custom){
$i=1;
foreach ($single_special_header_commend_custom_arr as $data){
if($i>$commend_number){break;}
if($single_data['jinsom_single_special_header_commend_title']){
$title='<p>'.get_the_title($data).'</p>';
}else{
$title='';
}
$html.='<li class="opacity"><a href="'.get_the_permalink($data).'" target="_blank"><img loading="lazy" src="'.jinsom_single_cover(jinsom_get_post_content($data)).'" alt="'.strip_tags($title).'">'.$title.'</a></li>';
$i++;
}
}
}else if($header_commend=='set'){
$commend_set_data=$single_data['jinsom_single_special_header_commend_set'];
if($commend_set_data){
$i=1;
foreach ($commend_set_data as $data) {
if($i>$commend_number){break;}
if($data['title']){
$title='<p>'.$data['title'].'</p>';
}else{
$title='';
}
$html.='<li class="opacity"><a href="'.$data['link'].'" target="_blank"><img loading="lazy" src="'.$data['images'].'" alt="'.strip_tags($title).'">'.$title.'</a></li>';
$i++;
}
}
}

?>


<?php if($style=='one'){?>
<div class="right">
<?php echo $html;?>
</div>
<?php }else if($style=='two'){?>
<div class="right">
<?php echo $html;?>
</div>
<?php }else if($style=='three'){?>
<div class="right">
<?php echo $html;?>
</div>
<?php }else if($style=='four'||$style=='five'||$style=='seven'){?>
<div class="right">
<?php echo $html;?>
</div>
<?php }else if($style=='eight'){?>
<div class="right">
<?php echo $html;?>
</div>
<?php }else if($style=='nine'){?>
<div class="right">
<?php echo $html;?>
</div>
<?php }?>

</div>

<?php if($style=='ten'){?>
<div class="ten-list">
<?php echo $html;?>
</div>
<?php }?>


</div>

<?php }  //样式6  且关闭幻灯片?>

<div class="jinsom-main-content clear">
<div class="jinsom-content-left <?php if($custom_sidebar=='no'){echo 'full';}?>">

<?php 
$jinsom_single_special_content_data_add=$single_data['jinsom_single_special_content_data_add'];
if($jinsom_single_special_content_data_add){
foreach ($jinsom_single_special_content_data_add as $data) {
if($data['jinsom_single_special_content_data_add_type']=='content'){

echo '<div class="jinsom-single-special-box">';

//头部区域
if($data['jinsom_single_special_module_menu_style']!='one'){//显示头部

if($data['more_btn_on_off']){
$more_btn='<div class="more"><a href="'.$data['more_btn_link'].'" target="_blank">更多 >></a></div>';
}else{
$more_btn='';  
}

echo '<div class="jinsom-single-special-box-header clear '.$data['jinsom_single_special_module_menu_style'].'" number="'.$data['number'].'" style="'.$data['content_style'].'">';
if($data['jinsom_single_special_module_menu_style']=='two'&&$data['header_title']!=''){//显示文本标题
echo '<div class="text">'.$data['header_title'].$more_btn.'</div>';
}else{//显示子菜单

if($data['add']){
$i=1;
foreach ($data['add'] as $adds){
if($i==1){$on='on';}else{$on='';}
if($adds['jinsom_single_special_module_menu_data']=='one'){
if($i==1){
$data_type='menu_topic';
$data_str=$adds['topic'];
}
echo '<li type="topic" data="'.$adds['topic'].'" class="'.$on.'">'.$adds['title'].'</li>';
}else{
if($i==1){
$data_type='menu_bbs';
$data_str=$adds['bbs'];
}
echo '<li type="bbs" data="'.$adds['bbs'].'" class="'.$on.'">'.$adds['title'].'</li>';
}
$i++;
}//foreach
}

echo $more_btn;

}//else
echo '</div>';
}

//内容区域
echo '<div class="jinsom-single-special-box-content clear '.$data['content_style'].'">';

if($data['jinsom_single_special_module_menu_style']=='one'||$data['jinsom_single_special_module_menu_style']=='two'){
$data_type=$data['jinsom_single_special_module_no_menu_data'];
$data_str='';
if($data['jinsom_single_special_module_no_menu_data']=='no_menu_topic'){
$data_str=$data['no_menu_data_topic'];
}
if($data['jinsom_single_special_module_no_menu_data']=='no_menu_bbs'){
$data_str=$data['no_menu_data_bbs'];
}

}

if($data_type=='menu_topic'||$data_type=='no_menu_topic'||$data_type=='menu_bbs'||$data_type=='no_menu_bbs'){
$data_str_arr=explode(",",$data_str);
if($data_type=='menu_topic'||$data_type=='no_menu_topic'){
$args = array(
'post_status' => 'publish',
'showposts' => $data['number'],
'meta_key' => 'post_type',
'meta_value'=>'single',
);

if($data_str){
$args['tag__in']=$data_str_arr;
}

}else{
$args = array(
'post_status' => 'publish',
'showposts' => $data['number'],
'cat' =>$data_str_arr,
'post_parent'=>999999999,
);

if($data_str){
$args['cat']=$data_str_arr;
}

}
}else if($data_type=='commend_single'){//推荐的文章
$args = array(
'post_status' => 'publish',
'showposts' => $data['number'],
'meta_key' => 'post_type',
'meta_value'=>'single',
'meta_query' => array(
array(
'key' => 'jinsom_commend',
)
),
);
}else if($data_type=='nice_bbs'){//加精的帖子
$args = array(
'post_status' => 'publish',
'showposts' => $data['number'],
'meta_key' => 'jinsom_commend',
'post_parent'=>999999999,
);
}else if($data_type=='new_single'){//最新的文章
$args = array(
'post_status' => 'publish',
'showposts' => $data['number'],
'meta_key' => 'post_type',
'meta_value'=>'single',
);
}else if($data_type=='new_bbs'){//最新的帖子
$args = array(
'post_status' => 'publish',
'showposts' => $data['number'],
'post_parent'=>999999999,
);
}


$args['no_found_rows']=true;
$args['ignore_sticky_posts']=1;
query_posts($args);
if(have_posts()){
while (have_posts()):the_post();
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');


if($data['content_style']=='one'){
$topics=wp_get_post_tags(get_the_ID());
$i=1;
$topic_html='';
if($topics){
foreach($topics as $topic){
if($i<=3){
$topic_html.='<a href="'.get_tag_link($topic->term_id).'" title="'.$topic->name.'" target="_blank">'.$topic->name.'</a>';
}
$i++;
}
}

echo '
<li>
<a href="'.get_the_permalink().'" target="_blank" class="bg">
<img loading="lazy" src="'.jinsom_single_cover(get_the_content()).'" alt="'.strip_tags(get_the_title()).'">
</a>
<div class="bottom">
<div class="title">'.get_the_title().'</div>
<div class="topic">'.$topic_html.'</div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</div>
</li>';


}else if($data['content_style']=='two'){
$topics=wp_get_post_tags(get_the_ID());
$i=1;
$topic_html='';
if($topics){
foreach($topics as $topic){
if($i<=3){
$topic_html.='<a href="'.get_tag_link($topic->term_id).'" title="'.$topic->name.'" target="_blank">'.$topic->name.'</a>';
}
$i++;
}
}

echo '
<li>
<a href="'.get_the_permalink().'" target="_blank" class="bg">
<img loading="lazy" src="'.jinsom_single_cover(get_the_content()).'" alt="'.strip_tags(get_the_title()).'">
</a>
<div class="bottom">
<div class="title">'.get_the_title().'</div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</div>
</li>';


}else{

preg_match_all('/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is',get_the_content(),$result);
$single_img_number=count($result[1]);

if($single_img_number<=1){
$content=strip_tags(get_the_content());
$content = preg_replace("/\[file[^]]+\]/", "[".__('附件','jinsom')."]",$content);
$content = preg_replace("/\[video[^]]+\]/", "[".__('视频','jinsom')."]",$content);
$content = preg_replace("/\[music[^]]+\]/", "[".__('音乐','jinsom')."]",$content);
$content=preg_replace('/\s(?=\s)/','',$content);
$single_excerp_max_words = (int)jinsom_get_option('jinsom_publish_single_excerp_max_words');//文章摘要字数
}

if($single_img_number>=2){

echo '<li class="b">
<div class="title">
<a href="'.get_the_permalink().'" target="_blank">'.get_the_title().'</a>
</div>
<div class="cover">'.jinsom_get_single_img(get_the_content(),$post_id).'</div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</li>';  


}else if($single_img_number==1){//只有一图

echo '<li class="a">
<a href="'.get_the_permalink().'" target="_blank" class="bg">
<img loading="lazy" src="'.jinsom_single_cover(get_the_content()).'" alt="'.strip_tags(get_the_title()).'">
</a>
<div class="right">
<div class="title"><a href="'.get_the_permalink().'" target="_blank">'.get_the_title().'</a></div>
<div class="desc">
<a href="'.get_the_permalink().'" target="_blank">'.convert_smilies(mb_substr($content,0,$single_excerp_max_words,'utf-8')).'...</a>
</div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</div>
</li>';


}else{

echo '<li class="c">
<div class="title">
<a href="'.get_the_permalink().'" target="_blank">'.get_the_title().'</a>
</div>
<div class="desc"><a href="'.get_the_permalink().'" target="_blank">'.convert_smilies(mb_substr($content,0,$single_excerp_max_words,'utf-8')).'...</a></div>
<div class="info">
<a href="'.jinsom_userlink($author_id).'" target="_blank">
'.jinsom_avatar($author_id,'30',avatar_type($author_id)).'
<span class="name">'.get_user_meta($author_id,'nickname',true).'</span>
</a>
<span class="time">'.jinsom_timeago(get_the_time('Y-m-d G:i:s')).'</span>
</div>
</li>';  


}




}





endwhile;
}else{
echo jinsom_empty();
}



echo '</div>';//内容区域
echo '</div>';

}else if($data['jinsom_single_special_content_data_add_type']=='user'){
echo '<div class="jinsom-single-special-box">';
if($data['header_title']!=''){
echo '<div class="jinsom-single-special-box-header clear user"><div class="text">'.$data['header_title'].'</div></div>';
}

echo '<div class="jinsom-single-special-box-content clear user">';

if($data['jinsom_single_special_content_user_data']=='new'){//最新注册用户
$user_query = new WP_User_Query( array ( 
'orderby' => 'registered', 
'order' => 'DESC',
'count_total'=>false,
'number' => $data['number']
));
}else if($data['jinsom_single_special_content_user_data']=='verify'){//随机所有的认证用户
$user_query = new WP_User_Query( array ( 
'orderby' => 'rand', 
'count_total'=>false,
'meta_query' => array( 
array(
'key' => 'verify', 
'value' => 0, 
'type' => 'NUMERIC',
'compare' => '!=' 
)
),
'number' => $data['number']
));
}else if($data['jinsom_single_special_content_user_data']=='vip'){//随机所有vip用户
$user_query = new WP_User_Query( array ( 
'orderby' => 'rand', 
'count_total'=>false,
'meta_query' => array( 
array(
'key' => 'vip_time', 
'value' => date('Y-m-d'), 
'type' => 'DATE',
'compare' => '>' 
)
),
'number' => $data['number']
));
}else if($data['jinsom_single_special_content_user_data']=='honor'){
$user_query = new WP_User_Query( array ( 
'orderby' => 'rand', 
'count_total'=>false,
'meta_query' => array( 
array(
'key' => 'user_honor', 
'value' => ' ', 
'compare' => '!=' 
)
),
'number' => $data['number']
));
}else if($data['jinsom_single_special_content_user_data']=='custom'){
$custom_data=$data['jinsom_single_special_content_user_data_ids'];
$custom_data_arr=explode(",",$custom_data);
$user_query = new WP_User_Query( array ( 
'include' => $custom_data_arr, 
'orderby' => 'rand',
'count_total'=>false,
'number' => $data['number']
));
}else{//随机所有用户
$user_query = new WP_User_Query( array ( 
'orderby' => 'rand', 
'count_total'=>false,
'number' => $data['number']
));
}

if (!empty($user_query->results)){
foreach ($user_query->results as $user){
$description =get_user_meta($user->ID,'description',true);
if($description==''){$description=jinsom_get_option('jinsom_user_default_desc_b');}
$bg=jinsom_member_bg($user->ID,'small_img');

echo '<li>
'.jinsom_follow_button_home($user->ID).'
<div class="bg" style="background-image: url('.$bg.');"><a href="'.jinsom_userlink($user->ID).'" target="_blank">'.jinsom_avatar($user->ID,'30',avatar_type($user->ID)).jinsom_verify($user->ID).'</a></div>
<div class="info">
<div class="name">'.jinsom_nickname_link($user->ID).jinsom_vip($user->ID).'</div>
<div class="desc">'.$description.'</div>
<div class="number">
<span>'.__('粉丝','jinsom').'<i>'.jinsom_follower_count($user->ID).'</i></span>
<span>'.__('关注','jinsom').'<i>'.jinsom_following_count($user->ID).'</i></span>
</div>
</div>
</li>
';
}
}

echo '</div>';

echo '</div>';
}else{
echo '<div>'.do_shortcode($data['html']).'</div>';
}


}//第一个foreach










}




?>














</div>
<?php 
if($custom_sidebar!='no'){
require(get_template_directory().'/sidebar/sidebar-custom.php');//引入右侧栏
}?>
</div>
</div>
<?php get_footer();?>


<script>
<?php if($jinsom_single_special_slider_on_off){?>
var swiper = new Swiper('.jinsom-single-special-slider', {
<?php if($single_data['jinsom_single_special_slider_pagination']){?>
pagination: {
el: '.swiper-pagination',
clickable :true,
},
<?php }?>
<?php if($single_data['jinsom_single_special_slider_navigation']){?>
navigation: {
nextEl: '.swiper-button-next',
prevEl: '.swiper-button-prev',
},
<?php }?>
paginationClickable: true,
centeredSlides: true,
<?php if($single_data['jinsom_single_special_slider_autoplay']){?>
autoplay:true,
<?php }?>
loop:true,
autoplayDisableOnInteraction: true,//当用户操作之后，则停止
effect: '<?php echo $effect;?>',
cube: {
slideShadows: false,
shadow: false,
shadowOffset: 20,
shadowScale: 0.94
}
});

<?php }?>


//菜单切换
$('.jinsom-single-special-box-header li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
data_type=$(this).attr('type');
data=$(this).attr('data');
number=$(this).parent().attr('number');
style=$(this).parent().attr('style');
this_dom=$(this);
this_dom.parent().next().html(jinsom.loading);
$.ajax({
type: "POST",
url:  jinsom.jinsom_ajax_url+"/data/single.php",
data: {data_type:data_type,data:data,number:number,style:style},
success: function(msg){
this_dom.parent().next().html(msg);
}
});


});
</script>


<?php }

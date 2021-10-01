<?php 
//获取发现页面的数据
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();




$jinsom_mobile_find_add=jinsom_get_option('jinsom_mobile_find_add');
if($jinsom_mobile_find_add){

$i=1;
foreach ($jinsom_mobile_find_add as $find_data) {
$find_type=$find_data['jinsom_mobile_find_add_type'];
if($find_data['more_text']){
$more='<a href="'.do_shortcode($find_data['more_link']).'" class="link">'.$find_data['more_text'].'</a>';
}else{
$more='';
}

if($find_type=='slider'&&$find_data['slider_add']){//幻灯片


echo '<div class="jinsom-find-box"><div class="jinsom-find-slider"><div style="height:'.$find_data['slider_height'].'vw;" class="jinsom-mobile-slider owl-carousel" id="jinsom-find-slider-'.$i.'">';

foreach ($find_data['slider_add'] as $data) {
if($data['app']){
$class='class="link"';	
}else{
$class='';	
}
$desc=$data['desc'];
if($desc){
$desc='<p>'.$desc.'</p>';
}else{
$desc='';
}

echo '<li class="item"><a href="'.do_shortcode($data['link']).'" '.$class.' style="background-image:url('.$data['images'].');height:'.$find_data['slider_height'].'vw;">'.$desc.'</a></li>';		
}
echo '</div></div></div>';

if($find_data['slider_auto']){
$slider_auto='autoplay:true,autoplayTimeout:5000,';
}else{
$slider_auto='';
}

echo '
<script type="text/javascript">
$("#jinsom-find-slider-'.$i.'").owlCarousel({
items: 1,'.$slider_auto.'
margin:15,
loop: true,
});
</script>';

}else if($find_type=='menu'&&$find_data['menu_add']){//格子菜单

echo '<div class="jinsom-find-box">';
if($find_data['title']){
echo '<div class="header"><span>'.$find_data['title'].'</span>'.$more.'</div>';
}
echo '<div class="content clear"><div class="jinsom-find-menu jinsom-find-menu-'.$i.' clear">';
foreach ($find_data['menu_add'] as $data){
if($data['app']){
$class='class="link"';
}else{
$class='';	
}

if($data['login']){
if(is_user_logged_in()){
echo '<li><a href="'.do_shortcode($data['link']).'" '.$class.' target="_blank"><img src="'.$data['images'].'"><p>'.$data['name'].'</p></a></li>';
}
}else{
echo '<li><a href="'.do_shortcode($data['link']).'" '.$class.' target="_blank"><img src="'.$data['images'].'"><p>'.$data['name'].'</p></a></li>';
}
}
echo '</div></div></div>';

}else if($find_type=='topic'){//话题展示
$find_topic_type=$find_data['find_topic_type'];
$args=array(
'number'=>$find_data['number'],
'taxonomy'=>'post_tag',
'hide_empty'=>false,
'order'=>'DESC'
);
if($find_topic_type=='views'){
$args['meta_key']='topic_views';
$args['orderby']='meta_value_num';
}else if($find_topic_type=='count'){
$args['orderby']='count';
}else if($find_topic_type=='custom'){
$args['include']=explode(",",$find_data['custom_topic_id']);
$args['orderby']='include';
}

$tag_arr=get_terms($args);
echo '<div class="jinsom-find-box topic"><div class="header"><span>'.$find_data['title'].'</span>'.$more.'</div><div class="content clear">';
if(!empty($tag_arr)){
$i=1;
foreach ($tag_arr as $tag) {
echo '<li><a href="'.jinsom_mobile_topic_id_url($tag->term_id).'" class="link"><i>'.$i.'</i><span>#'.$tag->name.'</span><m>#</m></a></li>';
$i++;
}
}else{
echo jinsom_empty();
}
echo '</div></div>';
}else if($find_type=='bbs'){//论坛展示
$find_bbs_type=$find_data['find_bbs_type'];
if($find_bbs_type=='commend'){
$bbs_ids=jinsom_get_option('jinsom_bbs_commend_list');
}else{
$bbs_ids=$find_data['custom_bbs_id'];
}

if($bbs_ids){
$bbs_id_arr=explode(",",$bbs_ids);

echo '<div class="jinsom-find-box bbs '.$find_data['style'].'"><div class="header"><span>'.$find_data['title'].'</span>'.$more.'</div><div class="content clear">';
foreach ($bbs_id_arr as $data) {

if($find_data['style']=='a'||$find_data['style']=='b'){
echo '
<li>
<a href="'.jinsom_mobile_bbs_url($data).'" class="link">
<div class="img">
'.jinsom_get_bbs_avatar($data,0).'
</div>
<div class="name">'.get_category($data)->name.'</div>
</a>
</li>';
}else if($find_data['style']=='c'||$find_data['style']=='d'){
echo '
<li>
<a href="'.jinsom_mobile_bbs_url($data).'" class="link">
<div class="img">
'.jinsom_get_bbs_avatar($data,0).'
</div>
<div class="name"><p>'.get_category($data)->name.'</p><p>'.strip_tags(get_term_meta($data,'desc',true)).'</p></div>
</a>
'.jinsom_bbs_like_btn($user_id,$data).'
</li>';	
}


}
echo '</div></div>';
}
}else if($find_type=='user'){//用户展示
$find_user_type=$find_data['find_user_type'];
$args=array( 
'order' => 'DESC',
'count_total'=>false,
'number' => $find_data['number']
);

if($find_user_type=='online'){
$args['meta_key']='latest_login';
$args['orderby']='meta_value';
}else if($find_user_type=='vip'){
$args['meta_query']['usermeta']=array(
'key' => 'vip_time', 
'value' => date('Y-m-d'), 
'type' => 'DATE',
'compare' => '>' 
);
$args['orderby']='rand';
}else if($find_user_type=='verify'){
$args['meta_query']['usermeta']=array(
'key' => 'verify', 
'value' => 0, 
'type' => 'NUMERIC',
'compare' => '!=' 
);	
$args['orderby']='rand';
}else if($find_user_type=='rand'){
$args['orderby']='rand';
}else if($find_user_type=='new'){
$args['orderby']='ID';
}else if($find_user_type=='custom'){
$args['include']=explode(",",$find_data['custom_user_id']);
$args['orderby']='include';
$args['order']='ASC';
}

$user_query = new WP_User_Query($args);
if (!empty($user_query->results)){
echo '<div class="jinsom-find-box user '.$find_data['style'].'"><div class="header"><span>'.$find_data['title'].'</span>'.$more.'</div><div class="content clear">';
foreach ($user_query->results as $user){

if($find_user_type=='online'){
$desc=jinsom_timeago(get_user_meta($user->ID,'latest_login',true)).'<v>（'.jinsom_get_online_type($user->ID).'）</v>';
}else if($find_user_type=='new'){
$user_info=get_userdata($user->ID);
$desc=jinsom_timeago($user_info->user_registered);
}else{
$desc=get_user_meta($user->ID,'description',true);
}

if($find_data['style']=='a'){
echo '<li><a href="'.jinsom_mobile_author_url($user->ID).'" class="link">'.jinsom_avatar($user->ID,'60',avatar_type($user->ID)).jinsom_verify($user->ID).'</a></li>';
}else{

if($find_data['style']=='c'||$find_data['style']=='d'){
$follow=jinsom_mobile_follower_list_button($user_id,$user->ID);
}else{
$follow='';	
}

echo '<li><a href="'.jinsom_mobile_author_url($user->ID).'" class="link"><div class="img">'.jinsom_avatar($user->ID,'60',avatar_type($user->ID)).jinsom_verify($user->ID).'</div><div class="info"><div class="name">'.jinsom_nickname($user->ID).'</div><div class="desc">'.$desc.'</div></div></a>'.$follow.'</li>';
}

}
echo '</div></div>';
}

}else if($find_type=='html'){//自定义
echo do_shortcode($find_data['html']);
}

$i++;
}//foreach find
}else{
echo jinsom_empty('请到后台-移动设置-发现页面-添加模块');
}





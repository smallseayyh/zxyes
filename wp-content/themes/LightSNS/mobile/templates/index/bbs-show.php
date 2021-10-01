<?php 
$bbs_data=get_post_meta($page_id,'bbs_show_page_data',true);
if($bbs_data){
$jinsom_bbs_show_add=$bbs_data['jinsom_bbs_show_add'];
$jinsom_bbs_header=$bbs_data['jinsom_bbs_header'];
$jinsom_bbs_hide_arr=jinsom_get_option('jinsom_bbs_show_hide');//论坛大厅隐藏的板块
$args = array(
'post_status' =>'publish',
'showposts' => $jinsom_bbs_header['mobile_list_number'],
'post_parent'=>999999999,
'ignore_sticky_posts' => 1,
'no_found_rows'=>true,
'category__not_in'=>$jinsom_bbs_hide_arr
);

//过滤数据
$jinsom_bbs_list_data_type_bbs_id=$jinsom_bbs_header['jinsom_bbs_list_data_type_bbs_id'];
if($jinsom_bbs_header['jinsom_bbs_list_data_type']=='b'&&$jinsom_bbs_list_data_type_bbs_id){
$args['cat']=$jinsom_bbs_list_data_type_bbs_id;
}else{
$args['cat']='';
}

?>

<style type="text/css">
#jinsom-bbs-slider,#jinsom-bbs-slider .item a{height:<?php echo $jinsom_bbs_header['jinsom_mobile_bbs_slider_height'];?>vw;}
</style>

<?php 
$jinsom_bbs_slider_add = $jinsom_bbs_header['jinsom_mobile_bbs_slider_add'];
if($jinsom_bbs_slider_add&&$jinsom_bbs_header['jinsom_mobile_bbs_slider_on_off']){
echo '<div class="jinsom-find-box"><div class="jinsom-mobile-slider owl-carousel" id="jinsom-bbs-slider">';
foreach ($jinsom_bbs_slider_add as $bbs_slider) {
if($bbs_slider['jinsom_mobile_bbs_slider_add_app']){
$class='class="link"';	
}else{
$class='';	
}
if(!$bbs_slider['jinsom_mobile_bbs_slider_add_app']&&$bbs_slider['target']){
$target='target="_blank"';
}else{
$target='';	
}
$desc=$bbs_slider['desc'];
if($desc){
$desc='<p>'.$desc.'</p>';
}else{
$desc='';
}

echo '<li class="item"><a href="'.do_shortcode($bbs_slider['link']).'" '.$class.' '.$target.' style="background-image:url('.$bbs_slider['images'].')">'.$desc.'</a></li>';
}
echo '</div></div>';
}
echo do_shortcode($bbs_data['jinsom_mobile_bbs_slider_bottom_ad']);//幻灯片底部广告
?>

<?php 
$enabled=$jinsom_bbs_header['list']['enabled'];
if($jinsom_bbs_header['jinsom_bbs_list_on_off']&&$enabled){?>
<div class="jinsom-find-box post">
<div class="jinsom-bbs-tab-post-header">
<?php 
foreach($enabled as $x=>$x_value){
switch($x){
case 'new': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('最新','jinsom').'</li>';  
break;
case 'hot': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('热门','jinsom').'</li>';  
break;
case 'nice': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('精品','jinsom').'</li>';  
break;
case 'pay': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('付费','jinsom').'</li>';  
break;
case 'answer': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('问答','jinsom').'</li>';  
break;
case 'activity': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('活动','jinsom').'</li>';  
break;
case 'vote': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.__('投票','jinsom').'</li>';  
break;
case 'custom_1': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.$jinsom_bbs_header['custom_a_name'].'</li>';  
break;
case 'custom_2': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.$jinsom_bbs_header['custom_b_name'].'</li>';  
break;
case 'custom_3': 
if(key($enabled)==$x){$class='class="on"';}else{$class='';}
echo '<li type="'.$x.'" '.$class.'>'.$jinsom_bbs_header['custom_c_name'].'</li>';  
break;
}}
?>
</div>	
<div class="jinsom-bbs-tab-post-content">

<?php

$date_query=array(
array(
'column' => 'post_date',
'before' => date('Y-m-d',time()+3600*24),
'after' =>date('Y-m-d',time()-3600*24*30)
)
);

foreach($enabled as $x=>$x_value){
switch($x){
case 'new':
$args['meta_key']='';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'nice':
$args['orderby']='date';
$args['meta_key']='jinsom_commend';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'hot':
$args['meta_key']='';
$args['orderby']='comment_count';
$args['date_query']=$date_query;
require($require_url.'/page/layout-bbs-list.php');
break;
case 'pay':
$args['meta_key']='post_price';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'answer':
$args['meta_key']='answer_number';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'activity':
$args['meta_key']='activity_data';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'vote':
$args['meta_key']='vote_data';
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'custom_1':
$args['meta_key']='';
$args['cat']=$jinsom_bbs_header['custom_bbs_a'];
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'custom_2':
$args['meta_key']='';
$args['cat']=$jinsom_bbs_header['custom_bbs_b'];
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
case 'custom_3':
$args['meta_key']='';
$args['cat']=$jinsom_bbs_header['custom_bbs_c'];
$args['orderby']='date';
$args['date_query']='';
require($require_url.'/page/layout-bbs-list.php');
break;
}}
?>


</div>
</div>

<?php }?>




<?php 
if($jinsom_bbs_show_add){
foreach ($jinsom_bbs_show_add as $jinsom_bbs_show_adds) {
$type=$jinsom_bbs_show_adds['jinsom_bbs_show_add_type'];
$title=$jinsom_bbs_show_adds['title'];
$bbs_txt=$jinsom_bbs_show_adds['bbs'];
$bbs_arr=explode(",",$bbs_txt);
if($type=='a'){
?>

<div class="jinsom-find-box bbs a">
<div class="header">
<span><?php echo $title;?></span>	
<?php 
if($jinsom_bbs_show_adds['mobile_more_link']){
echo '<a href="'.do_shortcode($jinsom_bbs_show_adds['mobile_more_link']).'" class="link">'.$jinsom_bbs_show_adds['more_name'].'</a>';
}
?>
</div>
<div class="content clear">
<?php 
foreach ($bbs_arr as $bbs_id) {
$category=get_category($bbs_id);
$cat_parents=$category->parent;
?>
<li>
<a href="<?php echo jinsom_mobile_bbs_url($bbs_id);?>" class="link">
<div class="img">
<?php echo jinsom_get_bbs_avatar($bbs_id,0);?>
</div>	
<div class="name"><?php echo get_category($bbs_id)->name;?></div>
</a>	
</li>

<?php }?>



</div>
</div>

<?php 
}else{//自定义html代码
echo '<div class="jinsom-bbs-show-ad">'.do_shortcode($jinsom_bbs_show_adds['ad_mobile']).'</div>';
}



}}


}else{
echo '请在新建页面的时候配置当前页面的数据！';
}


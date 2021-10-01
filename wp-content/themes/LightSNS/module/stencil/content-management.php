<?php
//内容管理
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!$user_id){exit();}

$args_all = array( 
'post_status' => array('publish','pending','draft'),
'showposts' => 8,
'no_found_rows'=>true,
'ignore_sticky_posts'=>1
);

$args_pending = array( 
'post_status' => array('pending'),
'showposts' => 8,
'no_found_rows'=>true,
'ignore_sticky_posts'=>1
);

$args_draft = array( 
'post_status' => array('draft'),
'showposts' => 8,
'no_found_rows'=>true,
'ignore_sticky_posts'=>1
);



global $wpdb;
if(!jinsom_is_admin($user_id)&&get_user_meta($user_id,'user_power',true)!=5){
$args_all['author']=$user_id;
$args_pending['author']=$user_id;
$args_draft['author']=$user_id;

$all_number=$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts where post_status in('publish','pending','draft') and post_author=$user_id and post_type='post'");
$pending_number=$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts where post_status='pending' and post_author=$user_id and post_type='post'");
$draft_number=$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts where post_status='draft' and post_author=$user_id and post_type='post'");

}else{
$all_number=$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts where post_status in('publish','pending','draft') and post_type='post'");
$pending_number=$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts where post_status='pending' and post_type='post'");
$draft_number=$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts where post_status='draft' and post_type='post'");
}


?>
<div class="jinsom-content-management-form">
<div class="jinsom-content-management-title">
<li class="on"><?php _e('全部内容','jinsom');?></li>
<li><?php _e('审核中','jinsom');?></li>
<li><?php _e('草稿/被驳回','jinsom');?></li>
</div>
<div class="jinsom-content-management-content">

<ul>
<div class="header"><span><?php _e('标题','jinsom');?></span><span><?php _e('状态','jinsom');?></span><span><?php _e('时间','jinsom');?></span></div>
<div class="content all">
<?php 
query_posts($args_all);
if(have_posts()){
while (have_posts()):the_post();
if(get_the_title()){
$title=get_the_title();
}else{
$title=mb_substr(strip_tags(get_the_content()),0,40,'utf-8');
}
$status=get_post_status();
if($status=='publish'){
$status='<font style="color:#5fb878">'.__('已发表','jinsom').'</font>';
}else if($status=='pending'){
$status='<font style="color:#2196F3">'.__('审核中','jinsom').'</font>';	
}else{
$status='<font style="color:#f00">'.__('被驳回','jinsom').'</font>';
}

echo '<li><span onclick="jinsom_post_link(this)" data="'.get_the_permalink().'">'.$title.'</span><span>'.$status.'</span><span>'.jinsom_timeago(get_the_time('Y-m-d H:i:s')).'</span></li>';
endwhile;
}else{
echo jinsom_empty();
}
?>
</div>
<div id="jinsom-content-management-all-page" class="jinsom-mycredit-page content-management"></div>
</ul>



<ul style="display: none;">
<div class="header"><span><?php _e('标题','jinsom');?></span><span><?php _e('时间','jinsom');?></span><span><?php _e('操作','jinsom');?></span></div>
<div class="content pending">
<?php 
query_posts($args_pending);
if(have_posts()){
while (have_posts()):the_post();
$post_id=get_the_ID();
if(get_the_title()){
$title=get_the_title();
}else{
$title=mb_substr(strip_tags(get_the_content()),0,40,'utf-8');
}

if(jinsom_is_admin($user_id)||(int)get_user_meta($user_id,'user_power',true)==5){
$do='
<span>
<m onclick="jinsom_content_management_agree('.$post_id.',this)" style="color:#5fb878">'.__('通过','jinsom').'</m>
<m onclick="jinsom_content_management_refuse('.$post_id.',0,2,this)" style="color:#f00">'.__('驳回','jinsom').'</m>
</span>';
}else{
$do='<span><m onclick="jinsom_content_management_pending_refuse('.$post_id.',this)" style="color:#f00">'.__('取消审核','jinsom').'</m></span>';
}

echo '<li><span onclick="jinsom_post_link(this)" data="'.get_the_permalink().'">'.$title.'</span><span>'.jinsom_timeago(get_the_time('Y-m-d H:i:s')).'</span>'.$do.'</li>';
endwhile;
}else{
echo jinsom_empty();
}
?>
</div>
<div id="jinsom-content-management-pending-page" class="jinsom-mycredit-page content-management"></div>
</ul>


<ul style="display: none;">
<div class="header"><span><?php _e('标题','jinsom');?></span><span><?php _e('时间','jinsom');?></span><span><?php _e('原因','jinsom');?></span></div>
<div class="content draft">
<?php 
query_posts($args_draft);
if(have_posts()){
while (have_posts()):the_post();
$post_id=get_the_ID();
if(get_the_title()){
$title=get_the_title();
}else{
$title=mb_substr(strip_tags(get_the_content()),0,40,'utf-8');
}

echo '<li><span onclick="jinsom_post_link(this)" data="'.get_the_permalink().'">'.$title.'</span><span>'.jinsom_timeago(get_the_time('Y-m-d H:i:s')).'</span><span><m onclick="jinsom_content_management_reason_form('.$post_id.')" style="color:#f00">'.__('点击查看','jinsom').'</m></span></li>';
endwhile;
}else{
echo jinsom_empty();
}
?>
</div>
<div id="jinsom-content-management-draft-page" class="jinsom-mycredit-page content-management"></div>
</ul>

</div>
</div>

<script type="text/javascript">
$('.jinsom-content-management-title li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$(this).parent().next().children('ul').eq($(this).index()).show().siblings().hide();
});

//分页
layui.use('laypage', function(){
var laypage = layui.laypage;

<?php if($all_number>8){?>
laypage.render({//全部内容
elem: 'jinsom-content-management-all-page',
limit:8,
groups:3,
count: <?php echo $all_number;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-content-management-content .content.all').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/content-management.php",
data:{page:page,number:number,type:'all'},
success: function(msg){
$('.jinsom-content-management-content .content.all').html(msg);
}
});

}
}
});
<?php }?>


<?php if($pending_number>8){?>
laypage.render({//待审核内容
elem: 'jinsom-content-management-pending-page',
limit:8,
groups:3,
count: <?php echo $pending_number;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-content-management-content .content.pending').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/content-management.php",
data:{page:page,number:number,type:'pending'},
success: function(msg){
$('.jinsom-content-management-content .content.pending').html(msg);
}
});

}
}
});
<?php }?>

<?php if($draft_number>8){?>
laypage.render({//被驳回内容
elem: 'jinsom-content-management-draft-page',
limit:8,
groups:3,
count: <?php echo $draft_number;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-content-management-content .content.draft').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/data/content-management.php",
data:{page:page,number:number,type:'draft'},
success: function(msg){
$('.jinsom-content-management-content .content.draft').html(msg);
}
});

}
}
});
<?php }?>

});
</script>
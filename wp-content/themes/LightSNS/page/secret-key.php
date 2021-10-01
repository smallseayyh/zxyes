<?php
/*
Template Name:卡密充值
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{
get_header();
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<!-- 主内容 -->
<div class="jinsom-main-content single clear">
<?php require(get_template_directory().'/sidebar/sidebar-page.php');?>
<div class="jinsom-content-left"><!-- 左侧 -->
<?php while ( have_posts() ) : the_post();
global $wp_query;//文章函数引人
$curauth = $wp_query->get_queried_object();
$user_info = get_userdata(get_the_author_meta('ID'));?>
<div class="jinsom-page-content">
<h1><?php the_title();?></h1>


<div style="text-align: left;margin: 20px 0 0;">
<?php the_content();?>
<?php if (is_user_logged_in()) { ?>
<button class="layui-btn layui-btn-danger" style="float: right;color: " onclick="jinsom_keypay_form('卡密兑换')">快捷充值按钮</button>
<?php }?>
</div>
<table class="layui-table" style="text-align: center;">
<thead>
<tr>
<th style="width: 25%;text-align: center;">卡密</th>
<th style="width: 10%;text-align: center;">类型</th>
<th style="width: 6%;text-align: center;">面值</th>
<th style="width: 13%;text-align: center;">使用者</th>
<th style="width: 15%;text-align: center;">有效期</th>
</tr> 
</thead>
<tbody>
<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'jin_key';
$key_data = $wpdb->get_results("SELECT * FROM $table_name WHERE 1 ORDER BY ID desc;");

foreach ($key_data as $data){
$type=$data->type;
if($type=='credit'){
$type=$credit_name;
}else if($type=='exp'){
$type='经验';
}else if($type=='vip'){
$type='会员';
}else if($type=='sign'){
$type='补签';
}else if($type=='vip_number'){
$type='成长值';
}

if(!$data->user_id){
$key_user='<font style="color:#4CAF50">未使用</font>';
}else{
$key_user=jinsom_nickname_link($data->user_id);
}
echo '<tr>';
echo '<td>'.$data->key_number.'</td>';
echo '<td>'.$type.'</td>';
echo '<td>'.$data->number.'</td>';
echo '<td>'.$key_user.'</td>';
echo '<td>'.$data->expiry.'</td>';
echo '</tr>';
}?>
</tbody>
</table> 

</div>
<?php endwhile;?>


</div><!-- 左侧结束 -->






</div>


<?php get_footer();

}?>
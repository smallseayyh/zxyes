<?php 
//获取排行榜数据
require( '../../../../../../wp-load.php' );
$number=(int)$_POST['number'];
$post_id=(int)$_POST['post_id'];
$page_option_data=get_post_meta($post_id,'page_leaderboard_data',true);
$jinsom_leaderboard_add=$page_option_data['jinsom_leaderboard_add'];
$type=$jinsom_leaderboard_add[$number]['type'];
$type_name=$jinsom_leaderboard_add[$number]['unit'];//单位
require( get_template_directory() . '/mobile/module/stencil/leaderboard-list.php' );
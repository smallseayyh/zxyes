<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$sign_day= (int)get_user_meta($user_id,'sign_c',true);



global $wpdb;
$table_name=$wpdb->prefix.'jin_sign';
$sign_data=$wpdb->get_results("SELECT strtotime FROM $table_name WHERE user_id='$user_id' ORDER BY date DESC limit 31;");
$month_day=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id AND ( DATE_FORMAT(date,'%Y%m')=DATE_FORMAT(CURDATE(),'%Y%m') )  GROUP BY date limit 31;");
$month_day=count($month_day);
?>

<div data-page="sign" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('签到','jinsom');?></div>
<div class="right">
<a href="<?php echo $theme_url;?>/mobile/templates/page/sign-rank.php" class="link icon-only"><?php _e('排行榜','jinsom');?></a>
</div>
</div>
</div>

<div class="page-content">

<div class="jinsom-sign-header">
<div class="left">
<p><span><?php echo $sign_day;?></span><?php _e('天','jinsom');?></p>
<p><?php _e('累计签到','jinsom');?></p>
</div>
<div class="right">
<?php if(jinsom_is_sign($user_id,date('Y-m-d',time()))){?>
<div class="btn had"><?php _e('已签到','jinsom');?></div>
<?php }else{?>

<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("sign",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<div class="btn" id="sign-1"><?php _e('签到','jinsom');?></div>
<?php }else{?>
<div class="btn" onclick="jinsom_sign(this,'','')"><?php _e('签到','jinsom');?></div>
<?php }?>
<?php }?>
</div>
</div>


<div class="jinsom-sign-page-content">
<table border="1px" cellpadding="0" cellspacing="0">
<thead>
<tr class="tou">
<td><span><?php _e('日','jinsom');?></span></td>
<td><span><?php _e('一','jinsom');?></span></td>
<td><span><?php _e('二','jinsom');?></span></td>
<td><span><?php _e('三','jinsom');?></span></td>
<td><span><?php _e('四','jinsom');?></span></td>
<td><span><?php _e('五','jinsom');?></span></td>
<td><span><?php _e('六','jinsom');?></span></td>
</tr>
</thead>
<tbody id="jinsom-sign-body">
</tbody>
</table>
</div>


<div class="jinsom-sign-page-box month">
<div class="jinsom-sign-page-month-days"><?php _e('本月签到','jinsom');?><span><?php echo $month_day;?></span><?php _e('天','jinsom');?></div>
<div class="content">
<?php 
$jinsom_sign_treasure_add=jinsom_get_option('jinsom_sign_treasure_add');
if($jinsom_sign_treasure_add){
$i=0;
foreach ($jinsom_sign_treasure_add as $data){
$day=$data['day'];
$had=jinsom_is_task($user_id,date('Y-m',time()).'_'.$day);


if($had){
echo '<li>
<div class="img"><img src="'.$data['img'].'" onclick="jinsom_sign_treasure_form('.$i.')"><span>'.$day.__('天','jinsom').'</span></div>	
<div class="btn opacity had">'.__('已领取','jinsom').'</div>
</li>';
}else{
echo '<li>
<div class="img"><img src="'.$data['img'].'" onclick="jinsom_sign_treasure_form('.$i.')"><span>'.$day.__('天','jinsom').'</span></div>	
<div class="btn opacity" onclick="jinsom_sign_treasure('.$i.',this)">'.__('领取','jinsom').'</div>
</li>';	
}

$i++;
}
}
?>
</div>
</div>

<?php 
$jinsom_mobile_sign_footer_html=jinsom_get_option('jinsom_mobile_sign_footer_html');
if($jinsom_mobile_sign_footer_html){
echo '<div class="jinsom-sign-page-box custom">'.do_shortcode($jinsom_mobile_sign_footer_html).'</div>';
}
?>

<div id="jinsom-sign-data-hide" style="display:none;">
<?php 
if($sign_data){
$sign_html='';
foreach ($sign_data as $data){
$sign_html.=$data->strtotime.',';
}
echo rtrim($sign_html,',');
}
?>
</div>


</div>
</div>        
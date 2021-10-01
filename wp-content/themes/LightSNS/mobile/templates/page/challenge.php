<?php 
//挑战
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
$default_color=jinsom_get_option('jinsom_challenge_default_color');
$credit_name=jinsom_get_option('jinsom_credit_name');
global $wpdb;
$table_name=$wpdb->prefix.'jin_challenge';
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name ORDER BY ID DESC LIMIT 20;");
?>
<div data-page="challenge" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo jinsom_get_option('jinsom_challenge_page_title');?></div>
<div class="right">
<?php if(is_user_logged_in()){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/challenge-mine.php" class="link icon-only">我的</a>
<?php }else{?>
<a class="link icon-only open-login-screen">我的</a>
<?php }?>
</div>

<div class="subnavbar">
<div class="jinsom-challenge-menu jinsom-home-menu">
<li class="on" type="all" onclick="jinsom_challenge_data('all',this)">全部</li>
<li type="ing" onclick="jinsom_challenge_data('ing',this)">进行中</li>
<li type="end" onclick="jinsom_challenge_data('end',this)">已结束</li>
</div>
</div>

</div>
</div>

<div class="page-content jinsom-challenge-content hide-navbar-on-scroll infinite-scroll" data-distance="500">
<div class="jinsom-publish-challenge-box" style="background:<?php echo $default_color;?>">
<a href="<?php echo $theme_url;?>/mobile/templates/page/publish/challenge.php" class="link">
<div class="title"><?php _e('发起新挑战','jinsom');?></div>
<div class="desc"><?php _e('来秀秀你的肌肉，看谁才是最厉害的那个！','jinsom');?></div>
<i class="jinsom-icon jinsom-jirou"></i>
</a>
</div>
<?php echo do_shortcode(jinsom_get_option('jinsom_challenge_header_html'));?>
<div class="jinsom-challenge-post-list">
<?php 
if($challenge_data){
foreach ($challenge_data as $data){
$type=$data->type;
$challenge_user_id=$data->challenge_user_id;
$price=(int)$data->price;
if($type=='a'){
$type_text=__('石头剪刀布','jinsom');
}else{
$type_text=__('数字比大小','jinsom');	
}
$c_user_id=$data->user_id;
if($challenge_user_id){
$btn='<a href="'.$theme_url.'/mobile/templates/page/challenge-join.php?id='.$data->ID.'" class="link no">'.__('已结束','jinsom').'</a>';
}else{
$btn='<a href="'.$theme_url.'/mobile/templates/page/challenge-join.php?id='.$data->ID.'" class="link">'.__('挑战','jinsom').'</a>';
}
echo '<li id="jinsom-challenge-'.$data->ID.'">
<div class="avatarimg"><a href="'.jinsom_mobile_author_url($c_user_id).'" class="link">'.jinsom_avatar($c_user_id,'40',avatar_type($c_user_id)).jinsom_verify($c_user_id).'</a></div>	
<div class="info"><a href="'.$theme_url.'/mobile/templates/page/challenge-join.php?id='.$data->ID.'" class="link"><div class="name">'.__('发起挑战','jinsom').'：<span>'.$type_text.'</span></div><div class="desc">'.$data->description.'</div></a></div>
<div class="number">
<div class="price">'.$price.$credit_name.'</div>
<div class="btn">'.$btn.'</div>
</div>
</li>';
}
}else{
echo jinsom_empty('还没有人发起挑战！');
}
?>

</div>
</div>
</div>        
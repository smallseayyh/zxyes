<?php 
//树洞秘密
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$jinsom_secret_type_add=jinsom_get_option('jinsom_secret_type_add');
$jinsom_secret_color_add=jinsom_get_option('jinsom_secret_color_add');
if($jinsom_secret_color_add){
shuffle($jinsom_secret_color_add);
$first_color=$jinsom_secret_color_add[0]['color'];
}else{
$first_color='#666';
}
?>
<div data-page="publish" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"></div>
<div class="right">


<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish-secret",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<a href="#" class="link icon-only" id="publish-secret"><?php _e('发表','jinsom');?></a>
<?php }else{?>
<a href="#" class="link icon-only" onclick="jinsom_publish_secret('','');"><?php _e('发表','jinsom');?></a>
<?php }?>

</div>
</div>
</div>



<div class="page-content jinsom-publish-words-form secret">
<form id="jinsom-publish-form">
<div class="content">
<textarea name="content" placeholder="<?php echo jinsom_get_option('jinsom_secret_textarea_placeholder');?>" class="resizable jinsom-smile-textarea" style="background:<?php echo $first_color;?>;"></textarea>
<i onclick="jinsom_smile_form(this)" class="smile jinsom-icon expression jinsom-weixiao-"></i>
<div class="hidden-smile" style="display: none;"><?php echo jinsom_get_expression(2,0);?></div>
</div>

<?php 
if($jinsom_secret_color_add){
echo '<div class="jinsom-publish-secret-color-list clear">';
foreach ($jinsom_secret_color_add as $data) {
if($data['vip']){
$vip='VIP';
}else{
$vip='';
}
echo '<li style="background:'.$data['color'].'" data="'.$data['color'].'">'.$vip.'</li>';
}

echo '</div>';
}

?>

<?php 
if($jinsom_secret_type_add){
echo '<div class="jinsom-publish-secret-type-list clear">';
$i=0;
foreach ($jinsom_secret_type_add as $data) {
if($i==0){
$on='class="on"';
}else{
$on='';
}
echo '<li '.$on.'>'.$data['name'].'</li>';
$i++;
}

echo '</div>';
}
?>



<input type="hidden" name="color" value="<?php echo $first_color;?>">
</form>

<?php echo do_shortcode(jinsom_get_option('jinsom_secret_publish_footer_html'));?>


</div>




</div>   


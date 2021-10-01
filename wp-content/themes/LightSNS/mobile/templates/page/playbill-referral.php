<?php 
//内容海报生成
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;


$content_playbill_copyright=jinsom_get_option('jinsom_referral_playbill_copyright');

$img=get_template_directory_uri().'/images/default-cover.jpg';
if(jinsom_get_option('jinsom_referral_playbill_cover')){
$img=jinsom_get_option('jinsom_referral_playbill_cover');
}

$post_type=get_post_meta($post_id,'post_type',true);
$is_bbs_post=is_bbs_post($post_id);
if($is_bbs_post||$post_type=='single'){
preg_match_all("/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is",$content,$result);
if(count($result[1])>0){
$img=$result[1][0];
}
}else if($post_type=='words'){
$post_img=get_post_meta($post_id,'post_img',true);
if($post_img){
$post_power=get_post_meta($post_id,'post_power',true);//内容权限
$pay_img_on_off=get_post_meta($post_id,'pay_img_on_off',true);
if($post_power==0||(($post_power==1||$post_power==2||$post_power==4||$post_power==5)&&$pay_img_on_off=='on')){
$post_img_arr=explode(",",$post_img);
$img=$post_img_arr[0];
}
}
}else if($post_type=='video'){
$video_img=get_post_meta($post_id,'video_img',true);
if($video_img){
$img=$video_img;
}
}else{

}

?>
<div data-page="content-playbill" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('生成海报','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>

</div>
</div>

<div class="page-content">

<div class="jinsom-content-playbill-main referral">
<div id="jinsom-content-playbill" class="jinsom-content-playbill referral">

<div class="referral-img">
<img src="<?php echo $img;?>" style="display: block;margin-bottom: 2vw;">
</div>
<?php echo do_shortcode(jinsom_get_option('jinsom_referral_playbill_footer_custom_html'));?>
<div class="footer">
<div class="code" id="jinsom-content-playbill-code"></div>
<?php echo $content_playbill_copyright;?>
</div>

</div>
</div>

<div id="jinsom-add-content-playbill" class="jinsom-add-content-playbill"><?php _e('生成海报','jinsom');?></div>

</div>

</div>
</div>        
<?php
/*
Template Name:案例页面
*/

if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');  	
}else{
get_header();
$post_id=get_the_ID();
$case_option=get_post_meta($post_id,'case_option',true);
if($case_option){
$case_data=$case_option['jinsom_data_add'];


$case_arr=explode(",",$jinsom_case_cat);
?>
<div class="jinsom-main-content jinsom-case-bg jinsom-case-bg-<?php echo $post_id;?>">
<?php echo do_shortcode($case_option['header_ad']);?>
<div class="jinsom-case-menu clear">
<?php 
if($case_data){
$a=1;
foreach ($case_data as $data){
if($a==1){$on='on';}else{$on='';}
echo '<li class="'.$on.'">'.$data['menu_name'].'</li>';
$a++;
}	
}
?>
<?php if(current_user_can('level_10')){?>
<div class="admin" onclick="jinsom_post_link(this)" data="/wp-admin/post.php?post=<?php echo $post_id;?>&action=edit"><i class="jinsom-icon jinsom-shezhi"></i></div>
<?php }?>
<?php if($case_option['case_join_url']){?>
<li class="join opacity" onclick="jinsom_post_link(this)" data="<?php echo $case_option['case_join_url'];?>"><?php echo $case_option['case_join_name'];?></li>
<?php }?>
</div>
<div class="jinsom-case-content clear">
<?php 
if($case_data){
$b=1;
foreach ($case_data as $data){
if($b==1){$hide='';}else{$hide='style="display:none;"';}
echo '<ul '.$hide.'>';
if($data['case_add']){

foreach ($data['case_add'] as $case) {
$nofollow=$case['nofollow'];
if($nofollow){$nofollow='rel="nofollow"';}else{$nofollow='';}
$link=do_shortcode($case['link']);

if(!$case['hide']){//不隐藏
echo '
<li>
<div class="jinsom-case-avatar">
<a href="'.$link.'" '.$nofollow.' target="_blank" style="background-image: url('.$case['images'].');"></a>
</div>
<div class="jinsom-case-info">
<div class="jinsom-case-name"><a href="'.$link.'" '.$nofollow.' target="_blank">'.$case['title'].'</a></div>
<div class="jinsom-case-desc">'.$case['desc'].'</div>
<div class="jinsom-case-visit"><a href="'.$link.'" '.$nofollow.' target="_blank">查看详情 <i class="fa fa-angle-right"></i></a></div>
</div>	
</li>';
}


}


}


echo '</ul>';
$b++;
}}?>
</div>
<?php echo do_shortcode($case_option['footer_ad']);?>
</div>

<script type="text/javascript">
$('.jinsom-case-menu li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$(this).parent().next().children('ul').eq($(this).index()).show().siblings().hide();
});
</script>


<?php }else{?>
<div class="jinsom-main-content jinsom-case-bg"><?php echo jinsom_empty('请在WordPress后台-页面-所有页面-找到你自己新建的案例页面-编辑-最底部进行配置数据');?></div>
<?php }?>
<?php get_footer();?>
<?php }?>
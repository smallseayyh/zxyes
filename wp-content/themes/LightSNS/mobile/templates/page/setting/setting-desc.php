<?php 
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$author_id=$_GET['author_id'];	
}else{
$author_id=$user_id;
}
$user_info = get_userdata($author_id);
$desc_number_max=jinsom_get_option('jinsom_user_desc_number_max');

?>
<div data-page="setting-desc" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('个人说明','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only" onclick="jinsom_update_desc(<?php echo $author_id;?>)"><?php _e('保存','jinsom');?></a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content desc">

<div class="jinsom-setting-box">
<textarea placeholder="<?php _e('介绍一下你自己嘛','jinsom');?>" id="jinsom-setting-desc"><?php echo $user_info->description;?></textarea>
<div class="jinsom-setting-box-desc-tips"><?php echo sprintf(__( '%s字内','jinsom'),$desc_number_max);?></div>
</div>

</div>       


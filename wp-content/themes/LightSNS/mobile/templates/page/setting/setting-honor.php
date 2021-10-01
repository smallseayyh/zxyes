<?php 
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$author_id=$_GET['author_id'];	
}else{
$author_id=$user_id;
}
$user_honor=get_user_meta($author_id,'user_honor',true);
$use_honor=get_user_meta($author_id,'use_honor',true);//用户当前使用的勋章


?>
<div data-page="setting-honor" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('选择头衔','jinsom');?></div>
<div class="right">
<?php if($user_honor!=''){?>
<a href="#" class="link icon-only" onclick="jinsom_use_honor(<?php echo $author_id;?>)"><?php _e('确定','jinsom');?></a>
<?php }else{?>
<a href="#" class="link icon-only"></a>
<?php }?>
</div>
</div>
</div>

<div class="page-content">

<div class="jinsom-user_honor-select-form">
<?php if($user_honor!=''){?>
<div class="list clear">
<?php 
$honor_arr=explode(",",$user_honor);
foreach ($honor_arr as $data) {
if($use_honor==$data){$on='class="on"';}else{$on='';}
echo '<li '.$on.'>'.$data.'</li>';
}
?>	
</div>
<?php }else{?>
<div class="nodata">
<?php _e('还没有获得头衔，多为社区作贡献将获得头衔','jinsom');?>
</div>
<?php }?>

</div>

</div>       


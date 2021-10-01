<?php 
//案例页面
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$case_option=get_post_meta($post_id,'case_option',true);
$case_data=$case_option['jinsom_data_add'];
$case_arr=explode(",",$jinsom_case_cat);


$mobile_case_join_name=$case_option['mobile_case_join_name'];//申请名称
$mobile_case_join_url=$case_option['mobile_case_join_url'];//申请链接
?>
<div data-page="case" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $case_option['case_mobile_title_name'];?></div>
<div class="right">
<?php if($mobile_case_join_name){?>
<a href="<?php echo do_shortcode($mobile_case_join_url);?>" class="link icon-only"><?php echo $mobile_case_join_name;?></a>
<?php }else{?>
<a href="#" class="link icon-only"></a>
<?php }?>

</div>

<?php if($case_data&&count($case_data)>1){?>
<div class="subnavbar">
<div class="jinsom-home-menu case clear">
<?php 
$a=1;
foreach ($case_data as $data){
if($a==1){$on='on';}else{$on='';}
echo '<li class="'.$on.'">'.$data['menu_name'].'</li>';
$a++;
}	
?>
</div>
</div>
<?php }?>




</div>
</div>

<div class="page-content jinsom-case-page-content">

<div class="jinsom-case-content clear" <?php if(count($case_data)<2){echo 'style="padding-top:4vw;"';}?>>
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
	
if($case['app']){
$app='class="link"';
}else{
$app='target="_blank"';
}

if(!$case['hide']){//不隐藏
echo '
<li>
<a href="'.do_shortcode($case['link']).'" '.$app.' '.$nofollow.'>
<div class="jinsom-case-avatar" style="background-image: url('.$case['images'].');"></div>
<div class="jinsom-case-info">
<div class="jinsom-case-name">'.$case['title'].'</div>
<div class="jinsom-case-desc">'.$case['desc'].'</div>
<div class="jinsom-case-visit">查看详情 <i class="fa fa-angle-right"></i></div>
</div>	
</a>
</li>';
}


}


}


echo '</ul>';
$b++;
}}?>
</div>


</div>
</div>      
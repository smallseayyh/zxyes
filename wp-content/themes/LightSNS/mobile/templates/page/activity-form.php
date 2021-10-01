<?php 
//活动报名表单
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$activity_data=get_post_meta($post_id,'activity_data',true);
$activity_price=(int)get_post_meta($post_id,'activity_price',true);
$activity_arr=explode(",",$activity_data);
$number=count($activity_arr);
?>
<div data-page="activity-form" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"></div>
<div class="right">
<a href="#" class="link icon-only" onclick="jinsom_activity(<?php echo $post_id;?>)">提交</a>
</div>
</div>
</div>

<div class="page-content">

<div class="jinsom-activity-form-list">
<?php 
$a=1;
for ($i=0; $i < $number; $i+=2) { 
echo '<li>';
echo '<label>'.$activity_arr[$i+1].'：</label>';
if($activity_arr[$i]=='text'){
echo '<input class="item" type="text" placeholder="请输入'.$activity_arr[$i+1].'">';
}else if($activity_arr[$i]=='number'){
echo '<input class="item" type="number" placeholder="请输入'.$activity_arr[$i+1].'">';	
}else if($activity_arr[$i]=='textarea'){
echo '<textarea class="item" placeholder="请输入'.$activity_arr[$i+1].'"></textarea>';	
}else if($activity_arr[$i]=='upload'){
echo '<span class="jinsom-activity-upload opacity">点击上传
<form class="jinsom-upload-activity-form jinsom-upload-activity-form-'.$a.'" method="post" enctype="multipart/form-data" action="'.get_template_directory_uri().'/module/upload/file.php">
<input type="file" name="file">
</form>
</span>';	
echo '<input class="item upload" type="text" style="display:none;">';
$a++;
}
echo '</li>';
}
?>
</div>

<?php if($activity_price>0){echo '<div class="jinsom-activity-form-tips">需要花费'.$activity_price.jinsom_get_option('jinsom_credit_name').'</div>';}?>

</div>

</div>
</div>        
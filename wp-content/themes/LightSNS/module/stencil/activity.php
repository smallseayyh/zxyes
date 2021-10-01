<?php
//活动报名表单
require( '../../../../../wp-load.php' );
$post_id=(int)$_POST['post_id'];
$activity_data=get_post_meta($post_id,'activity_data',true);
$activity_price=(int)get_post_meta($post_id,'activity_price',true);
$activity_arr=explode(",",$activity_data);
$number=count($activity_arr);
?>
<div class="jinsom-activity-form-content">
<div class="header"><?php _e('填写内容','jinsom');?></div>
<div class="jinsom-activity-form-list">
<?php 
for ($i=0; $i < $number; $i+=2) { 
echo '<li>';
echo '<label>'.$activity_arr[$i+1].'：</label>';
if($activity_arr[$i]=='text'){
echo '<input class="item" type="text" placeholder="'.__('请输入','jinsom').$activity_arr[$i+1].'">';
}else if($activity_arr[$i]=='number'){
echo '<input class="item" type="number" placeholder="'.__('请输入','jinsom').$activity_arr[$i+1].'">';	
}else if($activity_arr[$i]=='textarea'){
echo '<textarea class="item" placeholder="'.__('请输入','jinsom').$activity_arr[$i+1].'"></textarea>';	
}else if($activity_arr[$i]=='upload'){
echo '<span class="jinsom-activity-upload opacity">'.__('点击上传','jinsom').'</span>';	
echo '<input class="item upload" type="text" style="display:none;">';
}
echo '</li>';
}
?>
</div>
<div class="btn opacity" onclick="jinsom_activity(<?php echo $post_id;?>)"><?php _e('马上提交','jinsom');?><?php if($activity_price>0){echo '（-'.$activity_price.jinsom_get_option('jinsom_credit_name').'）';}?></div>
</div>
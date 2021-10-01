<?php
//发表内容选择权限表单
require( '../../../../../wp-load.php' );
$type=$_POST['type'];
if($type=='words'){
$jinsom_publish_power=jinsom_get_option('jinsom_words_power_sorter_a');
}elseif($type=='music'){
$jinsom_publish_power=jinsom_get_option('jinsom_music_power_sorter_a');	
}elseif($type=='single'){
$jinsom_publish_power=jinsom_get_option('jinsom_single_power_sorter_a');	
}elseif($type=='video'){
$jinsom_publish_power=jinsom_get_option('jinsom_video_power_sorter_a');	
}


?>
<div class="jinsom-publish-power-content clear">
<li data="0"><i class="jinsom-icon jinsom-gongkai1"></i><span>公开内容</span></li>
<?php
$enabled=$jinsom_publish_power['enabled'];
if($enabled){
foreach($enabled as $x=>$x_value){
switch($x){
case 'pay': 
echo '<li data="1"><i class="jinsom-icon jinsom-fufei"></i><span>'.__('付费内容','jinsom').'</span></li>';  
break;
case 'password': 
echo '<li data="2"><i class="jinsom-icon jinsom-mima"></i><span>'.__('密码内容','jinsom').'</span></li>'; 
break;
case 'private':    
echo '<li data="3"><i class="jinsom-icon jinsom-biyan"></i><span>'.__('私密内容','jinsom').'</span></li>';  
break;
case 'vip':    
echo '<li data="4"><i class="jinsom-icon jinsom-vip-type"></i><span>'.__('VIP可见','jinsom').'</span></li>';  
break;
case 'login':    
echo '<li data="5"><i class="jinsom-icon jinsom-denglu"></i><span>'.__('登录可见','jinsom').'</span></li>'; 
break;
case 'comment':    
echo '<li data="6"><i class="jinsom-icon jinsom-pinglun2"></i><span>'.__('回复可见','jinsom').'</span></li>'; 
break;
case 'verify':    
echo '<li data="7"><i class="jinsom-icon jinsom-dagou"></i><span>'.__('认证可见','jinsom').'</span></li>'; 
break;
case 'follow':    
echo '<li data="8"><i class="jinsom-icon jinsom-qunzu"></i><span>'.__('粉丝可见','jinsom').'</span></li>'; 
break;
}}}
?>



</div>

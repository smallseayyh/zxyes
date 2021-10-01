<?php
//弹窗选择发表类型
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$jinsom_publish_add=jinsom_get_option('jinsom_publish_add');

if(isset($_POST['topic_name'])){
$topic_name=$_POST['topic_name'];
}else{
$topic_name='';	
}


if($jinsom_publish_add){

if($topic_name){
echo '<div class="jinsom-publish-type-form topic clear">';	
}else{
echo '<div class="jinsom-publish-type-form clear">';
}

foreach ($jinsom_publish_add as $data) {
$publish_type=$data['jinsom_publish_add_type'];
if($publish_type=='words'){
$default_icon='<i class="jinsom-icon jinsom-shangjiadongtai"></i>';
}else if($publish_type=='music'){
$default_icon='<i class="jinsom-icon jinsom-yinle1"></i>';	
}else if($publish_type=='single'){
$default_icon='<i class="jinsom-icon jinsom-wenzhang44"></i>';	
}else if($publish_type=='video'){
$default_icon='<i class="jinsom-icon jinsom-shipin"></i>';	
}else if($publish_type=='redbag'){
$default_icon='<i class="jinsom-icon jinsom-hongbao2"></i>';	
}else{
$default_icon='<i class="jinsom-icon jinsom-neirong"></i>';	
}
if($data['icon']){$icon=$data['icon'];}else{$icon=$default_icon;}

if($publish_type=='bbs'){
$bbs_id=$data['bbs_id'];
}else{
$bbs_id=0;
}

if(!$data['in_mobile']){
if($publish_type!='custom'){
echo '<li class="'.$publish_type.' opacity" onclick=\'jinsom_publish_power("'.$publish_type.'",'.$bbs_id.',"'.$topic_name.'")\'><span style="background-color:'.$data['color'].';">'.$icon.'</span><p>'.$data['name'].'</p></li>';
}else{
echo '<li class="'.$publish_type.' opacity"><a href="'.$data['custom'].'"><span style="background-color:'.$data['color'].';">'.$icon.'</span><p>'.$data['name'].'</p></a></li>';	
}
}


}
echo '</div>';
}


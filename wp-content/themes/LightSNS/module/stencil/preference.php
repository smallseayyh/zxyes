<?php
//偏好设置
require( '../../../../../wp-load.php' );
$jinsom_preference_bg_add=jinsom_get_option('jinsom_preference_bg_add');

if($jinsom_preference_bg_add){
if(isset($_COOKIE['preference-bg'])){
$default_on='';
}else{
$default_on='on';	
}
echo '<li bg="default" class="'.$default_on.' bg-default"><img src="'.get_bloginfo('template_url').'/images/preference.png" alt="'.__('默认','jinsom').'"><span>'.__('默认','jinsom').'</span></li>';
$i=1;
foreach ($jinsom_preference_bg_add as $data) {
if($_COOKIE['preference-bg']==$data['css']){
$on='on';
}else{
$on='';
}
echo '<li bg="'.$data['css'].'" class="'.$on.' bg-'.$i.'"><img src="'.$data['img'].'" alt="'.$data['name'].'"><span>'.$data['name'].'</span></li>';
$i++;
}

}else{
echo jinsom_empty('请前往-主题设置-外观布局-偏好设置-配置主题风格');
}
?>

<?php 
//IM表情模版
require( '../../../../../wp-load.php' );
$type=strip_tags($_POST['type']);
$dom=strip_tags($_POST['dom']);
if($type=='im'){
echo jinsom_get_expression('im','');//IM
}else if($type=='ue'){
echo jinsom_get_expression('ue',$dom);//富文本	
}else{
echo jinsom_get_expression('normal','');
}


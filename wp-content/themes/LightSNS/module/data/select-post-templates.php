<?php
//筛选模版




if(wp_is_mobile()){
$permalink=jinsom_mobile_post_url($post_id);
$title_permalink=jinsom_mobile_post_url($post_id);
}else{
$permalink=get_the_permalink();
$title_permalink=get_the_permalink();
}

$gallery='target="_blank"';
if($gallery_on_off&&$list_style!='lattice_5'&&$list_style!='lattice_6'){
$permalink=$img;	
$gallery='data-fancybox="gallery-select-1" data-no-instant';
}

$post_price=(int)get_post_meta($post_id,'post_price',true);
if($post_price){
$post_price='<span class="price">'.$post_price.jinsom_get_option('jinsom_credit_name').'</span>';
}else{
$post_price='';
}


if($img_style){//图片样式规则
$img=$img.$img_style;
}

$html='';

$html.='
<li>
'.$post_price.'
<a href="'.$permalink.'" '.$gallery.' class="link">
<div class="bg opacity">
<img src="'.$img.'">
</div></a>';

if($list_style!='lattice_4'&&$list_style!='lattice_5'&&$list_style!='lattice_6'&&$list_style!='lattice_7'){
$html.='<div class="title"><a href="'.$title_permalink.'" target="_blank" class="link">'.$title.'</a></div>
<div class="bar clear">
<span class="views"><i class="jinsom-icon jinsom-liulan1"></i> '.$post_views.'</span>';

if(!$all_image_on_off){//抽取模式不显示喜欢
if(!wp_is_mobile()){
if(jinsom_is_like_post($post_id,$user_id)){
$html.='<span class="like jinsom-had-like" onclick="jinsom_like_posts('.$post_id.',this)"><i class="jinsom-icon jinsom-xihuan1"></i> <span>'.jinsom_count_post(0,$post_id).'</span></span>';	
}else{
$html.='<span class="like" onclick="jinsom_like_posts('.$post_id.',this)"><i class="jinsom-icon jinsom-xihuan2"></i> <span>'.jinsom_count_post(0,$post_id).'</span></span>';
}
}else{
if(jinsom_is_like_post($post_id,$user_id)){
$html.='<span class="like" onclick="jinsom_select_like('.$post_id.',this)"><i class="jinsom-icon jinsom-xihuan1 had"></i> <span>'.jinsom_count_post(0,$post_id).'</span></span>';	
}else{
$html.='<span class="like" onclick="jinsom_select_like('.$post_id.',this)"><i class="jinsom-icon jinsom-xihuan2"></i> <span>'.jinsom_count_post(0,$post_id).'</span></span>';
}	
}
}

$html.='</div>';
}



if($list_style=='lattice_5'||$list_style=='lattice_6'||$list_style=='lattice_7'){

if($list_style=='lattice_5'||$list_style=='lattice_7'){
$download_color=$select_option['jinsom_page_select_download_color'];
$download_text=$select_option['jinsom_page_select_download_text'];	
$btn='<div class="text" style="background:'.$download_color.'">'.$download_text.'</div>';
}else{
$popup_icon=$select_option['jinsom_page_select_popup_icon'];
if(!$popup_icon){
$popup_icon='<i class="jinsom-icon jinsom-sousuo1"></i>';
}
$btn='<div class="icon">'.$popup_icon.'</div>';
}

$html.='<div class="popup">
<a href="'.get_the_permalink().'" target="_blank">
<div class="shadow"></div>
<div class="popup-btn">'.$btn.'</div>
<div class="popup-title">'.$title.'</div>
</a></div>';
}


$html.='</li>';

echo $html;

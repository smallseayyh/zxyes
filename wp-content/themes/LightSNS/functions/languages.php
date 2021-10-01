<?php
//多语言
if(jinsom_get_option('jinsom_languages_on_off')&&jinsom_get_option('jinsom_languages_add')){
function jinsom_switch_languages($locale){
//先判断参数，再判断cookies，然后判断浏览器语言，最后判断网站设置的默认语言
if(isset($_GET['lang'])){
return $_GET['lang'];
}else{
if(isset($_COOKIE['lang'])){
//setcookie("lang",$_GET['lang'],time()+315360000); 这一段写到头部了
return $_COOKIE['lang'];
}else{
$lang=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,4);//浏览器默认语言
if(preg_match("/en/i",$lang)){//英语
return 'en_US';
}else if(preg_match("/fr/i",$lang)){//法语
return 'fr_FR';
}else if(preg_match("/de/i",$lang)){//德语
return 'de_DE';
}else if(preg_match("/jp/i",$lang)){//日语
return 'ja_JP';
}else if(preg_match("/ko/i",$lang)){//韩语
return 'ko_KR';
}else if(preg_match("/es/i",$lang)){//西班牙语
return 'es_ES';
}else if(preg_match("/ru/i",$lang)){//俄语
return 'ru_RU';
}else if(preg_match("/zh-t/i",$lang)||preg_match("/zh-h/i",$lang)){//台湾繁体和香港繁体
return 'zh_TW';
}else if(preg_match("/ar-s/i",$lang)){//阿拉伯语-沙特
return 'ar_SA';
}else if(preg_match("/it_i/i",$lang)){//意大利语
return 'it_IT';
}else if(preg_match("/pt-p/i",$lang)){//葡萄牙语
return 'pt_PT';
}else{
return 'zh_CN';
// if(jinsom_get_option('jinsom_languages_on_off')&&jinsom_get_option('jinsom_languages_add')){//后台是否开启了多语言
// return jinsom_get_option('jinsom_languages_add')[0]['code'];
// }else{
//return $locale;	
//}
}
}	
}
}
add_filter('locale','jinsom_switch_languages');

load_textdomain('jinsom',$_SERVER['DOCUMENT_ROOT'].'/wp-content/languages/themes/LightSNS-'.get_locale().'.mo');
}
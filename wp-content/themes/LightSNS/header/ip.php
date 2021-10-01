<?php
if(isset($_GET['lang'])){setcookie("lang",$_GET['lang'],time()+315360000);}//本地记录多语言
//IP封禁
$jinsom_ip_stop_one = jinsom_get_option('jinsom_ip_stop_one');
$jinsom_ip_stop_more = jinsom_get_option('jinsom_ip_stop_more');
$jinsom_ip_stop_domain = jinsom_get_option('jinsom_ip_stop_domain');
$ip = $_SERVER['REMOTE_ADDR'];
//单个ip
if(!empty($jinsom_ip_stop_one)){
$ip_one_arr=explode(',',$jinsom_ip_stop_one); 
if(in_array($ip,$ip_one_arr)){
header("Location:".$jinsom_ip_stop_domain);
exit;    
}  
}
//IP段
if(!empty($jinsom_ip_stop_more)){
$ip_more_arr=explode(',',$jinsom_ip_stop_more);  
$my_ip_arr=explode('.',$ip); 
$my_ip=$my_ip_arr[0].'.'.$my_ip_arr[1].'.'.$my_ip_arr[2]; 
if(in_array($my_ip,$ip_more_arr)){
header("Location:".$jinsom_ip_stop_domain);
exit;    
} 
}
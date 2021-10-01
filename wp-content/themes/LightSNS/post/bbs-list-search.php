
<div class="jinsom-bbs-box-header clear">
<div class="left">

<?php 

//菜单
$enabled_menu=get_term_meta($bbs_id,'enabled_menu',true);
$enabled_menu_arr=explode(",",$enabled_menu);
if($enabled_menu){
if($enabled_menu_arr&&$enabled_menu!='empty'){
$i=1;
foreach ($enabled_menu_arr as $data){
if($data=='comment'){
$name=__('回复','jinsom');
$topic='';
}else if($data=='new'){
$name=__('最新','jinsom');
$topic='';
}else if($data=='nice'){
$name=__('精华','jinsom');
$topic='';
}else if($data=='pay'){
$name=__('付费','jinsom');	
$topic='';
}else if($data=='answer'){
$name=__('问答','jinsom');
$topic='';
}else if($data=='ok'){
$name=__('已解决','jinsom');
$topic='';
}else if($data=='no'){
$name=__('未解决','jinsom');
$topic='';	
}else if($data=='vote'){
$name=__('投票','jinsom');
$topic='';
}else if($data=='activity'){
$name=__('活动','jinsom');	
$topic='';
}else if($data=='custom_1'){
$name=get_term_meta($bbs_id,'custom_menu_name_1',true);	
$topic=get_term_meta($bbs_id,'custom_menu_topic_1',true);
}else if($data=='custom_2'){
$name=get_term_meta($bbs_id,'custom_menu_name_2',true);
$topic=get_term_meta($bbs_id,'custom_menu_topic_2',true);
}else if($data=='custom_3'){
$name=get_term_meta($bbs_id,'custom_menu_name_3',true);	
$topic=get_term_meta($bbs_id,'custom_menu_topic_3',true);
}else if($data=='custom_4'){
$name=get_term_meta($bbs_id,'custom_menu_name_4',true);
$topic=get_term_meta($bbs_id,'custom_menu_topic_4',true);
}else if($data=='custom_5'){
$name=get_term_meta($bbs_id,'custom_menu_name_5',true);
$topic=get_term_meta($bbs_id,'custom_menu_topic_5',true);
}
if($i==1){
$on='class="on"';
}else{
$on='';
}

echo '<li onclick=\'jinsom_ajax_bbs_menu("'.$data.'",this)\' '.$on.' topic="'.$topic.'">'.$name.'</li>';
$i++;
}
}
}else{
echo '
<li onclick=\'jinsom_ajax_bbs_menu("comment",this)\' class="on" topic="">'.__('回复','jinsom').'</li>
<li onclick=\'jinsom_ajax_bbs_menu("new",this)\' topic="">'.__('最新','jinsom').'</li>
<li onclick=\'jinsom_ajax_bbs_menu("nice",this)\' topic="">'.__('精华','jinsom').'</li>
';
}

?>

</div>
<div class="right">
<?php if($cat_parents!=0){?>
<input type="text" id="jinsom-bbs-search" placeholder="搜索 <?php single_cat_title(); ?>">
<i onclick="jinsom_ajax_bbs_search()" class="jinsom-icon jinsom-sousuo1"></i>
<?php }else{?>
<input type="text" id="jinsom-bbs-search" placeholder="搜索内容">
<i onclick="jinsom_ajax_bbs_search()" class="jinsom-icon jinsom-sousuo1"></i>
<?php }?>
</div>
</div>
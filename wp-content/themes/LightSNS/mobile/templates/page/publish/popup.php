<!-- @列表 -->
<div class="popup jinsom-publish-aite-popup">
<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="link icon-only close-popup"><i class="jinsom-icon jinsom-xiangxia2"></i></a>
</div>
<div class="center">@好友</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
<div class="subnavbar">
<input type="text" placeholder="输入要@的用户昵称" oninput="jinsom_pop_aite_user_search()" maxlength="15" id="jinsom-aite-user-input">
</div>
</div>
</div>
<div class="page-content jinsom-publish-aite-form" style="padding-top: 12vw;">
<div class="list aite">
</div>
</div>
</div>

<!-- 话题筛选 -->
<div class="popup jinsom-publish-topic-popup">
<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="link icon-only close-popup"><i class="jinsom-icon jinsom-xiangxia2"></i></a>
</div>
<div class="center">选择<?php echo jinsom_get_option('jinsom_topic_name');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
<div class="subnavbar">
<input type="text" placeholder="新建或搜索<?php echo jinsom_get_option('jinsom_topic_name');?>" oninput="jinsom_pop_topic_search()" maxlength="30" id="jinsom-search-topic-input">
</div>
</div>
</div>
<div class="page-content jinsom-publish-aite-form" style="padding-top: 12vw;">
<div class="list topic"></div>
</div>
</div>

<!-- 添加应用 -->
<?php if($application_add){?>
<div class="jinsom-publish-add-application-form" style="display: none;">
<div class="jinsom-popup-half-header"><div class="title">添加应用</div><div class="close" onclick="layer.closeAll()"><i class="jinsom-icon jinsom-guanbi"></i></div></div>
<div class="jinsom-publish-add-application-form-list">
<?php 

foreach ($application_add as $data) {
$type=$data['type'];
$power=$data['power'];
$icon=$data['icon'];
$color=$data['color'];


if($type=='link'){
$default_icon='<i class="jinsom-icon jinsom-tuiguang"></i>';
$default_color='#666';
$onclick='jinsom_publish_add_application_link()';
}else if($type=='goods'){
$default_icon='<i class="jinsom-icon jinsom-shangcheng"></i>';
$default_color='#ff9800';
$onclick='layer.closeAll();myApp.getCurrentView().router.load({url:"'.get_template_directory_uri().'/mobile/templates/page/shop/order-mine.php?type=add_application&read_type=collect"});';
}else if($type=='challenge'){
$default_icon='<i class="jinsom-icon jinsom-jirou"></i>';
$default_color='#2196f3';
$onclick='jinsom_publish_add_application_challenge()';
}else if($type=='pet'){
$default_icon='<i class="jinsom-icon jinsom-chongwu"></i>';
$default_color='#009688';
$onclick='layer.closeAll();myApp.getCurrentView().router.load({url:"'.get_template_directory_uri().'/mobile/templates/page/publish/select-pet.php"});';
}
if(!$icon){
$icon=$default_icon;
}
if(!$color){
$color=$default_color;
}

if($power=='vip'){
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$onclick='layer.open({content:"该功能只有VIP用户才能使用！",skin:"msg",time:2});';
}
}else if($power=='admin'){
if(!jinsom_is_admin($user_id)){
$onclick='layer.open({content:"该功能只有管理团队才能使用！",skin:"msg",time:2});';
}
}

echo '<li class="'.$type.'" onclick=\''.$onclick.'\'><font style="color:'.$color.'">'.$icon.' </font><span>'.$data['name'].'</span></li>';


}

?>
</div>
</div>
<?php }?>

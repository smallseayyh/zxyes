<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_content_sort', array(
'title'       => 'LightSNS_首页内容排序',
'classname'   => 'jinsom-content-sort',
'description' => '首页内容浏览排序，随机或按时间或按评论数量排序',
));

if(!function_exists('jinsom_widget_content_sort')){
function jinsom_widget_content_sort($args,$instance){
echo $args['before_widget'];
if(isset($_COOKIE["sort"])&&$_COOKIE["sort"]=='rand'){
echo '
<li class="opacity" data="normal">顺序查看</li>
<li class="opacity" data="comment_count">热门排序</li>
<li class="on opacity" data="rand">随机排序</li>
';
}else if(isset($_COOKIE["sort"])&&$_COOKIE["sort"]=='comment_count'){
echo '
<li class="opacity" data="normal">顺序查看</li>
<li class="on opacity" data="comment_count">热门排序</li>
<li class="opacity" data="rand">随机排序</li>
';
}else{
echo '
<li class="on opacity" data="normal">顺序查看</li>
<li class="opacity" data="comment_count">热门排序</li>
<li class="opacity" data="rand">随机排序</li>
';	
}
echo $args['after_widget'];
}
}

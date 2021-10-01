<?php 
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色
$credit_name=jinsom_get_option('jinsom_credit_name');//金币名称
$author_id=jinsom_get_post_author_id($post_id);
$user_id=$current_user->ID;
$post_views=(int)get_post_meta($post_id,'post_views',true);
update_post_meta($post_id,'post_views',($post_views+1));
$ad_single_header=get_term_meta($bbs_id,'ad_single_header',true);
$ad_single_footer=get_term_meta($bbs_id,'ad_single_footer',true);
$tags=wp_get_post_tags($post_id);
$copyright=get_term_meta($bbs_id,'copyright',true);
if(!$copyright){
$copyright=jinsom_get_option('jinsom_bbs_copyright_info');
}
?>

<div class="jinsom-bbs-content-header-fixed">
<div class="jinsom-bbs-content-header-fixed-content">
<div class="left"><div class="jinsom-bbs-single-download-title"></div></div>
<div class="right"><div class="jinsom-bbs-download-box"></div></div>
</div>
</div>



<?php if(get_term_meta($bbs_id,'bbs_single_nav',true)){?>
<div class="jinsom-bbs-content-header-nav">
<span><a href="<?php echo home_url();?>"><?php echo jinsom_get_option('jinsom_site_name');?></a> ></span>
<span><a href="<?php echo get_category_link($bbs_id);?>"><?php echo get_category($bbs_id)->name;?></a> ></span>
<?php if($cat_parents===0||$cat_parents!=''){?>
<span><a href="<?php echo get_category_link($child_cat_id);?>"><?php echo get_category($child_cat_id)->name;?></a> ></span>
<?php }?>
正文
</div>
<?php }?>
<h1 class="jinsom-bbs-single-download-title" title="<?php echo get_the_title();?>" <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>><m class="ban">版</m><m class="shang">商</m><?php the_title();?>

<?php if(jinsom_is_like_post($post_id,$user_id)){?>
<div class="like jinsom-had-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan1"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</div>
<?php }else{?>
<div class="like jinsom-no-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan2"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</div>
<?php }?>
</h1>
<div class="jinsom-content-left">
<div class="jinsom-bbs-single-download-content">

<div class="content">
<?php echo do_shortcode(convert_smilies(wpautop(jinsom_add_lightbox_content(jinsom_get_post_content($post_id),$post_id))));//内容 ?>
</div>

<?php 
if($copyright){
echo '<div class="jinsom-bbs-copyright-info">'.do_shortcode($copyright).'</div>';
}
?>

<div class="jinsom-single-download-admin">
<?php if($post_status=='publish'){?>
<?php if(jinsom_is_admin($user_id)){?>
<?php if(is_sticky()){?>
<li class="up" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'sticky-all',this)">取消全局</li>
<?php }else{?>
<li class="up" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'sticky-all',this)">全局置顶</li>
<?php }?>
<?php }?>
<?php if($is_bbs_admin||get_user_meta($user_id,'user_power',true)==3){?>
<?php if($is_bbs_admin){?>
<?php if(get_post_meta($post_id,'jinsom_sticky',true)){?>
<li class="bbs-up" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'sticky-bbs',this)">取消版顶</li>
<?php }else{?>
<li class="bbs-up" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'sticky-bbs',this)">板块置顶</li>
<?php }?>
<?php if(get_post_meta($post_id,'jinsom_commend',true)){?>
<li class="nice" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'commend-bbs',this)">取消加精</li>
<?php }else{?>
<li class="nice" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'commend-bbs',this)">加精帖子</li>
<?php }?>
<?php echo '<li onclick=\'jinsom_change_bbs_form('.$post_id.','.$bbs_id.')\'>移动板块</li>';?>
<?php }?>
<li onclick="jinsom_content_management_refuse(<?php echo $post_id;?>,<?php echo $bbs_id;?>,1,this)">驳回内容</li>
<?php }?>
<?php }?>
<?php 
if($user_id==$author_id||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)||jinsom_is_admin_x($user_id)){
$edit_time=(int)jinsom_get_option('jinsom_edit_time_max');
$single_time=strtotime(get_the_time('Y-m-d H:i:s'));
if(time()-$single_time<=60*60*$edit_time||jinsom_is_admin($user_id)||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){
echo '<li onclick=\'jinsom_editor_form("bbs",'.$post_id.')\'>编辑内容</li>';
}
?>
<li class="delete" onclick="jinsom_delete_bbs_post(<?php echo $post_id;?>,<?php echo $bbs_id;?>,this)">删除帖子</li>
<?php }?>
</div>

<?php if($tags){?>
<div class="jinsom-single-download-topic-list clear">
<div class="title">相关<?php echo jinsom_get_option('jinsom_topic_name');?></div>
<?php 
$i=1;
foreach($tags as $tag){
$tag_link=get_tag_link($tag->term_id);
if($i<=10){
echo '<a href="'.$tag_link.'" title="'.$tag->name.'" class="opacity">'.$tag->name.'</a>';
}
$i++;
}
?>
</div>
<?php }?>


</div>
</div><!-- jinsom-content-left -->



<div class="jinsom-content-right">
<div class="jinsom-bbs-sidebar-download-box">
<?php 
$download_data=get_post_meta($post_id,'download_data',true);
$download_data_arr=explode(",",$download_data);
$download_arr=explode("|",$download_data_arr[0]);
$download_url=$download_arr[0];
$download_pass_a=$download_arr[1];
$download_pass_b=$download_arr[2];
if(!$download_pass_a){$download_pass_a='无';}
if(!$download_pass_b){$download_pass_b='无';}
$tips='';
$pass='
<div class="info">
<span>提取密码：<i>'.$download_pass_a.'</i></span>
<span>解压密码：<i>'.$download_pass_b.'</i></span>
</div>';

if(count($download_data_arr)>1){
$pass.='<div class="download-more"><div class="title">备用下载</div><div class="content">';

$i=1;
foreach ($download_data_arr as $data) {
$arr=explode("|",$data);
$pass_a=$arr[1];
$pass_b=$arr[2];
if($pass_a==''){$pass_a='无';}
if($pass_b==''){$pass_b='无';}
if($i!=1){
$pass.='<li>
<div class="top">
<div class="name">备用下载'.($i-1).'</div>
<div class="url"><a href="'.$arr[0].'" target="_blank" onclick="jinsom_download_times('.$post_id.')">下载</a></div>
</div>
<div class="pass">
<span>提取密码：<i>'.$pass_a.'</i></span>
<span>解压密码：<i>'.$pass_b.'</i></span>
</div>
</li>';
}
$i++;
}


$pass.='</div></div>';
}

$download_btn='<a href="'.$download_url.'" class="btn opacity" target="_blank" onclick="jinsom_download_times('.$post_id.')">'.__('立即下载','jinsom').'</a>';

if($post_type=='pay_see'){//付费帖子
if($user_id!=$author_id&&!jinsom_get_pay_result($user_id,$post_id)&&!jinsom_is_admin($user_id)){//没有权限
$tips='你没有权限，需要付费下载（'.get_post_meta($post_id,'post_price',true).$credit_name.'）';
$pass='';
$download_btn='<a class="btn opacity" onclick=\'jinsom_show_pay_form('.$post_id.')\'>'.__('立即下载','jinsom').'</a>';
}
}else if($post_type=='vip_see'){
if($user_id!=$author_id&&!is_vip($user_id)&&!jinsom_is_admin($user_id)){//没有权限
$tips='你没有权限，VIP用户才可以下载';
$pass='';
$download_btn='<a class="btn opacity" onclick=\'jinsom_recharge_vip_form()\'>'.__('立即下载','jinsom').'</a>';
}
}else if($post_type=='login_see'){//登录可见
if(!is_user_logged_in()){
$tips='你没有权限，需要登录才可以下载';
$pass='';
$download_btn='<a class="btn" onclick="jinsom_pop_login_style()">'.__('立即下载','jinsom').'</a>';
}
}
?>

<div class="jinsom-bbs-download-box">
<?php if($tips){echo '<div class="tips"><i class="jinsom-icon jinsom-jubao"></i> '.$tips.'</div>';}?>
<div class="download-btn">
<?php echo $download_btn;?>
<?php if(jinsom_is_collect($user_id,'post',$post_id,'')){?>
<a class="collect" onclick="jinsom_collect(<?php echo $post_id;?>,'post',this)"><i class="jinsom-icon jinsom-shoucang"></i><p>已收藏</p></a>
<?php }else{?>
<a class="collect" onclick="jinsom_collect(<?php echo $post_id;?>,'post',this)"><i class="jinsom-icon jinsom-shoucang1"></i><p>收藏</p></a>
<?php }?>

</div>
<?php echo $pass;?>
</div>


<?php 
echo '<div class="jinsom-bbs-single-custom-field download clear">';
$publish_field=get_term_meta($bbs_id,'publish_field',true);//发布字段
if($publish_field){
$publish_field_arr=explode(",",$publish_field);
foreach ($publish_field_arr as $data) {
$key_arr=explode("|",$data);
if($key_arr){
if($key_arr[1]=='link'){
echo '<li class="'.$key_arr[1].'"><label>'.$key_arr[0].'：</label><a href="'.get_post_meta($post_id,$key_arr[2],true).'" target="_blank">'.get_post_meta($post_id,$key_arr[2],true).'</a></li>';
}else{
echo '<li class="'.$key_arr[1].'"><label>'.$key_arr[0].'：</label>'.wpautop(convert_smilies(get_post_meta($post_id,$key_arr[2],true))).'</li>';
}
}
}
}
echo '<li class="text"><label>下载：</label>'.(int)get_post_meta($post_id,'download_times',true).'</li>';
echo '<li class="text"><label>喜欢：</label>'.jinsom_count_post(0,$post_id).'</li>';
echo '<li class="text"><label>浏览：</label>'.jinsom_views_show($post_views).'</li>';
echo '<li class="text"><label>作者：</label>'.jinsom_nickname_link($author_id).'</li>';
echo '<li class="text"><label>时间：</label>'.jinsom_timeago(get_the_time('Y-m-d H:i:s')).'</li>';
echo '</div>';
?>

</div>

<?php echo do_shortcode($ad_single_header);?>
</div>

<div class="clear"></div>

<?php echo do_shortcode($ad_single_footer);?>

<div class="jinsom-bbs-download-relate">
<div class="title clear">你可能会喜欢的<div class="right"><a href="<?php echo get_category_link($child_cat_id);?>">查看更多>></a></div></div>
<div class="content clear">

<?php 


$args = array(
'post_parent'=>999999999,
'category__in' =>array($bbs_id,$child_cat_id),
'showposts'=>8,
'ignore_sticky_posts'=>1,
'no_found_rows'=>true,
'post__not_in'=>array($post_id),
'post_status'=>'publish'
);	
query_posts($args);
if(have_posts()){
while (have_posts()):the_post();
$post_id=get_the_ID();
if(jinsom_is_collect($user_id,'post',$post_id,'')){
$collect='<div class="collect" onclick=\'jinsom_collect('.$post_id.',"post",this)\'><i class="jinsom-icon jinsom-shoucang"></i><p>已收藏</p></div>';
}else{
$collect='<div class="collect" onclick=\'jinsom_collect('.$post_id.',"post",this)\'><i class="jinsom-icon jinsom-shoucang1"></i><p>收藏</p></div>';
}

echo '<li>
'.$collect.'
<a href="'.get_the_permalink().'" target="_blank">
<div class="bg opacity"><img src="'.jinsom_single_cover(get_the_content()).'"></div>
<div class="title">'.get_the_title().'</div>
<div class="btn"><i class="fa fa-download"></i> 立即下载</div>
<div class="shadow"></div>
</a>
</li>';


endwhile;
}else{
echo jinsom_empty();	
}
wp_reset_query();
?>



</div>
</div>


<script type="text/javascript">
$(window).scroll(function(){
if($(window).scrollTop()>=120){
$('.jinsom-bbs-content-header-fixed').show();
$('.jinsom-bbs-content-header-fixed .jinsom-bbs-single-download-title').html($('h1.jinsom-bbs-single-download-title').html());
$('.jinsom-bbs-content-header-fixed .jinsom-bbs-download-box').html($('.jinsom-bbs-sidebar-download-box .jinsom-bbs-download-box').html());
$('.jinsom-bbs-content-header-fixed .info,.jinsom-bbs-content-header-fixed .tips,.jinsom-bbs-content-header-fixed .like,.jinsom-bbs-content-header-fixed .download-more').remove();
}else{
$('.jinsom-bbs-content-header-fixed').hide();
}
});
if(jinsom.is_login){
$('html').on('click','.jinsom-bbs-content-header-fixed .collect',function(event){
dom=$('.jinsom-bbs-sidebar-download-box .collect');
if(dom.children('i').hasClass('jinsom-shoucang')){
dom.children('i').addClass('jinsom-shoucang1').removeClass('jinsom-shoucang').siblings('p').text(dom.attr('a'));
}else{
dom.children('i').addClass('jinsom-shoucang').removeClass('jinsom-shoucang1').siblings('p').text(dom.attr('b'));
}
});
}
$('.jinsom-bbs-download-box .download-more').click(function(){
$(this).children('.content').show();
});
</script>
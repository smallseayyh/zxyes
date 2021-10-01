<?php 
//话题设置
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$topic_name=jinsom_get_option('jinsom_topic_name');//话题名称

if(jinsom_is_admin($user_id)){//判断管理员权限
//论坛id
$topic_id=$_POST['topic_id'];
$data_type=get_term_meta($topic_id,'topic_data_type',true);
$desc=get_term_meta($topic_id,'topic_desc',true);
$topic_avatar=get_term_meta($topic_id,'bbs_avatar',true);
$topic_bg=get_term_meta($topic_id,'topic_bg',true);
$mobile_topic_bg=get_term_meta($topic_id,'mobile_topic_bg',true);


//seo
$seo_title=get_term_meta($topic_id,'bbs_seo_title',true);
$seo_desc=get_term_meta($topic_id,'bbs_seo_desc',true);
$seo_keywords=get_term_meta($topic_id,'bbs_seo_keywords',true);
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
$power=get_term_meta($topic_id,'power',true);
$power_user=get_term_meta($topic_id,'power_user',true);

//广告
$ad_header=get_term_meta($topic_id,'bbs_ad_header',true);
$ad_footer=get_term_meta($topic_id,'bbs_ad_footer',true);



$menu=get_term_meta($topic_id,'menu',true);
if(!$menu){$menu='all,commend,words,music,single,video,bbs,pay';}
$menu_arr=explode(",",$menu);
?>


<div class="jinsom-topic-setting-form layui-form layui-form-pane">

<form id="jinsom-topic-setting-form">

<div class="layui-tab layui-tab-brief" style="margin-top:0;">
<ul class="layui-tab-title">
<li class="layui-this">基本设置</li>
<li>权限设置</li>
<li>SEO设置</li>
<li>广告设置</li>
</ul>
<div class="layui-tab-content" style="padding:0;padding-top:30px;">

<div class="layui-tab-item layui-show">
<div class="jinsom-topic-setting-avatar">
<?php echo jinsom_get_bbs_avatar($topic_id,1);?>
<span>点击上传头像</span>
</div>	

<p style="text-align:  center;margin-bottom: 20px;font-size: 12px;color:  #999;"><?php echo $topic_name;?>头像必须是正方形的，因为很多地方需要调用</p>

<div class="layui-form-item">
<label class="layui-form-label"><?php echo $topic_name;?>头像</label>
<div class="layui-input-block">
<input type="text" name="topic-avatar" class="jinsom-bbs-avatar-input layui-input" value="<?php echo $topic_avatar;?>" placeholder="https://">
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">电脑端封面</label>
<div class="layui-input-block">
<input type="text" name="topic-bg" class="layui-input" value="<?php echo $topic_bg;?>" placeholder="https://">
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">手机端封面</label>
<div class="layui-input-block">
<input type="text" name="mobile_topic_bg" class="layui-input" value="<?php echo $mobile_topic_bg;?>" placeholder="https://">
</div>
</div>

<div class="layui-form-item layui-form-text">
<label class="layui-form-label"><?php echo $topic_name;?>描述</label>
<div class="layui-input-block">
<textarea name="desc" class="layui-textarea"><?php echo $desc;?></textarea>
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php echo $topic_name;?>浏览量</label>
<div class="layui-input-block">
<input type="text" name="topic-views" class="layui-input" value="<?php echo $topic_views;?>">
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">默认显示</label>
<div class="layui-input-inline">
<select name="data_type">
<option value="all" <?php if($data_type=='all'){echo 'selected="selected"';}?>><?php _e('全部','jinsom');?></option>
<option value="commend" <?php if($data_type=='commend'){echo 'selected="selected"';}?>><?php _e('推荐','jinsom');?></option>
<option value="words" <?php if($data_type=='words'){echo 'selected="selected"';}?>><?php _e('动态','jinsom');?></option>
<option value="music" <?php if($data_type=='music'){echo 'selected="selected"';}?>><?php _e('音乐','jinsom');?></option>
<option value="video" <?php if($data_type=='video'){echo 'selected="selected"';}?>><?php _e('视频','jinsom');?></option>
<option value="single" <?php if($data_type=='single'){echo 'selected="selected"';}?>><?php _e('文章','jinsom');?></option>
</select>
</div>
</div> 

<div class="layui-form-item" pane="">
<label class="layui-form-label">菜单开关</label>
<div class="layui-input-block">
<input type="checkbox" name="all" lay-skin="primary" title="<?php _e('全部','jinsom');?>" <?php if(in_array("all",$menu_arr)){ echo 'checked=""';}?>>
<input type="checkbox" name="commend" lay-skin="primary" title="<?php _e('推荐','jinsom');?>" <?php if(in_array("commend",$menu_arr)){ echo 'checked=""';}?>>
<input type="checkbox" name="words" lay-skin="primary" title="<?php _e('动态','jinsom');?>" <?php if(in_array("words",$menu_arr)){ echo 'checked=""';}?>>
<input type="checkbox" name="music" lay-skin="primary" title="<?php _e('音乐','jinsom');?>" <?php if(in_array("music",$menu_arr)){ echo 'checked=""';}?>>
<input type="checkbox" name="single" lay-skin="primary" title="<?php _e('文章','jinsom');?>" <?php if(in_array("single",$menu_arr)){ echo 'checked=""';}?>>
<input type="checkbox" name="video" lay-skin="primary" title="<?php _e('视频','jinsom');?>" <?php if(in_array("video",$menu_arr)){ echo 'checked=""';}?>>
<input type="checkbox" name="bbs" lay-skin="primary" title="<?php _e('帖子','jinsom');?>" <?php if(in_array("bbs",$menu_arr)){ echo 'checked=""';}?>>
<input type="checkbox" name="pay" lay-skin="primary" title="<?php _e('付费','jinsom');?>" <?php if(in_array("pay",$menu_arr)){ echo 'checked=""';}?>>
</div>
</div>

</div>

<div class="layui-tab-item">

<div class="layui-form-item">
<label class="layui-form-label">使用权限</label>
<div class="layui-input-inline">
<select name="power" lay-filter="topic_power" id="jinsom-topic-power">
<option value="login" <?php if($power=='login'){echo 'selected="selected"';}?>>所有登录用户</option>
<option value="admin" <?php if($power=='admin'){echo 'selected="selected"';}?>>管理团队（官方）</option>
<option value="vip" <?php if($power=='vip'){echo 'selected="selected"';}?>>VIP用户</option>
<option value="verify" <?php if($power=='verify'){echo 'selected="selected"';}?>>认证用户</option>
<option value="user" <?php if($power=='user'){echo 'selected="selected"';}?>>指定用户</option>
</select>
</div>
</div> 

<div class="layui-form-item" <?php if($power!='user'){echo 'style="display: none;"';}?> id="jinsom-topic-power-user">
<label class="layui-form-label">指定用户</label>
<div class="layui-input-block">
<textarea placeholder="请输入用户ID，多个用户请用英文逗号隔开" class="layui-textarea" name="power_user"><?php echo $power_user;?></textarea>
</div>
</div>

</div>

<div class="layui-tab-item">
<div class="layui-form-item">
<label class="layui-form-label">标题</label>
<div class="layui-input-block">
<input type="text" name="seo-title" class="layui-input" value="<?php echo $seo_title;?>">
</div>
</div>

<div class="layui-form-item layui-form-text">
<label class="layui-form-label">描述</label>
<div class="layui-input-block">
<textarea name="seo-desc" class="layui-textarea"><?php echo $seo_desc;?></textarea>
</div>
</div>

<div class="layui-form-item layui-form-text">
<label class="layui-form-label">关键词</label>
<div class="layui-input-block">
<textarea name="seo-keywords" class="layui-textarea"><?php echo $seo_keywords;?></textarea>
</div>
</div>

</div>

<div class="layui-tab-item">
<div class="layui-form-item">
<label class="layui-form-label">列表顶部</label>
<div class="layui-input-block">
<textarea placeholder="请输入广告代码，支持html" class="layui-textarea" name="ad_header" style="height:350px;"><?php echo $ad_header;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">列表底部</label>
<div class="layui-input-block">
<textarea placeholder="请输入广告代码，支持html" class="layui-textarea" name="ad_footer" style="height:350px;"><?php echo $ad_footer;?></textarea>
</div>
</div>
</div>


</div>
</div> 





<input type="hidden" value="<?php echo $topic_id;?>" name="topic_id"></input>
</form>


</div>


<?php }//判断管理员权限 ?>
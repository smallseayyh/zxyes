<?php 
//论坛设置
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$bbs_name=jinsom_get_option('jinsom_bbs_name');//论坛名称

//论坛id
$bbs_id=$_POST['bbs_id'];
$category=get_category($bbs_id);
$cat_parents=$category->parent;//父级论坛id
$admin_a=get_term_meta($cat_parents,'bbs_admin_a',true);
$admin_a_arr=explode(",",$admin_a);
if(jinsom_is_admin($user_id)||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){//管理员、网站管理、大版主

$category_name=get_category($bbs_id)->name;
$category_slug=get_category($bbs_id)->slug;
$category_desc=get_term_meta($bbs_id,'desc',true);//论坛描述
$category_rank=(int)strip_tags(category_description($bbs_id));//排名

//seo
$bbs_seo_title=get_term_meta($bbs_id,'bbs_seo_title',true);
$bbs_seo_desc=get_term_meta($bbs_id,'bbs_seo_desc',true);
$bbs_seo_keywords=get_term_meta($bbs_id,'bbs_seo_keywords',true);
?>


<div class="jinsom-bbs-setting-form layui-form layui-form-pane">

<form id="jinsom-bbs-setting-form">


<div class="layui-tab layui-tab-brief" style="margin-top:0;">
<ul class="layui-tab-title">
<li class="layui-this">基本设置</li>
<li>SEO设置</li>
</ul>
<div class="layui-tab-content" style="padding:0;padding-top:30px;">
<div class="layui-tab-item layui-show">
<div class="jinsom-bbs-child-setting-avatar">
<?php echo jinsom_get_bbs_avatar($bbs_id,0);?>
<span>点击上传头像</span>
</div>	


<div class="layui-form-item">
<label class="layui-form-label">子<?php echo $bbs_name;?>头像</label>
<div class="layui-input-block">
<input type="text" name="avatar" placeholder="https:// 正方形即可" class="jinsom-bbs-avatar-input layui-input" value="<?php echo get_term_meta($bbs_id,'bbs_avatar',true);?>">
</div>
</div>


<div class="layui-form-item layui-form-text">
<label class="layui-form-label">子<?php echo $bbs_name;?>名称</label>
<div class="layui-input-block">
<input type="text" name="jinsom-bbs-name" class="layui-input" value="<?php echo $category_name;?>">
</div>
</div>


<div class="layui-form-item layui-form-text">
<label class="layui-form-label">路径slug</label>
<div class="layui-input-block">
<input type="text" name="jinsom-bbs-slug" class="layui-input" value="<?php echo $category_slug;?>">
</div>
</div>

<div class="layui-form-item layui-form-text">
<label class="layui-form-label"><?php echo $bbs_name;?>排序（数值越大越往后，0最先）</label>
<div class="layui-input-block">
<input type="number" name="jinsom-bbs-rank" class="layui-input" value="<?php echo $category_rank;?>">
</div>
</div>

<div class="layui-form-item layui-form-text">
<label class="layui-form-label"><?php echo $bbs_name;?>说明</label>
<div class="layui-input-block">
<textarea name="jinsom-bbs-desc" class="layui-textarea"><?php echo $category_desc;?></textarea>
</div>
</div>
</div>

<div class="layui-tab-item">
<div class="layui-form-item">
<label class="layui-form-label">标题</label>
<div class="layui-input-block">
<input type="text" name="jinsom-bbs-seo-title" class="layui-input" value="<?php echo $bbs_seo_title;?>">
</div>
</div>

<div class="layui-form-item layui-form-text">
<label class="layui-form-label">描述</label>
<div class="layui-input-block">
<textarea name="jinsom-bbs-seo-desc" class="layui-textarea"><?php echo $bbs_seo_desc;?></textarea>
</div>
</div>

<div class="layui-form-item layui-form-text">
<label class="layui-form-label">关键词</label>
<div class="layui-input-block">
<textarea name="jinsom-bbs-seo-keywords" class="layui-textarea" placeholder="请用英文逗号隔开"><?php echo $bbs_seo_keywords;?></textarea>
</div>
</div>

</div>

</div>
</div> 


<input type="hidden" value="<?php echo $bbs_id;?>" name="bbs_id"></input>
</form>



</div>


<?php }//判断管理员权限 ?>
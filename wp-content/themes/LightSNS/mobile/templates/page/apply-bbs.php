<?php 
//论坛申请
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$bbs_name=jinsom_get_option('jinsom_bbs_name');
?>
<div data-page="apply-bbs" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $bbs_name;?>申请</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-apply-bbs-content">
<li><label><?php echo $bbs_name;?>名称：</label>
<input type="text" class="title" id="jinsom-apply-bbs-title">
</li>
<li><label>申请原因：</label>
<textarea id="jinsom-apply-bbs-reason"></textarea>
</li>
<div class="btn opacity" onclick="jinsom_apply_bbs(<?php echo $bbs_id;?>)"><?php _e('提交申请','jinsom');?></div>
</div>
</div>        
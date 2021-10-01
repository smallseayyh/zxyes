<?php 
//当前活跃用户
require( '../../../../../../wp-load.php');
$url=strip_tags($_GET['link']);
$url=str_replace("$$$","&",$url);
?>
<div data-page="url" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $url;?></div>
<div class="right">
<a href="#" class="link icon-only" onclick="jinsom_url_page_more('<?php echo $url;?>')"><i class="jinsom-icon jinsom-gengduo2" style="font-size: 8vw;"></i></a>
</div>
</div>
</div>

<div class="page-content">
<?php if($url){?>
<iframe src="<?php echo $url;?>" frameborder="0" style="width:100%;height:100%;" id="jinsom-iframe-url"></iframe>
<?php }else{?>
无效链接
<?php }?>
</div>

</div>
</div>        
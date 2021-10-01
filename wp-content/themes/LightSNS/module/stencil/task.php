<?php
//任务表单
require( '../../../../../wp-load.php' );

require( get_template_directory() . '/module/stencil/task-html.php' );
?>


<script type="text/javascript">
$('.jinsom-task-form-content .header li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$(this).parent().next().children('ul').eq($(this).index()).show().siblings().hide();
});
</script>
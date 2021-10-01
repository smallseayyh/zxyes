<?php
//宝箱任务表单
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$task_id=strip_tags($_POST['task_id']);
$credit_name=jinsom_get_option('jinsom_credit_name');//金币名称
$jinsom_task_treasure_add=jinsom_get_option('jinsom_task_treasure_add'); 
require(get_template_directory().'/module/stencil/task-treasure-html.php');
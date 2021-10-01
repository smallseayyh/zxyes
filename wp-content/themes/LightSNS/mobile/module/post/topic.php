<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=$_POST['page'];
if(!isset($_POST['page'])){$page=1;}
$type=$_POST['type'];
$topic_id=$_POST['topic_id'];
$number = get_option('posts_per_page', 10);
$offset=($page-1)*$number;

if($type=='all'){//全部
$args = array(
'post_status' => array('publish'),
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);
}else if($type=='commend'){//推荐
$args = array(
'post_status' => array('publish'),
'meta_key' => 'jinsom_commend',
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}else if($type=='words'){//动态
$args = array(
'post_status' => array('publish'),
'meta_key' => 'post_type',
'meta_value'=>$type,
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}else if($type=='music'){//音乐
$args = array(
'post_status' => array('publish'),
'meta_key' => 'post_type',
'meta_value'=>$type,
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}else if($type=='video'){//视频
$args = array(
'post_status' => array('publish'),
'meta_key' => 'post_type',
'meta_value'=>$type,
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}else if($type=='single'){//文章
$args = array(
'post_status' => array('publish'),
'meta_key' => 'post_type',
'meta_value'=>$type,
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}else if($type=='bbs'){//帖子
$args = array(
'post_status' => array('publish'),
'post_parent'=>999999999,
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}else if($type=='pay'){//付费
$args = array(
'post_status' => array('publish'),
'meta_key' => 'post_price',
'showposts' => $number,
'offset' => $offset,
'tag__in' => array($topic_id)
);	
}


$args['no_found_rows']=true;
query_posts($args);
if (have_posts()){
while (have_posts()):the_post();
require(get_template_directory().'/mobile/templates/post/post-list.php' );	
endwhile;
}else{
echo 0;
}

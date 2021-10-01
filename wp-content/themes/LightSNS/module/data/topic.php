<?php 
//获取话题数据
require( '../../../../../wp-load.php' );
if(isset($_POST['type'])){
$type=$_POST['type'];
$topic_id=$_POST['topic_id'];
$topic_data=get_term_by('id',$topic_id,'post_tag');
$topic_number=$topic_data->count;
$paged = $_POST['page'];
if(empty($paged)){$paged=1;}
$number = get_option('posts_per_page', 10);
$offset = ($paged-1)*$number;

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
while (have_posts()) : the_post();
require(get_template_directory().'/post/post-list.php');
endwhile;
if(ceil($topic_number/$number)>=2&&!isset($_POST['page'])){
echo '<div class="jinsom-more-posts" data="2" onclick=\'jinsom_topic_data_more("'.$type.'",this);\'>加载更多</div>';	
}
}else{
if(isset($_POST['page'])){//加载更多
echo 0;
}else{
echo jinsom_empty();	
}
}
}
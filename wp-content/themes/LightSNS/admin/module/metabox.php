<?php if(!defined('ABSPATH')){die;}
$theme_url=get_template_directory_uri();

//自定义小工具
$custom_sidebar_number=(int)jinsom_get_option('jinsom_custom_sidebar_number');//小工具数量
$custom_sidebar_arr=array();
$custom_sidebar_arr['no']='不显示侧栏';
for ($i=1; $i <= $custom_sidebar_number; $i++) { 
$custom_sidebar_arr[$i]='自定义小工具-'.$i;
}

////====SEO配置
$page_prefix = 'page_option';
LightSNS::createMetabox($page_prefix, array(
'title'        => 'SEO设置',
'post_type'    => 'page',
'context'   => 'side',
'priority'   => 'high',
));

LightSNS::createSection($page_prefix, array(
'fields' => array(

array(
'id'         => 'jinsom_page_seo_title',
'type'       => 'text',
'title'      => '页面SEO标题',
),

array(
'id'         => 'jinsom_page_seo_keyword',
'type'       => 'textarea',
'title'      => '页面SEO关键词',
),

array(
'id'         => 'jinsom_page_seo_description',
'type'       => 'textarea',
'title'      => '页面SEO描述',
),

)
));


////====数据备份/导入
$case_back_prefix = 'case_back';
LightSNS::createMetabox($case_back_prefix, array(
'title'        => '数据备份/导入',
'post_type'    => 'page',
'context'   => 'side',
'priority'   => 'high',
// 'page_templates' => 'page/case.php',
));


LightSNS::createSection($case_back_prefix, array(
'fields' => array(

array(
'type' => 'backup_metabox',
),

)
));






////====案例页面配置
$case_prefix = 'case_option';
LightSNS::createMetabox($case_prefix, array(
'title'        => '案例模块配置',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/case.php',
));


LightSNS::createSection($case_prefix, array(
'fields' => array(

array(
'id'         => 'case_mobile_title_name',
'type'       => 'text',
'title'      => '移动端头部名称',
'default'    => '案例'
),

array(
'id' => 'jinsom_data_add',
'type' => 'group',
'title' => '配置数据',
'button_title' => '新增菜单',
'fields' => array(

array(
'id'         => 'menu_name',
'type'       => 'text',
'title'      => '菜单名称',
),

array(
'id' => 'case_add',
'type' => 'group',
'title' => '添加案例',
'button_title' => '添加案例',
'subtitle' => '当前菜单需要展示的案例',
'fields' => array(

array(
'id'         => 'title',
'type'       => 'text',
'title'      => '案例名称',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '案例封面',
'desc'=>'高度190px，宽度自己根据自己网站宽度去计算',
'placeholder' => 'http://'
),

array(
'id' => 'link',
'type' => 'text',
'title' => '案例链接',
'subtitle' => '支持短代码',
'placeholder' => 'https://'
),

array(
'id' => 'app',
'type' => 'switcher',
'default' => false,
'title' => 'app形式打开',
),

array(
'id' => 'hide',
'type' => 'switcher',
'default' => false,
'title' => '隐藏',
'desc' => '启用后，将暂时对外展示',
),

array(
'id' => 'nofollow',
'type' => 'switcher',
'default' => false,
'title' => 'nofollow',
), 

array(
'id'         => 'desc',
'type'       => 'textarea',
'title'      => '案例描述',
),

)
),


)
), 

array(
'id'         => 'case_join_name',
'type'       => 'text',
'title'      => '电脑端案例申请名称',
'default'    => '申请加入'
),

array(
'id'         => 'case_join_url',
'type'       => 'text',
'title'      => '电脑端案例申请链接',
'placeholder' => 'https://'
),


array(
'id'         => 'mobile_case_join_name',
'type'       => 'text',
'title'      => '移动端案例申请名称',
'subtitle'      => '不要超过2个字，留空则不显示',
'default'    => '申请'
),

array(
'id'         => 'mobile_case_join_url',
'type'       => 'text',
'title'      => '移动端案例申请链接',
'subtitle' => '支持短代码'
),




array(
'id' => 'header_ad',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端头部自定义内容',
'subtitle' => '支持html',
),

array(
'id' => 'footer_ad',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端底部自定义内容',
'subtitle' => '支持html',
),



)
));







////====论坛大厅
$bbs_show_page_prefix = 'bbs_show_page_data';
LightSNS::createMetabox($bbs_show_page_prefix, array(
'title'        => '论坛大厅页面配置',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/layout-bbs.php',
));


LightSNS::createSection($bbs_show_page_prefix, array(
'fields' => array(

array(
'id'         => 'jinsom_bbs_mobile_header_name',
'type'       => 'text',
'title'      => '移动端头部名称',
'default'    =>'论坛大厅',
),

array(
'id'         => 'jinsom_bbs_header_height',
'type'       => 'spinner',
'unit'       => 'px',
'title'      => '电脑端头部模块高度',
'default'    =>350,
),


array(
'id'            => 'jinsom_bbs_header',
'type'          => 'tabbed',
'title'         => '头部模块',
'tabs'          => array(
array(
'title'     => '幻灯片',
'fields'    => array(

array(
'id' => 'jinsom_bbs_slider_on_off',
'type' => 'switcher',
'default' => false,
'title' => '电脑端幻灯片',
),

array(
'id'      => 'jinsom_bbs_slider_width',
'dependency' => array('jinsom_bbs_slider_on_off','==','true'),
'type'    => 'slider',
'title'   => '电脑端幻灯片宽度',
'min'     => 20,
'max'     => 60,
'step'    => 1,
'unit'    => '%',
'default' => 40,
),

array(
'id' => 'jinsom_bbs_slider_add',
'dependency' => array('jinsom_bbs_slider_on_off','==','true'),
'type' => 'group',
'title' => '论坛大厅幻灯片',
'button_title' => '添加电脑端幻灯片',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '标题',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'placeholder' => 'https://',
'subtitle'=>'建议尺寸：492px*280px',
),

array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'default' =>'#',
'placeholder' => 'https://'
),


)
),


array(
'id' => 'jinsom_mobile_bbs_slider_on_off',
'type' => 'switcher',
'default' => false,
'title' => '移动端幻灯片',
),

array(
'id' => 'jinsom_mobile_bbs_slider_height',
'type'       => 'spinner',
'unit'       => 'vw',
'title' => '幻灯片高度',
'default' =>35,
'dependency' => array('jinsom_mobile_bbs_slider_on_off','==','true'),
'subtitle' => '1vw相当于1/100屏幕宽度',
),

array(
'id' => 'jinsom_mobile_bbs_slider_add',
'type' => 'group',
'title' => '移动端幻灯片',
'dependency' => array('jinsom_mobile_bbs_slider_on_off','==','true'),
'subtitle' => '建议最少添加三个',
'button_title' => '添加移动端幻灯片',
'fields' => array(

array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'desc'=>'支持短代码获取对应类型的内容地址、论坛地址、话题地址等等。短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a></br>如果填写外链地址则要关掉app形式打开',
'placeholder' => '[post_link id=123]'
),


array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'placeholder' => 'http://'
),

array(
'id'      => 'desc',
'type'    => 'textarea',
'title'   => '描述',
),

array(
'id' => 'jinsom_mobile_bbs_slider_add_app',
'type' => 'switcher',
'default' => true,
'title' => 'app形式打开',
'desc'  => '开启之后则以app的形式打开站内链接，如果需要链接到外部地址则关掉这个开关。',
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口打开',
'dependency' => array('jinsom_mobile_bbs_slider_add_app','==','false'),
),

)
), 


)
),
array(
'title'     => '内容列表',
'fields'    => array(

array(
'id' => 'jinsom_bbs_list_on_off',
'type' => 'switcher',
'default' => false,
'title' => '内容列表',
'subtitle' => '该设置与移动端同步',
),


array(
'id' => 'jinsom_bbs_list_data_type',
'type' => 'radio',
'title' => '数据类型',
'options' => array(
'a' => '全站的论坛数据',
'b' => '指定的论坛数据',
),
'default' => 'a',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'desc' => '这里指的是最新、热门、精品、付费、问答、活动、投票的数据',
),

array(
'id'         => 'list_number',
'type'       => 'spinner',
'unit'       => '篇',
'title'      => '电脑端显示数量',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'default'    =>20,
),

array(
'id'         => 'mobile_list_number',
'type'       => 'spinner',
'unit'       => '篇',
'title'      => '移动端显示数量',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'default'    =>5,
),

array(
'id'             => 'list',
'type'           => 'sorter',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'title'          => '需要展示的内容',
'default'        => array(
'enabled'      => array(
'new'        => '最新帖子',
'hot'   => '热门帖子',
'nice' => '精品帖子',
'pay' => '付费帖子',
'answer' => '问答帖子',
),
'disabled'     => array(
'activity' => '活动帖子',
'vote' => '投票帖子',
'custom_1' => '自定义列表-1',
'custom_2' => '自定义列表-2',
'custom_3' => '自定义列表-3',
),
),
),




array(
'id'      => 'jinsom_bbs_list_data_type_bbs_id',
'type'    => 'checkbox',
'dependency' => array('jinsom_bbs_list_data_type','==','b'),
'title'=> '指定的论坛数据',
'options' => 'categories',
'query_args'=>array(
'exclude'=>array(1),
'hide_empty'=>false
)
),


array(
'id'      => 'custom_bbs_a',
'type'    => 'checkbox',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'title'=> '自定义列表-1展示的数据',
'options' => 'categories',
'query_args'=>array(
'exclude'=>array(1),
'hide_empty'=>false
)
),

array(
'id'      => 'custom_bbs_b',
'type'    => 'checkbox',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'title'=> '自定义列表-2展示的数据',
'options' => 'categories',
'query_args'=>array(
'exclude'=>array(1),
'hide_empty'=>false
)
),

array(
'id'      => 'custom_bbs_c',
'type'    => 'checkbox',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'title'=> '自定义列表-3展示的数据',
'options' => 'categories',
'query_args'=>array(
'exclude'=>array(1),
'hide_empty'=>false
)
),


array(
'id'         => 'custom_a_name',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'type'       => 'text',
'title'      => '自定义列表-1',
'subtitle'       => '自定义列表-1菜单名称',
'default'    =>'自定义-1',
),

array(
'id'         => 'custom_b_name',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'type'       => 'text',
'title'      => '自定义列表-2',
'subtitle'       => '自定义列表-2菜单名称',
'default'    =>'自定义-2',
),

array(
'id'         => 'custom_c_name',
'dependency' => array('jinsom_bbs_list_on_off','==','true'),
'type'       => 'text',
'title'      => '自定义列表-3',
'subtitle'       => '自定义列表-3菜单名称',
'default'    =>'自定义-3',
),


)
),
)
),


array(
'id'         => 'jinsom_bbs_active_user_number',
'type'       => 'spinner',
'unit'       => '个',
'title'      => '活跃用户展示数量',
'subtitle'   => '如果不显示活跃用户，则将数量设置为0即可，仅仅电脑端展示',
'default'    =>44,
),

array(
'id' => 'jinsom_bbs_number_count',
'type' => 'switcher',
'default' => false,
'title' => '统计展示',
'desc' => '开启之后将显示全站内容数和用户数',
),


array(
'id'                 => 'jinsom_bbs_sidebar',
'type'               => 'select',
'title'              => '论坛大厅侧栏',
'options'            => $custom_sidebar_arr,
'default'       =>'no',
),


array(
'id' => 'jinsom_bbs_show_myfollow',
'type' => 'switcher',
'default' => false,
'title' => '关注的论坛',
'desc' => '开启之后将显示"我关注的论坛"板块，仅仅电脑端展示',
),


array(
'id' => 'jinsom_bbs_show_add',
'type' => 'group',
'title' => '论坛板块设置',
'button_title' => '配置',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'dependency' => array('jinsom_bbs_show_add_type','==','a'),
'title' => '标题',
),

array(
'id' => 'jinsom_bbs_show_add_type',
'type' => 'radio',
'title' => '类型',
'options' => array(
'a' => '论坛版块',
'b' => '自定义内容',
),
'default' => 'a',
),


array(
'id' => 'bbs',
'type' => 'textarea',
'dependency' => array('jinsom_bbs_show_add_type','==','a'),
'title' => '论坛ID',
'subtitle'=>'填写需要展示的论坛ID，用英文逗号隔开，查看论坛ID方法：内容管理->论坛管理',
'placeholder' => '1,2,3,4,5,6'
),


array(
'id' => 'more_name',
'type' => 'text',
'dependency' => array('jinsom_bbs_show_add_type','==','a'),
'title' => '“更多”的名称',
'subtitle' => '留空则电脑端不显示更多按钮',
),

array(
'id' => 'more_link',
'type' => 'text',
'dependency' => array('jinsom_bbs_show_add_type','==','a'),
'title' => '“更多”的链接',
'placeholder' => 'https://',
),

array(
'id' => 'mobile_more_link',
'type' => 'text',
'dependency' => array('jinsom_bbs_show_add_type','==','a'),
'title' => '移动端“更多”的链接',
'subtitle' => '支持短代码',
'placeholder' => 'https:// 留空则移动端不展示',
),

array(
'id' => 'more_target',
'type' => 'switcher',
'default' => false,
'dependency' => array('jinsom_bbs_show_add_type','==','a'),
'title' => '“更多”按钮新窗口打开',
'desc' => '只针对电脑端，开启之后，点击更多按钮将新窗口打开',
),


array(
'id' => 'ad',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'dependency' => array('jinsom_bbs_show_add_type','==','b'),
'title' => '电脑端自定义内容（支持html）',
'subtitle'=>'一般用于展示广告横幅，展示图片等等，【不支持输入js代码】，电脑端自动使用这里的自定义代码',
),

array(
'id' => 'ad_mobile',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'dependency' => array('jinsom_bbs_show_add_type','==','b'),
'title' => '移动端自定义内容（支持html）',
'subtitle'=>'一般用于展示广告横幅，展示图片等等，【不支持输入js代码】，移动端自动使用这里的自定义代码',
),


)
), //论坛板块添加结束


array(
'id' => 'jinsom_mobile_bbs_slider_bottom_ad',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '移动端幻灯片底部自定义区',
'subtitle'=>'一般用于展示广告横幅，展示图片等等',
),



)
));




////====文章/帖子专题
$single_show_page_prefix = 'single_show_page_data';
LightSNS::createMetabox($single_show_page_prefix, array(
'title'        => '文章/帖子专题页面配置',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/single.php',
));


LightSNS::createSection($single_show_page_prefix, array(
'fields' => array(

array(
'id'          => 'jinsom_single_special_slider_style',
'type'        => 'radio',
'title'       => '文章专题头部布局',
'subtitle'    => '目前一共有十种布局，具体可以点击这里查看具体<a href="#" target="_blank">《文章专题头部布局说明》</a>',
'options'     => array(
'one'         => '布局一',
'two'         => '布局二',
'three'       => '布局三',
'four'        => '布局四',
'five'        => '布局五',
'six'         => '布局六-纯幻灯',
'seven'       => '布局七',
'eight'       => '布局八',
'nine'        => '布局九',
'ten'         => '布局十-必须开启幻灯片',
),
'default'     =>'one',
),

array(
'id'         => 'jinsom_single_special_slider_height',
'type'       => 'spinner',
'title'      => '文章专题头部高度',
'subtitle'      => '等同于幻灯片高度',
'default'       => 400,
'unit'=>'px',
),

array(
'id' => 'jinsom_single_special_slider_on_off',
'type' => 'switcher',
'default' => false,
'title' => '文章专题幻灯片',
),


array(
'id' => 'jinsom_single_special_slider_add',
'type' => 'group',
'title' => '文章专题幻灯片',
'dependency' => array('jinsom_single_special_slider_on_off','==','true'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '标题',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'placeholder' => 'https://',
'subtitle'=>'高度305px，宽度默认550px，如果全屏则以全屏宽度为准',
),


array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'default' =>'#',
'placeholder' => 'https://'
),

)
), 


array(
'id'    => 'jinsom_single_special_slider_width',
'type'  => 'slider',
'title' => '文章专题幻灯片宽度',
'subtitle' => '若选择布局六和布局十宽度是100%，不需要进行设置',
'dependency' => array('jinsom_single_special_slider_style|jinsom_single_special_slider_style|jinsom_single_special_slider_on_off','!=|!=|==','six|ten|true'),
'default'=>50,
'unit'=>'%',
),


array(
'id'         => 'jinsom_single_special_slider_box_space',
'type'       => 'spinner',
'title'      => '幻灯片/格子间的间隙',
'subtitle' => '文章专题幻灯片',
'dependency' => array('jinsom_single_special_slider_style','!=','six'),
'default'       => 15,
'unit'=>'px',
),

array(
'id' => 'jinsom_single_special_slider_autoplay',
'dependency' => array('jinsom_single_special_slider_on_off','==','true'),
'type' => 'switcher',
'default' => false,
'title' => '幻灯片自动播放',
'subtitle' => '文章专题幻灯片',
),

array(
'id' => 'jinsom_single_special_slider_pagination',
'dependency' => array('jinsom_single_special_slider_on_off','==','true'),
'type' => 'switcher',
'default' => true,
'title' => '幻灯片分页器',
'subtitle' => '文章专题幻灯片',
),

array(
'id' => 'jinsom_single_special_slider_navigation',
'dependency' => array('jinsom_single_special_slider_on_off','==','true'),
'type' => 'switcher',
'default' => true,
'title' => '幻灯片前进后退按钮',
'subtitle' => '文章专题幻灯片',
),

array(
'id'     => 'jinsom_single_special_slider_effect',
'type'   => 'select',
'title'  => '幻灯片动画效果',
'subtitle' => '文章专题幻灯片',
'options'=> array(
'slide'=>'普通切换',
'fade'=>'淡出淡入',
'coverflow'=>'影片切换',
'flip'=>'翻转切换',
),
'default'=>'slide',
),


array(
'id'                 => 'jinsom_single_special_header_commend',
'type'               => 'radio',
'title'              => '幻灯片右侧/底部格子内容展示',
'subtitle' => '文章专题幻灯片右侧内容展示,不同布局展示的格子数量不一样，最多显示6条数据，一般手动设置。',
'dependency' => array('jinsom_single_special_slider_style','!=','six'),
'options'            => array(
'new'         => '最新的文章',
'new-bbs'         => '最新的帖子',
'comment'           => '一个月内评论量最多的文章',
'comment-bbs'           => '一个月内评论量最多的帖子',
'views'             => '随机一个月内浏览量最多的文章',
'views-bbs'             => '随机一个月内浏览量最多的帖子',
'custom'             => '指定的文章ID',
'set'             => '手动设置数据',
),
'default'       =>'new',
),

array(
'id' => 'jinsom_single_special_header_commend_custom',
'type' => 'textarea',
'dependency' => array('jinsom_single_special_header_commend','==','custom'),
'title' => '手动输入文章ID',
'subtitle' => '请根据你设置的头部布局来设置对应的文章数量，最大数量不能超过6篇',
'placeholder' => '1,2,3,4,5,6'
),

array(
'id' => 'jinsom_single_special_header_commend_set',
'type' => 'group',
'title' => '手动添加数据',
'dependency' => array('jinsom_single_special_header_commend','==','set'),
'subtitle' => '请根据你设置的头部布局来设置对应的文章数量，最大数量不能超过6篇',
'button_title' => '添加数据',
'max'=>6,
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '标题',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'placeholder' => 'https://',
'subtitle'=>'高度305px，宽度默认550px，如果全屏则以全屏宽度为准',
),


array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'default' =>'#',
'placeholder' => 'https://'
),

)
), 


array(
'id' => 'jinsom_single_special_header_commend_title',
'type' => 'switcher',
'dependency' => array('jinsom_single_special_slider_style','!=','six'),
'default' => true,
'title' => '推荐内容显示标题',
'subtitle' => '文章专题幻灯片右侧格子推荐内容是否显示标题',
),



array(
'id'                 => 'jinsom_single_special_sidebar',
'type'               => 'select',
'title'              => '文章专题侧栏',
'options'            => $custom_sidebar_arr,
'default'       =>'no',
),



array(
'id' => 'jinsom_single_special_content_data_add',
'type' => 'group',
'title' => '添加模块数据',
'button_title' => '添加数据',
'fields' => array(

array(
'id'    => 'mark',
'type'  => 'text',
'title' => '备注说明项',
'placeholder' => '这里是填写备注说明，可以为空',
'subtitle' => '不对外展示',
),


array(
'id'     => 'jinsom_single_special_content_data_add_type',
'type'   => 'select',
'title'  => '模块展示类型',
'subtitle' => '文章专题模块',
'options'=> array(
'content'=>'内容数据展示',
'user'=>'用户数据展示',
'html'=>'自定义html',
),
'default'=>'content',
),



array(
'id'     => 'jinsom_single_special_module_menu_style',
'type'   => 'select',
'title'  => '模块头部类型',
'subtitle' => '文章专题模块头部',
'dependency' => array('jinsom_single_special_content_data_add_type','==','content'),
'options'=> array(
'one'=>'不显示头部',
'two'=>'显示文本标题',
'three'=>'显示子菜单-样式a',
'four'=>'显示子菜单-样式b',
'five'=>'显示子菜单-样式c',
),
'default'=>'two',
),

array(
'id'    => 'header_title',
'type'  => 'text',
'title' => '模块头部显示的标题',
'default' => '我是一个标题',
'subtitle' => '支持html',
'dependency' => array('jinsom_single_special_module_menu_style|jinsom_single_special_content_data_add_type','==|!=','two|html'),
),


array(
'id'     => 'jinsom_single_special_module_no_menu_data',
'type'   => 'select',
'title'  => '模块默认展示的数据',
'subtitle' => '如果设置了子菜单，则默认调用第一个子菜单的数据',
'dependency' => array('jinsom_single_special_module_menu_style|jinsom_single_special_module_menu_style|jinsom_single_special_module_menu_style|jinsom_single_special_content_data_add_type','!=|!=|!=|==','three|four|five|content'),
'options'=> array(
'no_menu_topic'=>'指定话题的文章',
'no_menu_bbs'=>'指定论坛的帖子',
'commend_single'=>'推荐的文章',
'nice_bbs'=>'加精的帖子',
'new_single'=>'最新的文章',
'new_bbs'=>'最新的帖子',
),
'default'=>'new_single',
),


array(
'id'    => 'no_menu_data_topic',
'type'  => 'textarea',
'dependency' => array('jinsom_single_special_module_no_menu_data|jinsom_single_special_module_menu_style|jinsom_single_special_module_menu_style|jinsom_single_special_content_data_add_type','==|!=|!=|==','no_menu_topic|three|four|content'),
'title' => '指定话题ID的文章',
'subtitle' => '多个话题ID请用英文逗号隔开',
),

array(
'id'    => 'no_menu_data_bbs',
'type'  => 'textarea',
'dependency' => array('jinsom_single_special_module_no_menu_data|jinsom_single_special_module_menu_style|jinsom_single_special_module_menu_style|jinsom_single_special_content_data_add_type','==|!=|!=|==','no_menu_bbs|three|four|content'),
'title' => '指定论坛ID的帖子',
'subtitle' => '多个论坛ID请用英文逗号隔开',
),


array(
'id' => 'add',
'type' => 'group',
'title' => '添加头部子菜单',
'dependency' => array('jinsom_single_special_module_menu_style|jinsom_single_special_module_menu_style|jinsom_single_special_content_data_add_type','!=|!=|==','one|two|content'),
'button_title' => '添加',
'fields' => array(

array(
'id'    => 'title',
'type'  => 'text',
'title' => '菜单名称',
),


array(
'id'     => 'jinsom_single_special_module_menu_data',
'type'   => 'select',
'title'  => '模块头部布局',
'subtitle' => '文章专题模块头部',
'options'=> array(
'one'=>'指定话题的文章',
'two'=>'指定论坛的帖子',
),
'default'=>'one',
),


array(
'id'    => 'topic',
'type'  => 'textarea',
'dependency' => array('jinsom_single_special_module_menu_data','==','one'),
'title' => '指定话题ID的文章',
'subtitle' => '多个话题ID请用英文逗号隔开',
),

array(
'id'    => 'bbs',
'type'  => 'textarea',
'dependency' => array('jinsom_single_special_module_menu_data','==','two'),
'title' => '指定论坛ID的帖子',
'subtitle' => '多个论坛ID请用英文逗号隔开',
),


)
),


array(
'id' => 'more_btn_on_off',
'type' => 'switcher',
'dependency' => array('jinsom_single_special_module_menu_style|jinsom_single_special_content_data_add_type','!=|==','one|content'),
'default' => true,
'title' => '显示“更多”按钮',
),

array(
'id'    => 'more_btn_link',
'type'  => 'text',
'dependency' => array('jinsom_single_special_module_menu_style|jinsom_single_special_content_data_add_type|more_btn_on_off','!=|==|==','one|content|true'),
'title' => '更多按钮跳转的链接',
'placeholder' => 'https://',
),



array(
'id'     => 'content_style',
'type'   => 'select',
'title'  => '内容样式',
'subtitle' => '文章专题模块内容',
'dependency' => array('jinsom_single_special_content_data_add_type','==','content'),
'options'=> array(
'one'=>'格子-1',
'two'=>'格子-2',
'three'=>'矩形块',
),
'default'=>'one',
),



array(
'id'     => 'jinsom_single_special_content_user_data',
'type'   => 'select',
'title'  => '展示的用户数据',
'subtitle' => '文章专题模块内容',
'dependency' => array('jinsom_single_special_content_data_add_type','==','user'),
'options'=> array(
'new'=>'最新注册用户',
'rand'=>'随机所有用户',
'verify'=>'随机认证用户',
'vip'=>'随机VIP用户',
'honor'=>'随机头衔用户',
'custom'=>'随机指定的用户ID',
),
'default'=>'new',
),

array(
'id'    => 'jinsom_single_special_content_user_data_ids',
'type'  => 'textarea',
'dependency' => array('jinsom_single_special_content_data_add_type|jinsom_single_special_content_user_data','==|==','user|custom'),
'title' => '指定用户ID',
'subtitle' => '多个用户ID请用英文逗号隔开',
),


array(
'id'    => 'number',
'type'       => 'spinner',
'title' => '显示数量',
'dependency' => array('jinsom_single_special_content_data_add_type','!=','html'),
'default'       => 9,
),


array(
'id' => 'html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'dependency' => array('jinsom_single_special_content_data_add_type','==','html'),
'title' => '自定义代码',
'subtitle' => '一般用于广告展示',
),




)
),


array(
'id' => 'jinsom_single_special_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '文章/帖子专题头部自定义区域',
'subtitle' => '支持html',
), 


)
));



////====视频专题
$video_show_page_prefix = 'video_show_page_data';
LightSNS::createMetabox($video_show_page_prefix, array(
'title'        => '视频专题页面配置',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/video.php',
));


LightSNS::createSection($video_show_page_prefix, array(
'fields' => array(

array(
'id'         => 'jinsom_video_mobile_header_name',
'type'       => 'text',
'title'      => '移动端头部名称',
'default'    =>'视频',
),

array(
'id'                 => 'jinsom_video_commend',
'type'               => 'radio',
'title'              => '推荐模块的视频数据',
'subtitle'              => '视频专题头部幻灯片右侧的数据，只显示6个视频',
'options'            => array(
'new'         => '最新的视频',
'comment'           => '一个月内评论量最多的视频',
'views'             => '随机一个月内浏览量最多的视频',
'custom'             => '指定的视频',
),
'default'       =>'new',
),

array(
'id' => 'jinsom_video_commend_custom',
'type' => 'textarea',
'dependency' => array('jinsom_video_commend','==','custom'),
'title' => '手动输入视频文章ID，最多展示6个',
'subtitle' => '注意：只能输入视频类型的文章ID',
'placeholder' => '1,2,3,4,5,6'
),


array(
'id' => 'jinsom_video_autoplay_on_off',
'type' => 'switcher',
'default' => true,
'title' => '推荐视频自动播放',
'subtitle' => '开启之后，点击视频专题，头部推荐的视频会自动播放。',
),


array(
'id' => 'jinsom_video_special_add',
'type' => 'group',
'title' => '视频专题板块设置',
'button_title' => '配置',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'dependency' => array('jinsom_video_special_add_type','==','a'),
'title' => '标题',
'subtitle' => '可以在标题后加上一些自定义的小图标代码',
'desc' => '留空则不显示标题',
),




array(
'id' => 'more',
'type' => 'text',
'dependency' => array('jinsom_video_special_add_type','==','a'),
'title' => '更多的链接',
'subtitle' => '如果不填写则关闭，一般用于链接到指定的话题页面',
'placeholder' => 'https://',
),

array(
'id' => 'jinsom_video_special_add_type',
'type' => 'radio',
'title' => '类型',
'options' => array(
'a' => '显示指定话题的视频',
'b' => '显示自定义内容',
),
'default' => 'a',
),


array(
'id' => 'data_add',
'type' => 'group',
'title' => '添加显示数据',
'subtitle' => '如果只有一个菜单则不显示二级，默认显示第一个菜单',
'dependency' => array('jinsom_video_special_add_type','==','a'),
'button_title' => '添加',
'fields' => array(

array(
'id'    => 'subtitle',
'type'  => 'text',
'title' => '菜单名称',
),


array(
'id'    => 'topics',
'type'  => 'textarea',
'title' => '指定话题ID的视频',
'subtitle' => '多个话题ID请用英文逗号隔开',
'placeholder' => '留空则调用所有最新的视频',
),

)
),

array(
'id'         => 'number',
'type'       => 'spinner',
'title'      => '显示数量',
'dependency' => array('jinsom_video_special_add_type','==','a'),
'default'       => 12,
),


array(
'id' => 'ad',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'dependency' => array('jinsom_video_special_add_type','==','b'),
'title' => '电脑端自定义内容（支持html）',
'subtitle'=>'一般用于展示广告横幅，展示图片等等，【不支持输入js代码】',
),



)
), //添加结束


array(
'id' => 'jinsom_video_special_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '视频专题头部自定义区域',
'subtitle' => '支持html',
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '以下是移动端的视频专题配置',
),


array(
'id' => 'jinsom_mobile_video_special_add',
'type' => 'group',
'title' => '移动端视频专题板块设置',
'button_title' => '配置',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '标题',
'subtitle' => '头部导航名称标题',
),


array(
'id' => 'topic',
'type' => 'textarea',
'title' => '话题ID',
'subtitle'=>'多个话题ID请用英文逗号隔开，如果留空则调用所有的视频',
'placeholder' => '1,2,3,4,5,6'
),



)
), //添加结束


array(
'id'         => 'jinsom_mobile_video_load_number',
'type'       => 'spinner',
'title'      => '每页视频显示数量',
'default'       => 10,
),


)
));



////====话题中心
$topic_show_page_prefix = 'topic_show_page_data';
LightSNS::createMetabox($topic_show_page_prefix, array(
'title'        => '话题中心页面配置',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/topic.php',
));


LightSNS::createSection($topic_show_page_prefix, array(
'fields' => array(

array(
'id'         => 'header_name',
'type'       => 'text',
'title'      => '移动端头部名称',
'subtitle'      => '首页tab页面可以单独设置，这里设置的仅仅是内页的头部名称',
'default'    =>'话题中心',
),


array(
'id' => 'jinsom_topic_add',
'type' => 'group',
'title' => '话题中心内容添加',
'desc' => '添加一些精品话题展示在话题中心页面，可以根据自己需求分类',
'button_title' => '添加',
'accordion_title' => '新增的话题',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '话题分类标题',
'desc' => '如果不知道是怎么回事，可以随便设置看看效果就懂了。',
),

array(
'id'                 => 'jinsom_topic_add_type',
'type'               => 'select',
'title'              => '数据类型',
'options'            => array(
'follow'              => '我关注的话题',
'content'         => '内容最多的话题',
'views'         => '浏览量最多的话题',
'add'         => '手动设置话题',
),
'default'       =>'add',
),

array(
'id' => 'number',
'type' => 'spinner',
'dependency' => array('jinsom_topic_add_type','!=','add') ,
'title' => '展示的话题的数量',
'default' => 16,
),

array(
'id' => 'data',
'type' => 'textarea',
'dependency' => array('jinsom_topic_add_type','==','add') ,
'title' => '当前分类展示的话题ID集合，英文逗号隔开',
'placeholder' => '34,78,98,234,156'
),

array(
'id' => 'pc_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端头部自定义区域',
'subtitle' => '支持html，一般用于banner、广告展示',
),

array(
'id' => 'mobile_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '移动端头部自定义区域',
'subtitle' => '支持html，一般用于banner、广告展示',
),


)
) ,


array(
'id' => 'jinsom_topic_header_ad',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【电脑端】头部自定义内容',
'subtitle' => '支持html',
),

array(
'id' => 'jinsom_topic_footer_ad',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【电脑端】底部自定义内容',
'subtitle' => '支持html',
),

array(
'id' => 'jinsom_topic_mobile_header_ad',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【移动端】头部自定义内容',
'subtitle' => '支持html',
),

)
));




////====商城
$shop_page_prefix = 'shop_page_data';
LightSNS::createMetabox($shop_page_prefix, array(
'title'        => '商城页面配置',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/shop.php',
));


LightSNS::createSection($shop_page_prefix, array(
'fields' => array(


array(
'type' => 'notice',
'style' => 'success',
'content' => '电脑端设置',
),

array(
'id' => 'jinsom_shop_slider_on_off',
'type' => 'switcher',
'default' => false,
'title' => '头部幻灯片',
),


array(
'id' => 'jinsom_shop_slider_add',
'type' => 'group',
'title' => '幻灯片',
'dependency' => array('jinsom_shop_slider_on_off','==','true'),
'button_title' => '添加',
'fields' => array(

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => __('图片','jinsom'),
'placeholder' => 'https://'
),

array(
'id' => 'link',
'type'       => 'text',
'title' => '链接',
'placeholder' => 'https://',
),


)
),


array(
'id' => 'jinsom_shop_slider_nav_add',
'type' => 'group',
'title' => '幻灯片导航菜单',
'subtitle' => '不添加则不显示',
'dependency' => array('jinsom_shop_slider_on_off','==','true'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type'       => 'text',
'title' => '导航标题',
),

array(
'id' => 'link',
'type'       => 'text',
'title' => '导航链接',
'placeholder' => 'https://',
),


array(
'id' => 'nav_add',
'type' => 'group',
'title' => '二级菜单添加',
'subtitle' => '不添加则不显示',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'subtitle',
'type'       => 'text',
'title' => '标题',
),

array(
'id' => 'sublink',
'type'       => 'text',
'title' => '链接',
'placeholder' => 'https://',
),

array(
'id'      => 'subimages',
'type'    => 'upload',
'title'   => __('图标','jinsom'),
'subtitle'   => '50px*50px',
'placeholder' => 'https://'
),

)
),


)
),


array(
'id' => 'jinsom_shop_header_commend_add',
'type' => 'group',
'title' => '头部推荐格子',
'subtitle' => '不添加则不显示',
'button_title' => '添加',
'fields' => array(

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => __('图片','jinsom'),
'placeholder' => 'https://'
),

array(
'id' => 'link',
'type'       => 'text',
'title' => __('链接','jinsom'),
'placeholder' => 'https://',
),


)
),


array(
'id'     => 'jinsom_shop_show_type',
'type'   => 'radio',
'title'  => '内容区显示类型',
'options'=> array(
'list'    => '列表展示',
'box'    => '模块添加',
),
'default'       =>'list',
),


array(
'id' => 'jinsom_shop_box_add',
'type' => 'group',
'title' => '模块添加',
'dependency' => array('jinsom_shop_show_type','==','box'),
'button_title' => '添加',
'fields' => array(

array(
'id'     => 'jinsom_shop_box_add_type',
'type'   => 'radio',
'title'  => '模块显示类型',
'options'=> array(
'goods'    => '商品展示',
'html'    => '自定义内容',
),
'default'       =>'goods',
),

array(
'id'     => 'title_style',
'type'   => 'radio',
'title'  => '标题样式',
'dependency' => array('jinsom_shop_box_add_type','==','goods'),
'options'=> array(
'center'    => '居中显示',
'left'    => '左侧显示',
),
'default'       =>'center',
),

array(
'id' => 'title',
'type'       => 'text',
'title' => '标题名称',
'dependency' => array('jinsom_shop_box_add_type','==','goods'),
),

array(
'id' => 'title_link',
'type'       => 'text',
'title' => '标题链接',
'placeholder' => '可以留空',
'dependency' => array('jinsom_shop_box_add_type','==','goods'),
),

array(
'id' => 'subtitle_text',
'type'       => 'text',
'title' => '二级小标题',
'dependency' => array('jinsom_shop_box_add_type','==|==','goods'),
),


array(
'id' => 'jinsom_shop_box_add_submenu_add',
'type' => 'group',
'title' => '二级子菜单',
'dependency' => array('jinsom_shop_box_add_type|title_style','==|==','goods|left'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'subtitle',
'type'       => 'text',
'title' => '子菜单名称',
),

array(
'id' => 'subtitle_link',
'type'       => 'text',
'title' => '子菜单链接',
),


)
),


array(
'id'         => 'number',
'type'       => 'spinner',
'title'      => '显示数量',
'dependency' => array('jinsom_shop_box_add_type','==','goods'),
'default'      => 8,
),


array(
'id'     => 'goods_style',
'type'   => 'radio',
'title'  => '商品列表样式',
'dependency' => array('jinsom_shop_box_add_type','==','goods'),
'options'=> array(
'one'    => '样式-1',
'two'    => '样式-2',
),
'default'       =>'one',
),


array(
'id' => 'ids',
'type'       => 'textarea',
'title' => '分类ID',
'subtitle' => '该模块调用这个分类ID下的商品，多个分类ID请用英文逗号隔开',
'dependency' => array('jinsom_shop_box_add_type','==','goods'),
'placeholder'=>'留空则调用全部',
),


array(
'id' => 'jinsom_shop_box_add_type_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '自定义内容',
'dependency' => array('jinsom_shop_box_add_type','==','html'),
),

)
),



array(
'id' => 'jinsom_shop_header_menu_add',
'type' => 'group',
'title' => '兑换页面头部菜单',
'subtitle' => '留空则不显示菜单',
'dependency' => array('jinsom_shop_show_type','==','list'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type'       => 'text',
'title' => '菜单名称',
),

array(
'id' => 'ids',
'type'       => 'textarea',
'title' => '分类ID',
'subtitle' => '调用这个分类ID下的商品，多个分类ID请用英文逗号隔开',
'placeholder'=>'留空则调用全部'
),

)
),


array(
'id'     => 'jinsom_shop_style_type',
'type'   => 'radio',
'title'  => '商品列表样式',
'dependency' => array('jinsom_shop_show_type','==','list'),
'options'=> array(
'one'    => '样式-1',
'two'    => '样式-2',
),
'default'       =>'one',
),


array(
'id'         => 'jinsom_shop_show_number',
'type'       => 'spinner',
'title'      => '显示数量',
'dependency' => array('jinsom_shop_show_type','==','list'),
'default'      => 12,
),


array(
'id'                 => 'jinsom_shop_sidebar_type',
'type'               => 'select',
'title'              => '商城侧栏',
'options'            => $custom_sidebar_arr,
'default'       =>'no',
),


array(
'id' => 'jinsom_shop_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '头部自定义区域',
),

array(
'id' => 'jinsom_shop_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '底部自定义区域',
),

array(
'type' => 'notice',
'style' => 'success',
'content' => '移动端设置',
),


array(
'id' => 'jinsom_mobile_shop_cat_add',
'type' => 'group',
'title' => '商品分类添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '分类名称',
),

array(
'id'      => 'cats',
'type'    => 'textarea',
'title'   => '分类ID',
'subtitle'   => '请输入分类ID，多个ID英文逗号隔开',
),


)
),

array(
'id' => 'jinsom_mobile_shop_sort_add',
'type' => 'group',
'title' => '商品排序添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '选项名称',
),

array(
'id' => 'jinsom_mobile_shop_sort_add_type',
'type' => 'select',
'title' => '排序类型',
'options'      => array(
'new' => '最新发布',
'comment' => '评价最高(好评率)',
'buy' => '销量最高',
'price_min' => '价格最低',
'price_max' => '价格最高',
'rand' => '随机展示',
),
),


)
),


array(
'id' => 'jinsom_mobile_shop_price_add',
'type' => 'group',
'title' => '价格范围添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id' => 'min',
'type' => 'spinner',
'title' => '起始价格',
'subtitle' => '>=',
),

array(
'id' => 'max',
'type' => 'spinner',
'title' => '起始价格',
'subtitle' => '<',
),

)
),

array(
'id' => 'jinsom_mobile_shop_list_style',
'type' => 'select',
'title' => '列表布局',
'options'      => array(
'cell' => '格子',
'waterfall' => '瀑布流',
),
),




)
));


//直播页面-meta设置
$video_live_page_prefix = 'video_live_page_data';
LightSNS::createMetabox('video_live_page_data', array(
'title'        => '设置选项',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/live.php',
));


LightSNS::createSection('video_live_page_data', array(
'fields' => array(

array(
'id'         => 'jinsom_video_live_url',
'type'       => 'text',
'title'      => '直播推流地址',
'placeholder'      => 'https://xxxxx.com/xxx.m3u8',
'subtitle'      => '如果留空则显示停播的状态，一般是m3u8格式，回播支持mp4/flv/mov格式',
),

array(
'id'         => 'jinsom_video_live_img',
'type'       => 'upload',
'placeholder'      => 'https://',
'title'      => '直播封面图',
),

array(
'id'         => 'jinsom_video_live_user_id',
'type'       => 'spinner',
'title'      => '直播的用户ID',
'default'      => 1,
'subtitle'      => '就是当前直播的用户，如果是官方直播，可以专门注册一个官方的帐号',
),

array(
'id'         => 'jinsom_video_live_views_number',
'type'       => 'spinner',
'title'      => '直播观看数的倍数',
'default'      => 1,
'subtitle'      => '注意：实际的观看人数等于 页面浏览量 * 这里填的数字。',
),

array(
'id'         => 'jinsom_video_live_comment_placeholder',
'type'       => 'textarea',
'default'=> '请勿发布违规内容，否则进行封号处理！',
'title'      => '评论框框提示语',
),

array(
'id'         => 'jinsom_video_live_images_upload_on_off',
'type'       => 'switcher',
'title'      => '互动评论允许发图片',
'default'      => false,
'subtitle'      => '关闭之后，互动评论不能发图片',
),

array(
'id'         => 'jinsom_video_live_reward_on_off',
'type'       => 'switcher',
'title'      => '打赏功能',
'default'      => false,
),

array(
'id'         => 'jinsom_video_live_tab_desc_name',
'type'       => 'text',
'placeholder'=> '直播介绍',
'default'=> '直播介绍',
'title'      => '直播介绍的名称',
),

array(
'id'         => 'jinsom_video_live_tab_jingcai_name',
'type'       => 'text',
'placeholder'=> '精彩瞬间',
'default'=> '精彩瞬间',
'title'      => '精彩瞬间的名称',
),

array(
'id' => 'jinsom_video_live_jingcai_add',
'type' => 'group',
'title' => '添加精彩瞬间视频',
'subtitle' => '一般是直播完成之后裁剪出来的，如果不需要直接不添加则不开启这个功能',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type'=> 'text',
'title' => '视频名称',
),

array(
'id' => 'url',
'type'    => 'upload',
'title' => '视频地址',
'placeholder'      => 'https://',
),

array(
'id' => 'cover',
'type'=> 'upload',
'placeholder'      => 'https://',
'title'=> '视频封面',
),


)
),

array(
'id'=>'jinsom_video_live_title_bottom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '标题底下自定义区域',
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),

)
));


//商品详情页面-meta设置
LightSNS::createMetabox('goods_data', array(
'title'        => '设置选项',
'post_type'    => 'goods',
'priority'   => 'high',
));

LightSNS::createSection('goods_data', array(
'fields' => array(

array(
'id' => 'jinsom_shop_goods_img_add',
'type' => 'group',
'title' => '封面图-新版',
'subtitle' => '请使用新版封面图添加，旧版的仅仅用于旧数据迁移',
'button_title' => '添加',
'fields' => array(

array(
'id'          => 'name',
'type'        => 'text',
'title'       => '名称',
'placeholder'       => '可以留空',
),

array(
'id'          => 'img',
'type'        => 'upload',
'title'       => '封面图',
'desc'       => '500px*500px的正方形，请添加一张或4张，不要超过4张。更多介绍图片直接在内容区添加',
),


)
),


array(
'id'          => 'jinsom_shop_goods_img',
'type'        => 'gallery',
'title'       => '封面图-旧版',
'subtitle'       => '<font style="color:#f00;">新发布的商品请使用新版封面图</font>',
'add_title'   => '添加',
'edit_title'  => '编辑',
'clear_title' => '移除',
),



array(
'id'         => 'jinsom_shop_goods_subtitle',
'type'       => 'text',
'title'      => '二级标题',
'subtitle'      => '请用用一句话描述一下你的商品',
),

array(
'id'         => 'buy_number',
'type'       => 'spinner',
'title'      => '商品销量',
'subtitle'      => '如果你不想数据难看可以适当修改，商品每次被购买，此处会自动增加。',
'default'       =>0,
),


array(
'id'                 => 'jinsom_shop_goods_type',
'type'               => 'radio',
'title'              => '类型',
'options'            => array(
'a'    => '本站虚拟物品',
'b'    => '其他虚拟物品',
'c'    => '实体物品(需要物流的)',
'd'    => '淘宝客推广',
),
'default'       =>'a',
),

array(
'id'         => 'virtual_type',
'type'       => 'radio',
'title'      => '本站虚拟物品类型',
'dependency' => array('jinsom_shop_goods_type','==','a'),
'options'    => array(
'honor'    => '头衔',
'exp'    => '经验值',
'vip_number'    => '成长值',
'charm'    => '魅力值',
'sign'    => '补签卡',
'nickname'    => '改名卡',
'download'    => '下载地址',
'faka'    => '自动发卡',
),
'default'       =>'exp',
),

array(
'id'         => 'virtual_download_info',
'type'       => 'textarea',
'title'      => '下载信息',
'subtitle'      => '用户购买后，自动发送下载信息到用户的IM，用户也可以在订单里查看',
'placeholder'      => '下载地址：http://xxxx.com  密码：xxxx，解压密码：xxxx',
'dependency' => array('jinsom_shop_goods_type|virtual_type','==|==','a|download'),
),


array(
'id'         => 'virtual_faka',
'type'       => 'textarea',
'title'      => '卡密信息',
'subtitle'      => '一行一条卡密，用户购买后，自动将卡密到用户IM，用户也可以在订单里查看',
'placeholder'      => '切记，一行一条数据，建议几百条数据即可，每次发卡之后会剔除已发的卡密。卡密发完之后，用户不可购买。',
'attributes' => array(
'style'    => 'height:300px;width:500px;'
),
'dependency' => array('jinsom_shop_goods_type|virtual_type','==|==','a|faka'),
),

array(
'id'         => 'virtual_honor',
'type'       => 'text',
'title'      => '头衔名称',
'subtitle'      => '用户购买得到的头衔',
'dependency' => array('jinsom_shop_goods_type|virtual_type','==|==','a|honor'),
),

array(
'id'         => 'virtual_number',
'type'       => 'spinner',
'title'      => '本站虚拟物品数量',
'subtitle'      => '用户购买可以得到的数量',
'dependency' => array('jinsom_shop_goods_type|virtual_type|virtual_type|virtual_type','==|!=|!=|!=','a|honor|download|faka'),
'default'       =>10,
),


array(
'id'         => 'jinsom_shop_goods_taobaoke_url',
'type'       => 'text',
'title'      => '淘宝客推广地址',
'dependency' => array('jinsom_shop_goods_type','==','d'),
'placeholder'      => 'https://',
),


array(
'id'         => 'jinsom_shop_goods_price_type',
'type'       => 'radio',
'title'      => '售价类型',
'dependency' => array('jinsom_shop_goods_type','!=','d'),
'options'    => array(
'credit'    => '金币',
'rmb'    => '人民币',
),
'default'       =>'credit',
),


array(
'id'         => 'jinsom_shop_goods_price',
'type'       => 'spinner',
'title'      => '原价',
'subtitle'      => '只能为正整数，如果折扣价格为空则使用原始价格',
),

array(
'id'         => 'jinsom_shop_goods_price_discount',
'type'       => 'spinner',
'title'      => '折扣价',
'subtitle'      => '只能为正整数，留空则没有折扣',
),

array(
'id'         => 'jinsom_shop_goods_ico',
'type'       => 'text',
'title'      => '显示标识',
'subtitle'      => '例如：推荐、促销、甩卖、特价',
'placeholder' =>'可以留空'
),

array(
'id'         => 'jinsom_shop_goods_count',
'type'       => 'spinner',
'title'      => '库存',
'dependency' => array('jinsom_shop_goods_type|jinsom_shop_goods_type','!=|!=','a|d'),
'default'       =>9999,
),

array(
'id'         => 'jinsom_shop_goods_max',
'type'       => 'spinner',
'title'      => '上限',
'unit'       => '次',
'subtitle'      => '每个用户最多可以购买的次数',
'dependency' => array('jinsom_shop_goods_type','!=','d'),
'default'       =>9999,
),

array(
'id'         => 'jinsom_shop_goods_power',
'type'       => 'radio',
'title'      => '购买权限',
'subtitle'      => '哪些用户可以购买此商品',
'dependency' => array('jinsom_shop_goods_type','!=','d'),
'options'    => array(
'login'    => '所有登录用户',
'vip'    => '会员用户',
'verify'    => '认证用户',
'new'    => '新人专享(一个月内注册的用户)',
'vip_number'    => '指定成长值',
'exp'    => '指定经验值',
'charm'    => '指定魅力值',
'no'    => '准备开售',
'stop'    => '下架',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_shop_goods_power_vip_number',
'type'       => 'spinner',
'title'      => '购买权限_指定成长值',
'unit'       => '成长值',
'dependency' => array('jinsom_shop_goods_type|jinsom_shop_goods_power','!=|==','d|vip_number'),
'default'       =>10,
),

array(
'id'         => 'jinsom_shop_goods_power_exp',
'type'       => 'spinner',
'title'      => '购买权限_指定经验值',
'unit'       => '经验值',
'dependency' => array('jinsom_shop_goods_type|jinsom_shop_goods_power','!=|==','d|exp'),
'default'       =>10,
),

array(
'id'         => 'jinsom_shop_goods_power_charm',
'type'       => 'spinner',
'title'      => '购买权限_指定魅力值',
'unit'       => '魅力值',
'dependency' => array('jinsom_shop_goods_type|jinsom_shop_goods_power','!=|==','d|charm'),
'default'       =>10,
),


array(
'id' => 'jinsom_shop_goods_buy_tips',
'type' => 'textarea',
'title' => '<i></i>提醒文字',
'subtitle' => '用于提醒用户一些注意事项，留空则不显示',
),


array(
'id' => 'jinsom_shop_goods_buy_user_info_add',
'type' => 'group',
'title' => '<i></i>用户下单需要填写的信息',
'subtitle' => '添加之后，用户需要填写对应的信息才能下单，比如帐号密码',
'dependency' => array('jinsom_shop_goods_type','==','b'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type'       => 'text',
'title' => '选项名称',
'subtitle' => '<font style="color:#f00">不能超过4个字</font>，比如帐号，密码，手机号，QQ号等等',
),

)
),


array(
'id' => 'jinsom_shop_goods_buy_info_tips',
'type' => 'text',
'title' => '备注信息',
'subtitle' => '如果留空则用户不需要强制填写！',
'desc' => '用户购买的时候需要填一些备注信息，比如：QQ号、手机号、邮箱、密码、地址信息等等',
'dependency' => array('jinsom_shop_goods_type','==','c'),
),


array(
'id' => 'jinsom_shop_goods_select_add',
'type' => 'group',
'title' => '属性套餐',
'subtitle' => '注意：这个套餐是不能改变价格的，留空则不添加',
'dependency' => array('jinsom_shop_goods_type|jinsom_shop_goods_type','!=|!=','a|d'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type'       => 'text',
'title' => '套餐名称',
'subtitle' => '比如颜色、尺寸、版本等等',
),

array(
'id' => 'value_add',
'type' => 'group',
'title' => '可选套餐名称',
'subtitle' => '比如颜色对应的值有：黑色、白色、黄色、等等',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'value',
'type'   => 'text',
'title' => '套餐对应的值',
),

)
),

)
),


array(
'id' => 'jinsom_shop_goods_select_change_price_add',
'type' => 'group',
'title' => '价格套餐',
'subtitle' => '<font style="color:#f00;">如果添加了这个套餐，商品则默认使用这里的套餐价格。反之使用上面设置的价格</font>',
'max'=>1,
'dependency' => array('jinsom_shop_goods_type|jinsom_shop_goods_type','!=|!=','a|d'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type'       => 'text',
'title' => '套餐属性名',
'subtitle' => '比如款式、型号、版本等等',
),

array(
'id' => 'value_add',
'type' => 'group',
'title' => '可选套餐',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type'   => 'text',
'title' => '可选套餐名称',
),

array(
'id' => 'price',
'type'   => 'spinner',
'title' => '套餐售价',
'subtitle' => '不能为小数，只能正整数',
),

array(
'id' => 'price_discount',
'type'   => 'spinner',
'title' => '套餐折扣价',
'subtitle' => '不能为小数，只能正整数',
),

)
),

)
),


array(
'id' => 'jinsom_shop_goods_info_add',
'type' => 'group',
'title' => '商品属性',
'subtitle' => '留空则不添加',
'dependency' => array('jinsom_shop_goods_type','!=','d'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type'       => 'text',
'title' => '属性名称',
),

array(
'id' => 'value',
'type'   => 'textarea',
'title' => '属性值',
),

)
),


array(
'id'                 => 'jinsom_shop_kefu_type',
'type'               => 'radio',
'title'              => '客服类型',
'subtitle'              => '如果使用公共客服，则保留默认即可',
'desc'              => '<font style="color:#f00;">每个商品可以单独设置客服，如果不设置则使用公共设置(主题配置-商城模块-客服)</font>',
'options'            => array(
'public'           => '使用公共设置',
'im'              => '调用IM聊天',
'qq'         => '调用QQ聊天',
'link'           => '跳转指定链接',
'no'           => '不显示',
),
'default'       =>'public',
),

array(
'id'      => 'jinsom_shop_kefu_im_user_id',
'type'       => 'spinner',
'title'   => 'IM聊天的用户ID',
'default' => 1,
'dependency' => array('jinsom_shop_kefu_type','==','im') ,
'subtitle'   => '当用户点击客户按钮则调用与当前设置的用户ID的聊天界面',
),

array(
'id'      => 'jinsom_shop_kefu_qq',
'type'       => 'number',
'title'   => '客服QQ号',
'dependency' => array('jinsom_shop_kefu_type','==','qq') ,
'subtitle'   => '当用户点击客户按钮则调用与该QQ的聊天窗口',
),

array(
'id'      => 'jinsom_shop_kefu_link',
'type'       => 'text',
'title'   => '客服跳转的链接',
'placeholder'   => 'https://xxxx.com',
'dependency' => array('jinsom_shop_kefu_type','==','link') ,
'subtitle'   => '当用户点击客户按钮则跳转该链接',
),


array(
'id'         => 'jinsom_shop_referral_credit',
'type'       => 'spinner',
'title'      => '商品返利',
'unit'       => '金币',
'subtitle'      => '推广用户购买此商品可获得的金币返利。<font style="color:#f00;">注意：每次购买都能获得返利</font>',
'default'       =>0,
),


array(
'id' => 'jinsom_shop_comment_on_off',
'type' => 'switcher',
'default' => true,
'title' => '商品讨论模块',
'subtitle' => '<font style="color:#f00;">如果关闭这个开关，那么将不显示商品讨论模块。</font>这里是每个商品的单独设置，如果想要全局请到主题配置里面进行设置',
),

array(
'id' => 'jinsom_shop_related_goods_on_off',
'type' => 'switcher',
'default' => false,
'title' => '关闭相关商品模块',
'subtitle' => '<font style="color:#f00;">如果打开这个开关，那么将不显示相关商品模块。</font>',
),


array(
'id' => 'jinsom_shop_buy_text',
'type' => 'text',
'title' => '购买按钮名称',
'subtitle'=>'留空则显示“立即购买”，如果该商品你有特殊需求可以改为“立即兑换”、“马上兑换”等',
),


)
));



//排行榜-meta设置
LightSNS::createMetabox('page_leaderboard_data', array(
'title'        => '设置选项',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/leaderboard.php',
));


LightSNS::createSection('page_leaderboard_data', array(
'fields' => array(

array(
'id'         => 'header_name',
'type'       => 'text',
'title'      => '移动端头部名称',
'default'    => '排行榜'
),

array(
'id' => 'jinsom_leaderboard_add',
'type' => 'group',
'title' => '排行榜选项',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '选项名称',
'default'=>'富豪排行榜',
),

array(
'id' => 'type',
'type' => 'select',
'title' => '选项类型',
'options'      => array(
'reward' => '打赏排行榜',
'credit' => '金币排行榜',
'vip_number' => '会员排行榜',
'exp' => '经验排行榜',
'follower' => '粉丝排行榜',
'recharge' => '充值排行榜',
'visitor' => '人气排行榜',
'charm' => '魅力排行榜',
'post_count' => '发表排行榜',
'task_times' => '任务排行榜',
'invite_number' => '推广排行榜',
'custom' => '自定义排行榜',
),
'default'=>'credit',
),

array(
'id' => 'unit',
'type' => 'text',
'title' => '显示单位',
'default'=>'金币',
'dependency' => array('type','!=','custom'),
),

array(
'id'         => 'custom',
'type'       => 'textarea',
'title'      => '自定义数据',
'dependency' => array('type','==','custom'),
'desc'      => '例如：2637,100分||1342,98分||8278,60分',
'placeholder'=> '用户ID,展示的数据||用户ID,展示的数据||用户ID,展示的数据',
'subtitle' =>'注意，逗号是英文逗号'
),


)
),




array(
'id' => 'jinsom_leaderboard_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '头部自定义区域(电脑端)',
'subtitle' => '支持html和短代码',
),

array(
'id' => 'jinsom_leaderboard_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '底部自定义区域(电脑端)',
'subtitle' => '支持html和短代码',
),


)
));



//筛选-meta设置
LightSNS::createMetabox('page_select_option', array(
'title'        => '设置选项',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/select.php',
));


LightSNS::createSection('page_select_option', array(
'fields' => array(

array(
'id'     => 'jinsom_page_select_type',
'type'   => 'radio',
'title'  => '你需要筛选什么？',
'options'=> array(
'bbs' => '帖子',
'single' => '文章',
'video' => '视频',
'words' => '动态（有图）',
'all_a' => '文章+视频',
),
'default'=>'bbs',
),

array(
'id' => 'jinsom_page_select_menu_on_off',
'type' => 'switcher',
'title' => '面包屑导航',
'subtitle' => '仅在电脑端展示',
'default' => false,
),

array(
'id' => 'jinsom_page_select_color',
'type' => 'color',
'title' => '元素颜色',
'subtitle' => '仅在电脑端展示',
'default' => '#5fb878',
),

array(
'id' => 'jinsom_page_select_waterfall_on_off',
'type' => 'switcher',
'title' => '开启瀑布流',
'default' => false,
),

array(
'id' => 'jinsom_page_select_show_all_image_on_off',
'type' => 'switcher',
'title' => '抽取所有图片',
'subtitle' => '开启之后，内容里面的所有图片都抽取展示出来',
'dependency' => array('jinsom_page_select_type','any','bbs,single'),
'default' => false,
),


array(
'id'     => 'jinsom_page_select_list_style',
'type'   => 'select',
'title'  => '内容列表布局',
'desc'  => '移动端只有布局3和4',
'options'=> array(
'lattice_1' => '布局-1',
'lattice_2' => '布局-2',
'lattice_3' => '布局-3',
'lattice_4' => '布局-4 [纯图片]',
'lattice_5' => '弹层类-1',
'lattice_6' => '弹层类-2',
'lattice_7' => '弹层类-3',
),
'default'=>'lattice_1',
),


array(
'id' => 'jinsom_page_select_gallery_on_off',
'type' => 'switcher',
'title' => '开启灯箱模式',
'subtitle' => '开启之后，点击图片以灯箱形式展示图片而不是跳转内容页',
'dependency' => array('jinsom_page_select_list_style','not-any','lattice_5,lattice_6,lattice_7'),
'default' => false,
),



array(
'id' => 'jinsom_page_select_download_color',
'type' => 'color',
'title' => '弹层按钮背景颜色',
'default' => '#17A1FF',
'dependency' => array('jinsom_page_select_list_style','any','lattice_5,lattice_7'),
),

array(
'id' => 'jinsom_page_select_download_text',
'type' => 'text',
'title' => '弹层按钮文字',
'subtitle' => '支持图标代码',
'default' => '点击下载',
'dependency' => array('jinsom_page_select_list_style','any','lattice_5,lattice_7'),
),

array(
'id' => 'jinsom_page_select_popup_icon',
'type' => 'textarea',
'title' => '弹层-2图标代码',
'placeholder'=>'留空则使用程序自带的图标',
'subtitle' => '可使用程序内置图标或自定义图标，<a href="https://q.jinsom.cn/iconfont" target="_blank" style="color:#f00;">《内置图标参考》</a>',
'dependency' => array('jinsom_page_select_list_style','==','lattice_6'),
),

array(
'id' => 'jinsom_page_select_list_bg_height',
'type' => 'spinner',
'title' => '图片封面高度',
'subtitle' => '仅在电脑端生效',
'dependency' => array('jinsom_page_select_waterfall_on_off','==',false),
'default' => 320,
'unit'=>'px'
),


array(
'id' => 'jinsom_page_select_list_number',
'type' => 'spinner',
'title' => '列表展示的数量',
'default' => 20,
),

array(
'id'     => 'jinsom_page_select_line_number',
'type'   => 'select',
'title'  => '一行展示的数量',
'subtitle' => '仅在电脑端生效',
'options'=> array(
2 => '一行2个',
3 => '一行3个',
4 => '一行4个',
5 => '一行5个',
6 => '一行6个',
7 => '一行7个',
8 => '一行8个',
),
'default'=>4,
),

array(
'id' => 'jinsom_page_select_list_gutter',
'type' => 'spinner',
'title' => '格子间的间隙',
'default' => 15,
'unit'=>'px'
),


array(
'id' => 'jinsom_page_select_bbs_add',
'type' => 'group',
'title' => '添加筛选论坛',
'dependency' => array('jinsom_page_select_type','==','bbs'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id' => 'bbs_id',
'type' => 'text',
'title' => '绑定的论坛ID',
'subtitle' => '多个论坛ID请用英文逗号隔开',
'desc'=>'<font style="color:#f00;">所有板块请填：all</font>'
),


array(
'id' => 'jinsom_page_select_bbs_topic_add',
'type' => 'group',
'title' => '添加该板块话题',
'subtitle' => '可以留空',
'desc' => '不同板块可以显示不用的筛选话题',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '筛选名称',
),

array(
'id' => 'bbs_topic_add',
'type' => 'group',
'title' => '添加话题列表',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'topic_name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id' => 'topic_id',
'type' => 'text',
'title' => '绑定的话题ID',
'desc'=>'<font style="color:#f00;">多个话题ID请用 | 隔开，如果筛选关系使用and，切勿填写多个话题ID</font>'
),

)
),

)
),

)
),

array(
'id' => 'jinsom_page_select_topic_add',
'type' => 'group',
'title' => '添加筛选话题',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '筛选名称',
),

array(
'id' => 'topic_add',
'type' => 'group',
'title' => '添加话题列表',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'topic_name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id' => 'topic_id',
'type' => 'text',
'title' => '绑定的话题ID',
'desc'=>'<font style="color:#f00;">多个话题ID请用 | 隔开，如果筛选关系使用and，切勿填写多个话题ID</font>'
),

)
),

)
),


array(
'id'     => 'jinsom_page_select_topic_relation',
'type'   => 'radio',
'title'  => '话题筛选关系',
'subtitle'  => '多个话题直接的筛选组合关系',
'options'=> array(
'or' => '存在这些话题的内容（或or）',
'and' => '同时包含这些话题的内容（并且and）',
),
'default'=>'or',
),



array(
'id' => 'jinsom_page_select_field_add',
'type' => 'group',
'title' => '添加筛选字段',
'subtitle' => '<font style="color:#f00;">这个属于进阶功能，如果不懂请勿设置，有疑问请联系群主</font>',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '筛选名称',
),

array(
'id' => 'field_add',
'type' => 'group',
'title' => '添加字段列表',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'field_name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id'     => 'jinsom_page_select_field_type',
'type'   => 'radio',
'title'  => '类型',
'options'=> array(
'compare' => '比较某个字段',
'include' => '包含某个字段',
),
'default'=>'compare',
),

array(
'id' => 'field_key',
'type' => 'text',
'title' => '字段key值',
'desc'=>'文章postmeta的meta_key值'
),

array(
'id'     => 'relation',
'type'   => 'select',
'title'  => '比较关系',
'dependency' => array('jinsom_page_select_field_type','==','compare'),
'options'=> array(
'=' => '等于',
'!=' => '不等于',
'>' => '大于',
'<' => '小于',
'>=' => '大于或等于',
'<=' => '小于或等于',
'LIKE' => '包含字符串',
'NOTLIKE' => '不包含字符串',
'BETWEEN' => '两个值之间(数组)',
'NOTEXISTS' => '不存在meta_key值',
),
'default'=>'=',
),

array(
'id' => 'field_value',
'type' => 'text',
'title' => '字段值value',
'dependency' => array('jinsom_page_select_field_type|relation','==|!=','compare|NOTEXISTS'),
'desc'=>'需要比较的meta_value值<br><font style="color:#f00;">如果比较关系是“两个值之间”，请以-隔开比如：100-1000</font>'
),

array(
'id'     => 'field_value_type',
'type'   => 'select',
'title'  => 'value值类型',
'dependency' => array('jinsom_page_select_field_type|relation','==|!=','compare|NOTEXISTS'),
'options'=> array(
'CHAR' => '字符串',
'NUMERIC' => '数字',
'DATE' => '日期',
'DATETIME' => '时间日期',
),
'default'=>'CHAR',
),

)
),

)
),





array(
'id' => 'jinsom_page_select_power_add',
'type' => 'group',
'title' => '添加筛选权限[帖子]',
'dependency' => array('jinsom_page_select_type','==','bbs'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id'     => 'power_type',
'type'   => 'select',
'title'  => '帖子类型',
'options'=> array(
'free' => '免费',
'pay' => '付费可见',
'vip' => 'VIP可见',
'comment'=>'回复可见',
'login'=>'登录可见',
'answer'=>'问答悬赏',
'answer_ok'=>'已解决问答',
'answer_no'=>'未解决问答',
'vote'=>'投票帖子',
'activity'=>'活动帖子',
),
'default'=>'free',
),

)
),


array(
'id' => 'jinsom_page_select_words_power_add',
'type' => 'group',
'title' => '添加筛选权限[文章/视频/动态]',
'dependency' => array('jinsom_page_select_type','!=','bbs'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id'     => 'power_type',
'type'   => 'select',
'title'  => '权限类型',
'options'=> array(
'free' => '免费',
'pay' => '付费可见',
'vip' => 'VIP可见',
'password'=>'密码可见',
'login'=>'登录可见',
),
'default'=>'free',
),

)
),



array(
'id' => 'jinsom_page_select_sort_add',
'type' => 'group',
'title' => '添加筛选排序',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id'     => 'sort_type',
'type'   => 'select',
'title'  => '排序类型',
'options'=> array(
'new' => '最新发布',
'comment_count' => '回复最多',
'views' => '浏览最多',
'last_comment'=>'最后回复',
'commend'=>'推荐(加精)的内容',
'rand'=>'随机显示',
),
'default'=>'new',
),

)
),


array(
'id' => 'jinsom_page_select_img_style',
'type' => 'text',
'title' => '筛选列表的图片样式规则',
'subtitle' => '请填写你所使用的对象储存的图片样式规则',
'placeholder' => '留空则不使用',
),


array(
'id' => 'jinsom_page_select_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '头部自定义区域(电脑端)',
'subtitle' => '支持html和短代码',
),


)
));



//抽奖设置
LightSNS::createMetabox('page_luckdraw_option', array(
'title'        => '设置选项',
'post_type'    => 'page',
'context'   => 'normal',
'priority'   => 'high',
'page_templates' => 'page/luck-draw.php',
));


LightSNS::createSection('page_luckdraw_option', array(
'fields' => array(

array(
'id' => 'jinsom_page_header_name',
'type' => 'text',
'title' => '移动端头部名称',
'default' => '幸运抽奖',
),

array(
'id' => 'jinsom_luck_gift_number',
'type' => 'spinner',
'title' => '每次抽礼品所需金币',
'default' => 100,
),

array(
'id'      => 'jinsom_luck_gift_default_cover',
'type'    => 'upload',
'title'   => '奖品默认封面图',
'subtitle'=>'尺寸为200*200正方形',
'placeholder' => 'https://'
),

array(
'id' => 'jinsom_luck_gift_danmu_on_off',
'type' => 'switcher',
'default' => true,
'title' => '展示抽中弹幕',
'subtitle' => '仅仅在电脑端展示',
),


array(
'id' => 'jinsom_luck_gift_add',
'type' => 'group',
'title' => '添加抽奖礼品',
'subtitle' => '请注意，所有礼品抽中的概率是相同的',
'button_title' => '添加',
'accordion_title' => '新增的礼品',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '礼品名称',
),

array(
'id'                 => 'jinsom_luck_gift_add_type',
'type'               => 'select',
'title'              => '礼品类型',
'options'            => array(
'金币'      => '金币',
'经验值'         => '经验值',
'魅力值'       => '魅力值',
'成长值'  => '成长值',
'人气值'     => '人气值',
'签到天数'        => '补签卡',
'VIP天数'         => 'VIP天数',
'头衔'         => '头衔',
'nickname'         => '改名卡',
'custom'         => '自定义奖励',
'faka'         => '卡密发卡',
'空'         => '没有任何奖励',
),
'default'     =>'金币',
),

array(
'id' => 'honor_name',
'type' => 'text',
'title' => '头衔名称',
'dependency' => array('jinsom_luck_gift_add_type','==','头衔') ,
'subtitle' => '请输入需要奖励的头衔名称',
),

array(
'id' => 'jinsom_luck_gift_faka',
'type' => 'textarea',
'title' => '发卡列表',
'dependency' => array('jinsom_luck_gift_add_type','==','faka') ,
'subtitle' => '一行一个卡密，发完再抽到会自动返还金币',
),

array(
'id' => 'number',
'type' => 'spinner',
'title' => '数量',
'dependency' => array('jinsom_luck_gift_add_type|jinsom_luck_gift_add_type|jinsom_luck_gift_add_type','!=|!=|!=','头衔|空|faka') ,
'default' => 10,
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '礼品封面图',
'subtitle'=>'尺寸为200*200正方形，建议同一个类型的礼品封面图一样',
'placeholder' => 'https://'
),

array(
'id'      => 'color',
'type'    => 'color',
'title'   => '礼品边框颜色',
'default' => '#f5ad18',
),


)
) ,


array(
'id' => 'jinsom_luck_gift_info_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '幸运抽奖规则说明',
'subtitle' => '支持html',
'default' =>'<p>1、每次抽取礼物需要花费xxxx金币</p><p>2、本次活动解释权归本站所有！</p>'
),


)
));
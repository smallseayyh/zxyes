<?php
require_once get_theme_file_path() .'/admin/classes/setup.class.php';
require($require_url.'/functions/languages.php');
if(isset($_GET['page'])&&$_GET['page']=='jinsom'){
$theme_url=get_template_directory_uri();



$prefix = 'jinsom_options';
LightSNS::createOptions($prefix);
LightSNS::createSection($prefix,
array(
'title'  => '<span>'.__('数据统计','jinsom').'</span>',
'icon'   => 'fa fa-bar-chart',
'fields' => array(
array(
'type'       => 'panel',
),
)
));

LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_base',
'title'  => '<span>'.__('基础设置','jinsom').'</span>',
'icon'   => 'fa fa-cog',
));


//用户认证
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>'.__('认证设置','jinsom').'</span>',
'icon'        => 'fa fa-vcard-o',
'fields'      => array(

array(
'id' => 'jinsom_verify_add',
'type' => 'group',
'title' => __('添加认证类型','jinsom'),
'subtitle' => __('默认有四种认证类型，如果不够可以添加。','jinsom'),
'button_title' => __('添加','jinsom'),
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => __('认证名称','jinsom'),
'subtitle'=>__('例如：男神认证','jinsom'),
),


array(
'id'      => 'icon',
'type'    => 'upload',
'title'   => __('认证图标','jinsom'),
'subtitle'=>__('建议尺寸：30px以上的正方形png图片，同时也支持svg格式','jinsom'),
'placeholder' => 'https://'
),


)
),


array(
'id'         => 'jinsom_verify_url',
'type'       => 'text',
'title'      => __('认证说明页面url地址','jinsom'),
'desc'      => __('认证功能为手动认证，这里设置的为认证说明页面地址，你可以设置一个页面用于说明认证需要什么条件和发送认证信息到指定的邮箱。','jinsom'),
'placeholder' => 'http://'
),

)
));

//用户中心
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>用户主页</span>',
'icon'        => 'fa fa-user',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => __('如果需要单独设置每个用户的资料信息，直接点击对应的用户头像进入用户中心-资料进行设置。如果不懂可以点击这里','jinsom').'<a href="https://q.jinsom.cn/10553.html" target="_blank" style="color:#f00;">'.__('《查看教程》','jinsom').'</a>',
),


array(
'id'         => 'jinsom_member_url',
'type'       => 'text',
'title'      => __('用户主页路径（需要配置伪静态）','jinsom'),
'subtitle'       => __('默认为author，不能使用wordpress自带参数，例如：p，page，cat。建议使用u，i 等简短的单字母。','jinsom'),
'desc'  =>'<font style="color:#f00;">'.__('【重要】如果没有配置伪静态，请保留默认的“author”，修改完成之后务必到设置-固定链接-重新保存一下生效。（如果任何不懂可以问群主）','jinsom').'</font>',
'default'    =>'author',
'attributes' => array(
'style'    => 'width: 100px;'
),
),

array(
'id' => 'jinsom_member_bg_add',
'type' => 'group',
'title' => __('用户主页背景图','jinsom'),
'subtitle' =>'<font style="color:#f00;">默认用户背景为设置的第一个</font><br>默认的背景封面请在群文件-素材-新版个人主页背景图.zip',
'button_title' => __('添加背景图','jinsom'),
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => __('封面名称','jinsom'),
),

array(
'id'      => 'big_img',
'type'    => 'upload',
'title'   => __('背景大图','jinsom'),
'desc' => __('直接显示在用户中心背景，为了适应2K屏幕，建议尺寸：2560x1600px','jinsom'),
'placeholder' => 'https://'
),

array(
'id'      => 'small_img',
'type'    => 'upload',
'title'   => __('资料卡小图','jinsom'),
'desc' => __('375*145px，资料卡片和侧栏资料展示','jinsom'),
'placeholder' => 'https://'
),

array(
'id'      => 'mobile_img',
'type'    => 'upload',
'title'   => '移动端背景',
'desc' => '1080*1200px，移动端背景展示，建议压缩一下',
'placeholder' => 'https://'
),

array(
'id'      => 'cover',
'type'    => 'upload',
'title'   => '封面图',
'desc' => '100*100px，该背景的封面图，在选择皮肤列表的地方展示',
'placeholder' => 'https://'
),

array(
'id' => 'vip',
'type' => 'switcher',
'default' => false,
'title' => 'VIP用户专属',
'subtitle' => '开启之后，这个背景只有VIP用户才能使用',
),


)
) ,


array(
'id'      => 'jinsom_default_avatar',
'type'    => 'upload',
'title'   => '用户默认头像',
'desc' => '建议100px*100px,反正是正方形即可',
'placeholder' => 'https://'
),

array(
'id' => 'jinsom_reg_avatar_rand_on_off',
'type' => 'switcher',
'default' => false,
'title' => '注册随机头像',
'subtitle' => '开启之后，用户注册之后随机分配默认头像，关闭后则统一使用默认头像',
),


array(
'id'         => 'jinsom_user_rand_avatar_number',
'type'       => 'spinner',
'title'      => '随机头像库的数量',
'subtitle'   => '头像格式为png格式',
'desc'   => '默认为40，请填你随机头像库的头像数量',
'dependency' => array('jinsom_reg_avatar_rand_on_off','==','true') ,
'default'    =>40,
'unit'     => '个',
),

array(
'id'         => 'jinsom_rand_avatar_url',
'type'       => 'text',
'title'      => '随机头像库的url地址',
'dependency' => array('jinsom_reg_avatar_rand_on_off','==','true') ,
'placeholder'=>'https://xxxxx.com/xxxx/',
'subtitle' =>'如果有疑问，请查看<a href="https://q.jinsom.cn/14126.html" target="_blank">《配置教程》</a>',
'desc'   => '支持外链，如果你网站是https，外链一定要支持https。格式：<font style="color:#f00;">https://xxxxx.com/xxxx/</font>',
),


array(
'id'      => 'jinsom_honur_color',
'type'    => 'color',
'title'   => '头衔默认颜色',
'default' => '#009688',
),


array(
'id' => 'jinsom_nickname_card_on_off',
'type' => 'switcher',
'default' => false,
'title' => '修改昵称是否需要改名卡',
'subtitle' => '开启之后，用户每次修改昵称都需要消耗一张改名卡',
),


array(
'id' => 'jinsom_member_profile_setting_add',
'type' => 'group',
'title' => '用户资料自定义字段',
'subtitle' => '电脑端默认展示在其他资料设置选项',
'button_title' => '添加',
'default'=>array(
array(
'name'=>'微博',
'value'=>'weibo',
'jinsom_member_profile_setting_power'=>'open'
),
array(
'name'=>'QQ号',
'value'=>'qq',
'jinsom_member_profile_setting_power'=>'open'
),
array(
'name'=>'微信号',
'value'=>'wechat',
'jinsom_member_profile_setting_power'=>'open'
)
),
'fields' => array(

array(
'id'      => 'name',
'type'    => 'text',
'title'   => '名称',
),

array(
'id'      => 'value',
'type'    => 'text',
'title'   => '字段值',
'subtitle'   => '一般为字母，唯一性，记录在数据库',
),

array(
'id'     => 'jinsom_member_profile_setting_power',
'type'   => 'select',
'title'  => '查看权限',
'options'=> array(
'open'=>'对外公开',
'privacy'=>'仅自己可见',
'vip'=>'会员用户可见',
'verify'=>'认证用户可见',
),
'default'=>'open',
),


)
) ,

array(
'id'         => 'jinsom_user_desc_number_max',
'type'       => 'spinner',
'title'      => '个人说明字数上限',
'desc'   => '用户填写的个人说明最大字数上限',
'default'    =>30,
'unit'     => '字',
),

array(
'id'         => 'jinsom_user_default_desc_a',
'type'       => 'textarea',
'title'      => '默认个人说明（自己）',
'subtitle'       => '用户查自己看自己资料时候，如果个人说明为空则显示这里设置的',
'default'       =>'你还没有填写个人说明哦',
),

array(
'id'         => 'jinsom_user_default_desc_b',
'type'       => 'textarea',
'title'      => '默认个人说明（别人）',
'default'       =>'他太懒了，什么都没有写',
'subtitle'       => '用户查看别人资料时候，如果别人说明为空则显示这里设置的',
),


)
));


//会员设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>会员设置</span>',
'icon'        => 'fa fa-heart',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => 'VIP会员开通价格请到-支付充值-VIP会员-进行设置 <a href="#tab=65"><font style="color:#f00;margin-left:10px;">快速传送门</font></a>',
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '注意：会员等级若涉及html，请用单引号代替双引号。若不懂设置，可以联系群主，或者群里问人。',
) ,

array(
'id' => 'jinsom_vip_add',
'type' => 'group',
'title' => '配置会员等级',
'button_title' => '添加设置',
'fields' => array(

array(
'id' => 'c',
'type' => 'text',
'title' => '会员等级',
'desc'       => '就是在这个范围内的成长值显示的文字，比如 "VIP 1","铜牌会员"等等',
),

array(
'id' => 'a',
'type'       => 'spinner',
'unit'       => '成长值',
'title' => '起始值',
'desc'       => '大于或等于',
),

array(
'id' => 'b',
'type'       => 'spinner',
'unit'       => '成长值',
'title' => '结束值',
'desc'       => '小于',
),

array(
'id'      => 'color',
'type'    => 'color',
'title'   => '显示的颜色',
'desc'      => '你可以为每个VIP等级设置不一样的颜色',
'default' => '#FF5722',
),

array(
'id'         => 'discount',
'type'       => 'spinner',
'unit'       => '折',
'title'      => '购买付费内容享受的折扣',
'desc'       => '输入0.8，则享受8折的优惠，注意：只能输入0-1之间的值，输入0，则免费，输入其他值则为不打折。',
'min'      => 0,
'max'      => 1,
'step'     => 0.1,
'default'  => 1,
),

array(
'id'         => 'discount_times',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每日折扣上限次数',
'desc'       => '当天享受折扣次数超过这个值，则只能原价购买。',
'default'  => 10,
),


)
) ,


array(
'id' => 'jinsom_vip_number_everyday',
'type'       => 'spinner',
'unit'       => '成长值',
'title' => '会员用户每天可领取成长值',
'default' => 100,
'subtitle' => '用户每天可以在移动端会员中心进行领取，如果填0则不开启每日领取功能',
),

array(
'id' => 'jinsom_vip_page_text',
'type'       => 'text',
'title' => '移动端会员卡片右上角标志',
'default' => 'LightSNS',
'subtitle' => '支持html代码，双引号要替换为单引号，如果留空则不显示',
),


array(
'id'=>'jinsom_mobild_vip_page_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '会员中心底部自定义区域',
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),





)
));

//金币设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>金币设置</span>',
'icon'        => 'fa fa-diamond',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '<font style="color:#f00;margin-left:10px;">如何修改金币的图标<a href="https://q.jinsom.cn/6561.html" target="_blank">《查看教程》</a></font>',
),

array(
'id'         => 'jinsom_credit_name',
'type'       => 'text',
'title'      => '金币名称',
'desc'       => '网站的货币名称，比如：钻石、点券、水晶等等，留空则显示金币',
'default'    =>'金币',
'attributes' => array(
'style'    => 'width: 100px;'
),
),


array(
'type' => 'notice',
'style' => 'danger',
'content' => '金币获取方式【提示：当为0时，则不获取/消耗金币/经验】、【论坛发帖和回帖每个论坛可以单独进行设置】',
),

array(
'id'         => 'jinsom_reg_credit',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '首次注册可获得奖励',
'subtitle'       => '用户注册可获得金币数量，默认100',
'default'    =>100,
'step'       => 100,
),

array(
'id'         => 'jinsom_publish_post_credit',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '每次发布可获得奖励',
'subtitle'   => '发布帖子的奖励每个论坛可以单独设置，请到前台论坛进行设置',
'desc'       => '用户发动态、音乐、文章、视频可获得金币数量，默认10，负数则需要对应的金币才可以发表',
'default'    =>10,
'step'       => 10,
),

array(
'id'         => 'jinsom_comment_post_credit',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '每次评论可获得奖励',
'default'    =>5,
'step'       =>5,
'subtitle'   => '回复帖子的奖励每个论坛可以单独设置，请到前台论坛进行设置',
'desc'       => '用户评论动态可获得金币数量，默认5，负数则需要对应的金币才可以评论',
),

array(
'id'         => 'jinsom_like_post_credit',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '每次喜欢内容可获得奖励',
'desc'       => '用户喜欢动态可获得金币数量，默认2,不能为负数',
'default'    =>2,
),     

array(
'id'         => 'jinsom_comment_like_post_credit',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '每次点赞评论可获得奖励',
'desc'       => '用户喜欢动态可获得金币数量，默认1,不能为负数',
'default'    =>1,
),        

array(
'type' => 'notice',
'style' => 'danger',
'content' => '每天获取金币/经验上限次数限制，当天超过设定的次数则不会获得奖励，若是负数则不受次数上限限制。',
),


array(
'id'         => 'jinsom_publish_post_max',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天前几次发布可获得奖励',
'subtitle'       => '用户每天前几次发布动态、音乐、视频、文章可获得奖励上限，默认5次',
'default'    =>5,
),

array(
'id'         => 'jinsom_comment_post_max',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天前几次评论可获得奖励',
'subtitle'       => '用户每天前几次评论动态、音乐、视频、文章可获得奖励上限，默认10次',
'default'    =>10,
),

array(
'id'         => 'jinsom_publish_bbs_max',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天前几次发帖可获得奖励',
'subtitle'       => '用户每天前几次发帖可获得奖励上限，默认5次',
'default'    =>5,
),

array(
'id'         => 'jinsom_comment_bbs_max',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天前几次回帖可获得奖励',
'subtitle'       => '用户每天前几次回帖可获得奖励上限，默认10次',
'default'    =>10,
),

array(
'id'         => 'jinsom_like_post_max',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天前几次喜欢内容可获得奖励',
'subtitle'       => '用户每天前几次喜欢可获得奖励上限，默认10次',
'default'    =>10,
),  

array(
'id'         => 'jinsom_comment_like_max',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天前几次点赞评论可获得奖励',
'subtitle'       => '用户每天前几次点赞评论可获得奖励上限，默认10次',
'default'    =>10,
),


)
));

//付费购买
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>'.__('付费购买','jinsom').'</span>',
'icon'        => 'fa fa-shopping-bag',
'fields'      => array(

array(
'id'                 => 'jinsom_buy_power',
'type'               => 'radio',
'title'              => '付费购买权限',
'subtitle'              => '用户符合指定的权限才能购买付费内容',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_buy_power_exps',
'type'       => 'spinner',
'unit'       => '经验值',
'default'    => 10,
'title'      => '付费购买权限_指定经验值',
'dependency' => array('jinsom_buy_power','==','exp') ,
'desc'       => '用户需要达到这个经验值才可以使用',
),

array(
'id'         => 'jinsom_buy_power_honor_arr',
'type'=>'textarea',
'title'      => '付费购买权限_指定头衔',
'dependency' => array('jinsom_buy_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_buy_power_verify_arr',
'type'=>'textarea',
'title'      => '付费购买权限_指定认证类型',
'dependency' => array('jinsom_buy_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),


array(
'id'         => 'jinsom_normal_user_buy_times',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '普通用户每天购买付费内容上限',
'default'    =>10,
),

array(
'id'         => 'jinsom_vip_user_buy_times',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '会员用户每天购买付费内容上限',
'default'    =>30,
),


array(
'id'      => 'jinsom_buy_post_poundage',
'type'       => 'spinner',
'unit'       => '%',
'title'   => '购买手续费',
'default' => 0,
'subtitle'      => '用户购买付费内容平台所需要收取的手续费，0则不开启，如果含有小数则默认取整，作者最终收益是扣取手续费之后的收益。',
),


array(
'id' => 'jinsom_buy_post_show_comment_on_off',
'type' => 'switcher',
'default' => true,
'title' => '购买评论',
'subtitle' => '开启之后，用户购买付费内容之后将显示在评论列表',
),


array(
'id' => 'jinsom_mobile_buy_post_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '移动端付费页面底部自定义区域',
'subtitle' => '支持html和短代码',
),


)
));


//打赏设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>'.__('打赏设置','jinsom').'</span>',
'icon'        => 'fa fa-gift',
'fields'      => array(

array(
'id'                 => 'jinsom_reward_power',
'type'               => 'radio',
'title'              => '打赏功能使用权限',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_reward_power_exps',
'type'       => 'spinner',
'unit'       => '经验值',
'default'    => 10,
'title'      => '打赏权限_指定经验值',
'dependency' => array('jinsom_reward_power','==','exp') ,
'desc'       => '用户需要达到这个经验值才可以使用',
),

array(
'id'         => 'jinsom_reward_power_honor_arr',
'type'=>'textarea',
'title'      => '打赏权限_指定头衔',
'dependency' => array('jinsom_reward_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_reward_power_verify_arr',
'type'=>'textarea',
'title'      => '打赏权限_指定认证类型',
'dependency' => array('jinsom_reward_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),


array(
'id'         => 'jinsom_reward_min',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '最低打赏金额',
'desc'       => '打赏时最低的打赏金额，默认10',
'default'    =>10,
),

array(
'id'         => 'jinsom_reward_max',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '最高打赏金额',
'desc'       => '打赏时最高的打赏金额，默认1000',
'default'    =>1000,
),


array(
'id'         => 'jinsom_reward_rand_min',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '随机打赏范围最低值',
'desc'       => '电脑端点击打赏时，随机金额的最低值，默认100',
'default'    =>100,
),
array(
'id'         => 'jinsom_reward_rand_max',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '随机打赏范围最高值',
'desc'       => '电脑端点击打赏时，随机金额的最高值，默认500',
'default'    =>500,
),

array(
'id'      => 'jinsom_reward_poundage',
'type'       => 'spinner',
'unit'       => '%',
'title'   => '打赏手续费',
'default' => 0,
'subtitle'      => '用户打赏作者平台所需要收取的手续费，0则不开启，如果含有小数则默认取整，作者最终收益是扣取手续费之后的收益。',
),


array(
'id' => 'jinsom_reward_post_show_comment_on_off',
'type' => 'switcher',
'default' => true,
'title' => '打赏评论',
'subtitle' => '开启之后，用户打赏作者之后将会显示在评论列表',
),


array(
'id' => 'jinsom_reward_number_add',
'type' => 'group',
'title' => '【移动端】打赏预设包',
'subtitle' => '就是移动端打赏界面，预设一些快捷打赏的金币，用户点击之后就可以打赏了，不需要再输入打赏金额。【注意】：预设的打赏包金额要大于最小打赏金额',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'number',
'type'       => 'spinner',
'unit'       => '金币',
'title' => '数量',
),

)
) ,

)
));


//等级设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>'.__('等级设置','jinsom').'</span>',
'icon'        => 'fa fa-rocket',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '注意：等级显示若涉及html，请用单引号代替双引号。若不懂设置，可以联系群主，或者群里问人。',
),


array(
'id' => 'jinsom_lv_add',
'type' => 'group',
'title' => '用户等级配置',
'button_title' => '添加设置',
'fields' => array(

array(
'id' => 'c',
'type' => 'text',
'title' => '等级显示',
'desc'       => '就是在这个经验值范围的显示的等级符号，比如 "小兵","排长","连长","司令"等等',
),

array(
'id' => 'a',
'type'       => 'spinner',
'unit'       => '经验值',
'title' => '起始值',
'desc'       => '大于或等于',
),

array(
'id' => 'b',
'type'       => 'spinner',
'unit'       => '经验值',
'title' => '结束值',
'desc'       => '小于',
),

array(
'id'      => 'color',
'type'    => 'color',
'title'   => '显示的颜色',
'desc'      => '你可以为每个用户等级设置不一样的颜色',
'default' => '#FFB800',
),


)
) ,

array(
'type' => 'notice',
'style' => 'danger',
'content' => '经验获取设置，注意：不能小于或等于0',
),

array(
'id'         => 'jinsom_reg_exp',
'type'       => 'spinner',
'unit'       => '经验',
'title'      => '首次注册可获得经验奖励',
'subtitle'       => '用户注册可获得经验数量，默认100',
'min'        => 1,
'default'    =>100,
),


array(
'id'         => 'jinsom_publish_post_exp',
'type'       => 'spinner',
'unit'       => '经验',
'title'      => '每次发布可获得经验奖励',
'subtitle'   => '发布帖子的奖励每个论坛可以单独设置，请到前台论坛进行设置',
'desc'       => '用户发动态、音乐、文章、视频可获得的经验值，默认10',
'min'        => 1,
'default'    =>10,
),

array(
'id'         => 'jinsom_comment_post_exp',
'type'       => 'spinner',
'unit'       => '经验',
'title'      => '每次评论动态可获得奖励',
'desc'       => '用户评论动态可获得经验值，默认5',
'subtitle'   => '回复帖子的奖励每个论坛可以单独设置，请到前台论坛进行设置',
'min'        => 1,
'default'    =>5,
),

array(
'id'         => 'jinsom_like_post_exp',
'type'       => 'spinner',
'unit'       => '经验',
'title'      => '每次喜欢内容可获得奖励',
'desc'       => '用户喜欢动态可获得经验值，默认2',
'min'        => 1,
'default'    =>2,
),     

array(
'id'         => 'jinsom_comment_like_post_exp',
'type'       => 'spinner',
'unit'       => '经验',
'title'      => '每次点赞评论可获得奖励',
'desc'       => '用户喜欢动态可获得经验值，默认1',
'min'        => 1,
'default'    =>1,
),  

)
));


//签到设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>'.__('签到设置','jinsom').'</span>',
'icon'        => 'fa fa-calendar-check-o',
'fields'      => array(


array(
'id'      => 'jinsom_sign_page_url',
'type'    => 'text',
'title'   => '签到页面的地址',
'subtitle'   => '电脑端的签到页面地址',
'placeholder' => 'https://'
),

array(
'id'      => 'jinsom_get_sign_card_url',
'type'    => 'text',
'title'   => '获取签到卡的地址',
'subtitle'   => '一般是你商城出售签到卡的地址，留空则不显示',
'placeholder' => 'https://'
),

array(
'id' => 'jinsom_sign_add',
'type' => 'group',
'title' => '添加每月签到奖励',
'subtitle' => '如果当天没有奖励，请不用添加即可，请不要添加重复日期。',
'button_title' => '添加',
'fields' => array(

array(
'id'         => 'day',
'type'       => 'spinner',
'unit'       => '号',
'title'      => '日期',
'subtitle'       => '用户在对应的日期签到可以获得对应的奖励',
'desc'       => '取值范围1-31，请勿填其他值！',
),

array(
'id' => 'sign_reward_data',
'type' => 'group',
'title' => '奖励添加',
'subtitle' => '当天签到可获得的奖励',
'button_title' => '添加',
'fields' => array(

array(
'id'     => 'sign_reward_type',
'type'   => 'select',
'title'  => '奖励类型',
'options'=> array(
'credit'=>'金币',
'exp'=>'经验',
'vip'=>'VIP天数',
'charm'=>'魅力值',
'vip_number'=>'成长值',
'sign'=>'补签卡',
'nickname'=>'改名卡',
'honor'=>'头衔',
),
'default'=>'credit',
),

array(
'id' => 'sign_reward_number',
'type'=>'spinner',
'title' => '奖励的数量',
'dependency' => array('sign_reward_type','!=','honor'),
'default'=>10,
),

array(
'id' => 'sign_reward_honor',
'type'=>'text',
'title' => '头衔名称',
'desc' => '用户签到可以获得这个头衔',
'dependency' => array('sign_reward_type','==','honor'),
),


)
),

)
),

array(
'id' => 'jinsom_sign_treasure_add',
'type' => 'group',
'title' => '添加累计签到宝箱奖励',
'subtitle' => '用户当月累计签到达到指定天数可以领取对应的宝箱奖励',
'button_title' => '添加',
'fields' => array(

array(
'id'         => 'day',
'type'       => 'spinner',
'title'      => '当月累计签到天数',
'subtitle' => '用户当月累计签到天数达到该值则可以领取该宝箱奖励',
'desc'       => '取值范围1-31，请勿填其他值！',
),

array(
'id'      => 'img',
'type'    => 'upload',
'title'   => '宝箱图标',
'placeholder' => 'https://'
),

array(
'id' => 'sign_treasure_reward_data',
'type' => 'group',
'title' => '奖励添加',
'subtitle' => '当天签到可获得的奖励',
'button_title' => '添加',
'fields' => array(

array(
'id'     => 'sign_treasure_reward_type',
'type'   => 'select',
'title'  => '奖励类型',
'options'=> array(
'credit'=>'金币',
'exp'=>'经验',
'vip'=>'VIP天数',
'charm'=>'魅力值',
'vip_number'=>'成长值',
'sign'=>'补签卡',
'nickname'=>'改名卡',
'honor'=>'头衔',
),
'default'=>'credit',
),

array(
'id' => 'sign_treasure_reward_number',
'type'=>'spinner',
'title' => '奖励的数量',
'dependency' => array('sign_treasure_reward_type','!=','honor'),
'default'=>10,
),

array(
'id' => 'sign_treasure_reward_honor',
'type'=>'text',
'title' => '头衔名称',
'desc' => '用户签到可以获得这个头衔',
'dependency' => array('sign_treasure_reward_type','==','honor'),
),


)
),

)
),

array(
'id'         => 'jinsom_sign_page_rank_user_number',
'type'       => 'spinner',
'title'      => '签到排行显示的用户数',
'subtitle' => '签到页面的今日签到/累计签到展示的用户数量',
'default'       => 20,
),


array(
'id' => 'jinsom_sign_page_footer_info',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '签到页面底部说明',
'subtitle' => '支持html和短代码，一般用户说明签到的规则等等，如果不懂，随便输入一些内容测试就知道了。',
),


array(
'id' => 'jinsom_sign_page_sidebar_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '签到页面右侧栏自定义区域',
'subtitle' => '支持html和短代码',
),


array(
'id' => 'jinsom_mobile_sign_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '移动端签到页面底部自定义区域',
'subtitle' => '支持html和短代码',
),


)
));

//聊天设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>'.__('聊天设置','jinsom').'</span>',
'icon'        => 'fa fa-comments-o',
'fields'      => array(

array(
'id'                 => 'jinsom_im_power',
'type'               => 'radio',
'title'              => 'IM聊天使用权限（单聊和群聊）',
'subtitle'              => '如果不想让用户使用IM模块，可以将权限设置为仅限管理团队使用',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_im_power_exps',
'type'       => 'spinner',
'unit'       => '经验值',
'default'    => 10,
'title'      => 'IM权限_指定经验值',
'dependency' => array('jinsom_im_power','==','exp') ,
'desc'       => '用户需要达到这个经验值才可以使用IM',
),

array(
'id'         => 'jinsom_im_power_honor_arr',
'type'=>'textarea',
'title'      => 'IM权限_指定头衔',
'dependency' => array('jinsom_im_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_im_power_verify_arr',
'type'=>'textarea',
'title'      => 'IM权限_指定认证类型',
'dependency' => array('jinsom_im_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),


array(
'id'         => 'jinsom_im_user_id',
'type'       => 'spinner',
'default'    => 2,
'title'      => 'IM官方助手用户ID',
'subtitle'       => '你需要设置一个官方助手的用户，用于官方信息的发送，例如：注册欢迎语、商城发卡、下单提醒。禁止设置为管理员帐号。',
),



array(
'id' => 'jinsom_im_group_on_off',
'type' => 'switcher',
'default' => true,
'title' => '群组功能',
'subtitle' => '关闭之后，IM就不会显示群组功能了。',
),





array(
'id'         => 'jinsom_im_content_max',
'type'       => 'spinner',
'unit'       => '字',
'default'    => 500,
'title'      => '聊天内容字数最大上限',
'desc'       => '用户发送单条聊天内容最大字数上限，单聊和群聊都使用这个值。',
),


array(
'id' => 'jinsom_reg_im_tip_on_off',
'type' => 'switcher',
'default' => false,
'title' => '开启注册自动发送第一条IM消息',
),





array(
'id' => 'jinsom_reg_im_notice',
'type' => 'textarea',
'dependency' => array('jinsom_reg_im_tip_on_off','==','true') ,
'title' => '自动发送的IM消息内容',
'default' => '嗨！你好啊！',
'attributes' => array(
'style'    => 'width: 350px;'
),
'desc' => '当用户注册之后，会自动向新注册用户发送一条IM消息（不支持html）'
) ,

array(
'id'      => 'jinsom_im_music',
'type'    => 'upload',
'title'   => 'IM消息提示音',
'subtitle'=>'建议不要太大，一般几KB，mp3格式',
'placeholder' => 'https://',
),


)
));


//多语言设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_base',
'title'       => '<span>'.__('多语言设置','jinsom').'</span>',
'icon'        => 'fa fa-language',
'fields'      => array(

array(
'id' => 'jinsom_languages_on_off',
'type' => 'switcher',
'default' => false,
'title' => '多语言',
'subtitle' => '开启之后，用户可以前台切换其他国家的语言',
),

array(
'id' => 'jinsom_languages_add',
'type' => 'group',
'title' => '添加语言',
'dependency' => array('jinsom_languages_on_off','==','true') ,
'subtitle'      => '第一个语言为网站默认语言，添加的语言必须提前把翻译文件上传到服务器，点击这里查看<a href="https://q.jinsom.cn/17874.html" target="_blank">《教程》</a>',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '语言名称',
'placeholder' => '英语',
'subtitle' => '显示在前台给用户切换的，可以加国家图标',
),

array(
'id' => 'code',
'type' => 'text',
'title' => '语言标识码',
'placeholder' => 'en_US',
'subtitle' => '所有繁体统一使用台湾省繁体，代码是zh_TW',
),


),
'default'=>array(
array(
'name'=>'中文',
'code'=>'zh_CN'
),
array(
'name'=>'English',
'code'=>'en_US'
)
)
) ,

)
));

//运营模块
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_operation',
'title'  => '<span>运营模块<k>重要</k></span>',
'icon'   => 'fa fa-send',
));

//运营模块-SEO
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>'.__('SEO设置','jinsom').'</span>',
'icon'        => 'fa fa-line-chart',
'fields'      => array(


array(
'type' => 'notice',
'style' => 'danger',
'content' => '论坛和话题页面的标题、描述、关键词是在前台论坛背景封面的右上角小齿轮点击进去设置的。每个论坛都可以单独进行设置的。如果不懂可以点击这里<a href="https://q.jinsom.cn/10551.html" target="_blank" style="color:#f00;">《查看教程》</a>',
),

array(
'id'         => 'jinsom_site_name',
'type'       => 'text',
'title'      => '网站名称',
'default'      => 'LightSNS',
'subtitle'      => '你的网站名称',
),

array(
'id'         => 'jinsom_title_index',
'type'       => 'text',
'title'      => '首页标题(title)',
'subtitle'      => '网站标题、浏览器标题',
),

array(
'id'         => 'jinsom_keyword',
'type'       => 'textarea',
'title'      => '网站关键词',
),

array(
'id'         => 'jinsom_description',
'type'       => 'textarea',
'title'      => '网站描述',
),

array(
'type' => 'notice',
'style' => 'success',
'content' => '页面标题设置',
),

array(
'id'         => 'jinsom_title_user',
'type'       => 'textarea',
'title'      => '个人主页标题',
'default'      => "[user_info type='nickname' id='member']-[site_name]",
'desc' =>'支持短代码，[user_info type="nickname" id="member"]是获取当前主页用户的昵称，短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),


array(
'id'         => 'jinsom_title_post',
'type'       => 'textarea',
'title'      => '动态内容页标题',
'default'      => "[post_info type='title']-[site_name]",
'desc' =>"支持短代码，[post_info type='title']是获取内容标题，[site_name]是获取网站名称",
),



array(
'id'         => 'jinsom_title_bbs_post',
'type'       => 'textarea',
'title'      => '帖子内容页标题',
'default'      => "[post_info type='title']-[site_name]",
'desc' =>"支持短代码，[post_info type='title']是获取内容标题，[bbs_name]是获取论坛名称，[site_name]是获取网站名称",
),


array(
'id'         => 'jinsom_title_search',
'type'       => 'textarea',
'title'      => '搜索页面标题',
'default'      => '[search_name]-[site_name]',
'desc' =>'支持短代码，[search_name]是获取搜索关键词，[site_name]是获取网站名称',
),



array(
'id'         => 'jinsom_title_topic',
'type'       => 'textarea',
'title'      => '话题页面标题',
'default'      => '[topic_name]-[site_name]',
'desc' =>'支持短代码，[topic_name]是获取话题名称，[site_name]是获取网站名称，每个话题都是支持单独自定义seo标题的，如果没有设置就默认使用这里的话题标题。',
),


array(
'type' => 'notice',
'style' => 'success',
'content' => '页面描述description设置',
),

array(
'id'         => 'jinsom_author_page_description',
'type'       => 'textarea',
'title'      => '个人主页默认SEO描述',
'default'    => "这是关于[user_info type='nickname' id='member']的个人主页，文章，帖子，音乐，视频...赶快加入[site_name]，和我们分享你的精彩",
'subtitle'      => '个人主页默认描述为当前用户的个人说明，如果用户没有填写个人说明则使用当前设置的缺省描述。',
'desc' =>'支持短代码，短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),


array(
'id'         => 'jinsom_topic_page_description',
'type'       => 'textarea',
'title'      => '话题页面默认SEO描述',
'default'    => '这是关于[topic_name]的话题，欢迎进入[site_name]网发表您对于这个话题的看法，期待你的参与',
'subtitle'      => '话题页面默认描述为当前话题设置的seo描述，如果当前话题没有设置seo描述那么则使用当前话题的话题说明作为描述，如果当前话题的话题描述也是空的，则使用后台设置的缺省描述。',
'desc' =>'支持短代码，[topic_name]是获取话题名称，[site_name]是获取网站名称，每个话题都是支持单独自定义seo描述的，如果没有设置就默认使用这里的话题缺省描述。',
),


)
));

//运营模块-百度熊掌
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>百度推送</span>',
'icon'        => 'fa fa-paw',
'fields'      => array(

array(
'id'     => 'jinsom_baidu_paw_auto_on_off',
'type'   => 'select',
'subtitle'=>'<font style="color:#f00;">信息查看方式：搜索资源平台-搜索服务-快速收录/普通收录-资源提交-API提交-推送接口</font>',
'title'  => '选择百度推送类型',
'options'=> array(
'close'=>'关闭推送',
'normal'=>'普通推送',
'quick'=>'快速推送',
),
'default'=>'close',
),



array(
'id' => 'jinsom_baidu_paw_site',
'type' => 'text',
'title' => '网站地址',
'subtitle' => '在搜索资源平台验证的站点，比如https://q.jinsom.cn',
'dependency' => array('jinsom_baidu_paw_auto_on_off','!=','close') ,
'placeholder' =>'https://q.jinsom.cn',
),

array(
'id' => 'jinsom_baidu_paw_token',
'type' => 'text',
'title' => '推送token',
'dependency' => array('jinsom_baidu_paw_auto_on_off','!=','close') ,
'subtitle' => '在搜索资源平台申请的推送用的准入密钥',
),



)
));

//运营模块-人机验证
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>'.__('人机验证','jinsom').'</span>',
'icon'        => 'fa fa-user-times',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '人机验证配置教程，如果不会请<a href="https://q.jinsom.cn/14606.html" target="_blank" style="color:#f00;">《查看教程》</a>',
),



array(
'id' => 'jinsom_machine_verify_on_off',
'type' => 'switcher',
'default' => false,
'title' => '开启人机验证',
'subtitle'=>'开启之后，前台登录、注册、发表、评论、签到都需要进行人机验证'
),


array(
'id' => 'jinsom_machine_verify_appid',
'type' => 'text',
'title' => '验证码APPID',
'subtitle' => '查看方式：腾讯云验证码后台-APPID列表-查看详情-基础配置-基础信息',
'dependency' => array('jinsom_machine_verify_on_off','==','true') ,
'placeholder' =>'2075***928',
),

array(
'id' => 'jinsom_machine_verify_secretkey',
'type' => 'text',
'title' => '验证码App Secret Key',
'subtitle' => '查看方式：腾讯云验证码后台-APPID列表-查看详情-基础配置-基础信息',
'dependency' => array('jinsom_machine_verify_on_off','==','true') ,
'placeholder' =>'0iIQEb67*****9MwjGcLHtw**',
),


array(
'id'      => 'jinsom_machine_verify_use_for',
'type'    => 'checkbox',
'dependency' => array('jinsom_machine_verify_on_off','==','true') ,
'title'=> '使用人机验证的功能',
'options'    => array(
'login' => '登录',
'reg' => '注册',
'code' =>'获取验证码(手机号、邮箱)',
'publish' => '发布内容(暂不包含转发)',
'comment' =>'评论回复',
'sign' =>'签到',
'pet' =>'出售/捕获宠物',
'publish-secret' =>'发布秘密',
),
'default'  => array('login','reg','code','publish','sign','comment')
),


array(
'id' => 'jinsom_machine_verify_param',
'type' => 'text',
'title' => '机器人/脚本免疫参数',
'subtitle' => '当使用机器人或脚本请求对应的接口时，请带上此参数，就不会被安全拦截了<br><font style="color:#f00;">参数值必须字母开头,默认生成的是字母开头的</font>',
'default' =>'a'.md5(uniqid(microtime(true),true)),
),



)
));

//运营模块-马甲设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>马甲配置</span>',
'icon'        => 'fa fa-user-secret',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '马甲功能只能管理员使用，且马甲用户之间可以相互切换',
),


array(
'id' => 'jinsom_majia_on_off',
'type' => 'switcher',
'default' => false,
'title' => '开启马甲功能',
'subtitle'=>'开启之后，管理员可以在前台快速切换马甲帐号。位置：前台右上角头像下拉菜单-马甲切换'
),

array(
'id'         => 'jinsom_majia_user_id',
'type'       => 'textarea',
'title'      => '马甲用户',
'placeholder'=>'1,2,3,4,5,6,7,8,9,10,11',
'dependency' => array('jinsom_majia_on_off','==','true') ,
'desc' =>'请输入你的马甲用户ID，英文逗号隔开，切记末尾不要有逗号。',
),


)
));


//运营模块-搜索设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>搜索设置</span>',
'icon'        => 'fa fa-search',
'fields'      => array(

array(
'id' => 'jinsom_search_login_on_off',
'type' => 'switcher',
'default' => false,
'title' => '登录才可以搜索',
'subtitle' => '开启之后，用户需要登录才可以进行搜索',
),	


array(
'id' => 'jinsom_mobile_search_post_hot_on_off',
'type' => 'switcher',
'default' => false,
'title' => '热门搜索',
),

array(
'id'         => 'jinsom_mobile_search_post_hot_title',
'type'       => 'text',
'title'      => '热门搜索标题',
'dependency' => array('jinsom_mobile_search_post_hot_on_off','==','true') ,
'default' =>'大家都在搜'
),

array(
'id' => 'jinsom_mobile_search_post_hot_add',
'type' => 'group',
'title' => '添加热门搜索关键词',
'dependency' => array('jinsom_mobile_search_post_hot_on_off','==','true') ,
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '关键词名称',
),

)
),

array(
'id' => 'jinsom_mobile_search_hot_bbs_on_off',
'type' => 'switcher',
'default' => false,
'title' => '热门论坛',
),

array(
'id'         => 'jinsom_mobile_search_hot_bbs_title',
'type'       => 'text',
'title'      => '热门论坛标题',
'dependency' => array('jinsom_mobile_search_hot_bbs_on_off','==','true') ,
'default' =>'热门论坛'
),

array(
'id' => 'jinsom_mobile_search_hot_bbs_list',
'type' => 'textarea',
'title' => '需要展示的论坛',
'subtitle' => '请输入论坛ID，多个ID请用英文逗号隔开，建议不要超过5个',
'dependency' => array('jinsom_mobile_search_hot_bbs_on_off','==','true') ,
),

array(
'id' => 'jinsom_mobile_search_hot_topic_on_off',
'type' => 'switcher',
'default' => false,
'title' => '热门话题',
),

array(
'id'         => 'jinsom_mobile_search_hot_topic_title',
'type'       => 'text',
'title'      => '热门话题标题',
'dependency' => array('jinsom_mobile_search_hot_topic_on_off','==','true') ,
'default' =>'热门话题'
),

array(
'id' => 'jinsom_mobile_search_hot_topic_list',
'type' => 'textarea',
'title' => '需要展示的话题',
'subtitle' => '请输入话题ID，多个ID请用英文逗号隔开，建议不要超过3个',
'dependency' => array('jinsom_mobile_search_hot_topic_on_off','==','true') ,
),



)
));







//运营模块-风险监控
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>风险监控</span>',
'icon'        => 'fa fa-low-vision',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '风险监控可以让你更有效的拦截各种挂和脚本自动发布或者评论。【当用户触发了风险监控，该帐号则自动变为风险账户，自动退出将无法进行登录，需要联系管理员进行解封】',
),


array(
'id' => 'jinsom_danger_on_off',
'type' => 'switcher',
'default' => false,
'title' => '开启风险监控功能',
),


array(
'id'         => 'jinsom_publish_danger_limit',
'type'       => 'spinner',
'unit'       => '秒',
'title'      => '连续发布内容的最小时间间隔',
'dependency' => array('jinsom_danger_on_off','==','true'),
'desc'      => '如果连续发布内容的间隔小于设定的这个值，就可能对方是使用脚本进行刷内容。或者恶意发布垃圾内容，系统将该帐号则自动变为风险账户',
'default'    =>5,
),


array(
'id'         => 'jinsom_comment_danger_limit',
'type'       => 'spinner',
'unit'       => '秒',
'title'      => '连续评论内容的最小时间间隔',
'dependency' => array('jinsom_danger_on_off','==','true'),
'desc'      => '如果连续评论内容的间隔小于设定的这个值，就可能对方是使用脚本进行刷内容。或者恶意发布垃圾内容，系统将该帐号则自动变为风险账户',
'default'    =>1,
),

)
));

//运营模块-安全设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>安全设置</span>',
'icon'        => 'fa fa-shield',
'fields'      => array(

array(
'id'      => 'jinsom_login_safe_a',
'type'    => 'text',
'title'   => '后台登录参数名',
'default' => 'abc',
'attributes' => array(
'style'    => 'width: 100px;'
),
),

array(
'id'      => 'jinsom_login_safe_b',
'type'    => 'text',
'title'   => '后台登录参数值',
'default' => '123',
'attributes' => array(
'style'    => 'width: 100px;'
),
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '你WordPress后台登录地址是'.home_url().'/wp-login.php?'.jinsom_get_option('jinsom_login_safe_a').'='.jinsom_get_option('jinsom_login_safe_b').'',
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '管理员进入后台的两种方案：</br>1、前台登录之后，点击右上角下拉菜单-后台管理</br>2、直接访问<font style="color:#f00;">'.home_url().'/wp-login.php?参数名=参数值</font> 进行登录',
),



array(
'id' => 'jinsom_safe_question_add',
'type' => 'group',
'title' => '用户密保问题',
'subtitle' => '用户找回密码',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'content',
'type' => 'text',
'title' => '问题',
'desc'=>'例如：你的父亲叫什么名字？',
),

)
),


)
));



//运营模块-推广设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>推广设置</span>',
'icon'        => 'fa fa-bullhorn',
'fields'      => array(

array(
'id' => 'jinsom_referral_link_on_off',
'type' => 'switcher',
'default' => false,
'title' => '推广功能',
),

array(
'id'         => 'jinsom_referral_link_name',
'type'       => 'text',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '推广链接参数名称',
'subtitle' =>'如果不懂，请保留默认,wordpress自带参数名称请不要设置，例如：p、cat、author、<p>格式：http://abc.com/author/1?t=123</p>',
'default'    =>'t',
'attributes' => array(
'style'    => 'width: 100px;'
),
),



array(
'type' => 'notice',
'style' => 'danger',
'content' => '点击推广：用户分享的推广链接，被其他用户点击了就会获得对应奖励，每个IP视为一个用户。',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
),


array(
'id'         => 'jinsom_referral_link_times',
'type'       => 'spinner',
'unit'       => '次',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '每天点击推广上限',
'subtitle'      => '用户通过点击推广获得奖励的上限次数，<font style="color:#f00;">如果上限设置为0，则不开启该类型的推广</font>',
'default'    =>10,
),

array(
'id'         => 'jinsom_referral_link_credit',
'type'       => 'spinner',
'unit'       => '金币',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '奖励的金币',
'subtitle'       => '每次有效点击推广链接可获得金币，默认10',
'default'    =>10,
),

array(
'id'         => 'jinsom_referral_link_exp',
'type'       => 'spinner',
'unit'       => '经验',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '奖励的经验',
'subtitle'       => '每次有效点击推广链接可获得经验，默认10',
'default'    =>10,
),


array(
'type' => 'notice',
'style' => 'danger',
'content' => '注册推广：用户分享的推广链接，被其他用户访问并且注册了，就会获得相应的奖励。',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
),

array(
'id'         => 'jinsom_referral_link_reg_max',
'type'       => 'spinner',
'unit'       => '次',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '每天注册推广上限',
'subtitle'      => '用户通过注册推广获得奖励的上限次数，<font style="color:#f00;">如果上限设置为0，则不开启该类型的推广</font>',
'default'    =>10,
),


array(
'id'         => 'jinsom_referral_link_reg_credit',
'type'       => 'spinner',
'unit'       => '金币',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '奖励的金币',
'subtitle'       => '通过推广链接注册，推广的用户可获得金币，默认100',
'default'    =>100,
),

array(
'id'         => 'jinsom_referral_link_reg_exp',
'type'       => 'spinner',
'unit'       => '经验',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '奖励的经验',
'subtitle'       => '通过推广链接注册，推广的用户可获得经验，默认100',
'default'    =>100,
),


array(
'type' => 'notice',
'style' => 'danger',
'content' => '注册并开通会员推广：用户分享的推广链接，被其他用户访问注册，并且开通了会员，就会获得相应的奖励。(通过签到或系统奖励的不计入)',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
),


array(
'id' => 'jinsom_referral_link_opne_vip_credit_on_off',
'type' => 'switcher',
'default' => true,
'title' => '使用金币开通会员也可以获得奖励',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'subtitle' => '关闭之后，推广的用户必须使用人民币（微信/支付宝/迅虎支付）开通的会员才会获得奖励',
),


array(
'id'         => 'jinsom_referral_link_opne_vip_credit',
'type'       => 'spinner',
'unit'       => '金币',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '奖励的金币',
'subtitle'       => '通过推广链接注册并且开了会员，推广的用户可获得金币，默认200',
'default'    =>200,
),

array(
'id'         => 'jinsom_referral_link_opne_vip_exp',
'type'       => 'spinner',
'unit'       => '经验',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '奖励的经验',
'subtitle'       => '通过推广链接注册并且开了会员，推广的用户可获得经验，默认200',
'default'    =>200,
),


array(
'type' => 'notice',
'style' => 'danger',
'content' => '推广的用户注册了并且充值了金币',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
),


array(
'id'         => 'jinsom_referral_link_recharge_credit',
'type'       => 'spinner',
'unit'       => '%',
'dependency' => array('jinsom_referral_link_on_off','==','true'),
'title'      => '奖励充值金额的百分比',
'subtitle'       => '通过推广链接注册并且充值金币，每次充值金币，推广者可以获得充值金币的百分比，如果为小数，则向下取整',
'default'    =>0,
),


)
));


//运营模块-维护模式
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>维护模式</span>',
'icon'        => 'fa fa-warning',
'fields'      => array(

array(
'id' => 'jinsom_maintenance_mode_on_off',
'type' => 'switcher',
'default' => false,
'title' => '维护模式',
),

array(
'id'         => 'jinsom_maintenance_mode_cookie',
'type'       => 'text',
'dependency' => array('jinsom_maintenance_mode_on_off','==','true'),
'title'      => '免疫cookie值',
'subtitle' =>'如果当前浏览器存在这个cookie则不受维护模式影响，这样其他用户访问网站是维护模式，管理员可以正常操作网站。请在审查元素Console输入：<font style="color:#f00;">document.cookie="'.jinsom_get_option('jinsom_maintenance_mode_cookie').'"</font> 则当前浏览器对维护模式免疫',
'default'    =>'webtest',
'desc'    =>'请务必修改这个值！',
'attributes' => array(
'style'    => 'width: 100px;'
),
),

array(
'id' => 'jinsom_maintenance_mode_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'dependency' => array('jinsom_maintenance_mode_on_off','==','true'),
'title' => '维护模式显示的内容',
'default'=>'<div>网站维护中，请稍后再访问！</div>',
),




)
));


//运营模块-微信提醒
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>微信提醒</span>',
'icon'        => 'fa fa-wechat',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '【微信消息推送】 此功能需要用户关注了你的公众号才可以收到通知。并且只能认证的微信服务号开通',
),

array(
'id' => 'jinsom_wechat_mp_notice_on_off',
'type' => 'switcher',
'default' => false,
'title' => '微信模版消息提醒',
'subtitle' => '<font style="color:#f00;">使用此功能必须要开启微信公众号登录</font>',
),

array(
'id'         => 'jinsom_wechat_mp_template_id',
'type'       => 'text',
'dependency' => array('jinsom_wechat_mp_notice_on_off','==','true'),
'title'      => '模版ID',
'subtitle' =>'查看：公众号平台-功能-模版消息-我的模版-模版ID',
'placeholder'    =>'DzrXGz3HZpZmyRC****M2dsj5aD-UWh75t0fBrJMww',
),

array(
'id'         => 'jinsom_wechat_mp_first_text',
'type'       => 'text',
'dependency' => array('jinsom_wechat_mp_notice_on_off','==','true'),
'title'      => '二级标题',
'default'    =>'你有一条新提醒',
),

array(
'id'         => 'jinsom_wechat_mp_remark_text',
'type'       => 'text',
'dependency' => array('jinsom_wechat_mp_notice_on_off','==','true'),
'title'      => '备注内容',
'default'    =>'如果不想接收提醒请到设置里面关掉提醒',
),


)
));


//运营模块-海报生成
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>海报生成</span>',
'icon'        => 'fa fa-picture-o',
'fields'      => array(


array(
'id'         => 'jinsom_content_playbill_default_cover',
'type'       => 'upload',
'title'      => '内容海报默认封面',
'desc'       => '如果没有图片的内容，则使用默认的封面',
),

array(
'id'         => 'jinsom_content_playbill_copyright',
'type'       => 'text',
'title'      => '内容海报底部版权说明',
'default'       => '© 来自LightSNS官网',
),

array(
'id' => 'jinsom_content_playbill_footer_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '内容海报自定义内容区域',
'subtitle' => '二维码往上的区域，支持html和短代码',
),


array(
'id'         => 'jinsom_referral_playbill_cover',
'type'       => 'upload',
'title'      => '推广海报封面图',
'desc'       => '这个是必填的',
),

array(
'id'         => 'jinsom_referral_playbill_copyright',
'type'       => 'text',
'title'      => '推广海报底部版权说明',
'default'       => '© 来自LightSNS官网',
),

array(
'id' => 'jinsom_referral_playbill_footer_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '推广海报自定义内容区域',
'subtitle' => '二维码往上的区域，支持html和短代码',
),



)
));



//运营模块-防灌水
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>防灌水</span>',
'icon'        => 'fa fa-map-signs',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '以下防灌水设置主要防止一些恶意捣乱或者广告营销的机器人操作，适当按照自己的业务配置，如果不限制可以设置为9999999，管理员或网站管理肯定是免疫的啦',
),


array(
'id'         => 'jinsom_publish_limit',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天发表动态次数上限',
'subtitle'      => '仅针对发表动态、文章、音乐、视频，不包括发帖、发红包',
'default'    =>10,
),

array(
'id'         => 'jinsom_publish_limit_vip',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '<font style="color:#f00;">VIP用户</font>每天发表动态次数上限',
'subtitle'      => '仅针对发表动态、文章、音乐、视频，不包括发帖、发红包',
'default'    =>10,
),


array(
'id'         => 'jinsom_publish_bbs_limit',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天发表帖子次数上限',
'subtitle'      => '仅针对发表帖子，不包括发表动态',
'default'    =>10,
),

array(
'id'         => 'jinsom_publish_bbs_limit_vip',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '<font style="color:#f00;">VIP用户</font>每天发表帖子次数上限',
'subtitle'      => '仅针对发表帖子，不包括发表动态',
'default'    =>10,
),


array(
'id'         => 'jinsom_comment_limit',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天评论内容次数上限',
'subtitle'      => '仅针对评论动态、文章、音乐、视频，不包括回帖',
'default'    =>30,
),


array(
'id'         => 'jinsom_comment_limit_vip',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '<font style="color:#f00;">VIP用户</font>每天评论内容次数上限',
'subtitle'      => '仅针对评论动态、文章、音乐、视频，不包括回帖',
'default'    =>30,
),


array(
'id'         => 'jinsom_comment_bbs_limit',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天回帖次数上限',
'subtitle'      => '仅针对回帖，不包括评论数量',
'default'    =>30,
),


array(
'id'         => 'jinsom_comment_bbs_limit_vip',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '<font style="color:#f00;">VIP用户</font>每天回帖次数上限',
'subtitle'      => '仅针对回帖，不包括评论数量',
'default'    =>30,
),


array(
'id'         => 'jinsom_chat_limit',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天IM聊天发送消息次数上限',
'subtitle'      => '群聊和单对单聊天的消息数量',
'default'    =>50,
),

array(
'id'         => 'jinsom_chat_limit_vip',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '<font style="color:#f00;">VIP用户</font>每天IM聊天发送消息次数上限',
'subtitle'      => '群聊和单对单聊天的消息数量',
'default'    =>50,
),


array(
'id'         => 'jinsom_upload_limit',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天上传次数上限',
'subtitle'      => '不含头像',
'default'    =>40,
),

array(
'id'         => 'jinsom_upload_limit_vip',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '<font style="color:#f00;">VIP用户</font>每天上传次数上限',
'subtitle'      => '不含头像',
'default'    =>40,
),

array(
'id'         => 'jinsom_upload_avatar_limit',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天上传头像次数上限',
'subtitle'      => '每天上传头像次数',
'default'    =>3,
),

array(
'id'         => 'jinsom_upload_avatar_limit_vip',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '<font style="color:#f00;">VIP用户</font>每天上传头像次数上限',
'subtitle'      => '每天上传头像次数',
'default'    =>3,
),

)
));


//运营模块-敏感词
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>敏感词</span>',
'icon'        => 'fa fa-exclamation-circle',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '开启百度内容敏感词审核之后，评论、发表(标题&内容)、转发、个人说明、聊天、昵称、红包贺语含有敏感词都会自动拦截。百度敏感词拦截支持自定义敏感词列表，具体请前往<a style="color:#f00;" href="https://ai.baidu.com/censoring#/strategylist" target="_blank">https://ai.baidu.com/censoring#/strategylist</a>创建策略',
),


array(
'id' => 'jinsom_baidu_filter_on_off',
'type' => 'switcher',
'default' => false,
'title' => '百度内容敏感词审核',
'desc' =>'申请地址:<a style="color:#f00;" href="https://console.bce.baidu.com/ai/#/ai/antiporn/app/list" target="_blank">https://console.bce.baidu.com/ai/#/ai/antiporn/app/list</a>',
),

array(
'id'         => 'jinsom_baidu_filter_apikey',
'type'       => 'text',
'title'      => '百度内容审核API Key',
'dependency' => array('jinsom_baidu_filter_on_off','==','true'),
'subtitle'      => '查看位置：百度内容审核后台-应用列表-API Key',
'placeholder'=>'qyugut3f******GT3BKbotT7',
),

array(
'id'         => 'jinsom_baidu_filter_secretkey',
'type'       => 'text',
'title'      => '百度内容审核Secret Key',
'dependency' => array('jinsom_baidu_filter_on_off','==','true'),
'subtitle'      => '查看位置：百度内容审核后台-应用列表-Secret Key',
'placeholder'=>'NmLPUOeGlbpD8**********EOPZOiVrSQ ',
),

array(
'id'      => 'jinsom_baidu_filter_use_for',
'type'    => 'checkbox',
'dependency' => array('jinsom_baidu_filter_on_off','==','true') ,
'title'=> '使用敏感词审核的功能',
'subtitle'=> '开启此功能后，需要将内容发送至百度服务器进行审查，可能稍微会有延迟。',
'options'    => array(
'comment' => '评论回复',
'publish' => '发表/编辑内容(含转发、红包贺语)',
'title' => '发表/编辑标题',
'desc' =>'个人说明',
'nickname' => '昵称',
'chat' =>'聊天(含群聊)',
'live' =>'直播',
),
'default'  => array('comment','publish','desc','nickname','chat','title')
),

)
));

//运营模块-IP 封禁
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>IP封禁</span>',
'icon'        => 'fa fa-ban',
'fields'      => array(

array(
'type' => 'notice',
'class' => 'info',
'content' => '被封禁的IP将无法访问网站',
),

array(
'id'         => 'jinsom_ip_stop_one',
'type'       => 'textarea',
'title'      => '单个IP封禁',
'desc'       => '请用英文逗号隔开，为空时则不开启',
),

array(
'id'         => 'jinsom_ip_stop_more',
'type'       => 'textarea',
'title'      => 'IP段封禁(最后一段)',
'desc'       => '只需填写前三段，例如要封禁192.168.111.124的IP段，请填写：192.168.111 ，请用英文逗号隔开，为空时则不开启',
),

array(
'id'         => 'jinsom_ip_stop_domain',
'type'       => 'text',
'title'      => '设置封禁IP跳转的地址',
'desc'       => '需要填http://或https://，可以自己创建一个自定义页面，然后链接到这个页面。',
'placeholder' => 'http://',
'default' =>'https://baidu.com'
),

)
));


//运营模块-404页面
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_operation',
'title'       => '<span>404页面</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'id' => 'jinsom_404_page_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '404页面自定义内容',
'subtitle' => '懒人请点击这里<a href="https://q.jinsom.cn/15462.html" target="_blank" style="color:#f00;">查看教程</a>，直接复制即可。',
'default' =>'404页面'
),


)
));



//外观布局
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_style',
'title'  => '<span>外观布局</span>',
'icon'   => 'fa fa-paint-brush',
));



//外观布局-基本设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_style',
'title'       => '<span>基本设置</span>',
'icon'        => 'fa fa-cog',
'fields'      => array(


array(
'id' => 'jinsom_body_bg_on_off',
'type' => 'switcher',
'default' => false,
'title' => '网站背景设置',
), 

array(
'id'    => 'jinsom_body_bg',
'type'  => 'background',
'title' => '网站背景样式设置',
'dependency' => array('jinsom_body_bg_on_off','==','true') ,
'default'      => array(
'background-repeat'     => 'repeat',
'background-position'   => 'center center',
'background-attachment' => 'fixed',
'background-color'      => '#e2e2e2',
),
),



array(
'id'      => 'jinsom_logo',
'type'    => 'upload',
'title'   => '网站logo',
'subtitle'=>'建议200px*50',
'placeholder' => 'http://',
),


array(
'id'      => 'jinsom_icon',
'type'    => 'upload',
'title'   => '浏览器icon小图标',
'subtitle'   => '浏览器图标',
'desc'=>'可以为任意尺寸的正方形图片，建议不要过大，后台的ico图标请在</br>外观-自定义-站点身份-站点图标，自行设置',
'placeholder' => 'http://',
),

array(
'id' => 'jinsom_main_width',
'type'       => 'spinner',
'unit'       => 'px',
'title' => '电脑端全站宽度',
'default' => 1100,
),

array(
'id' => 'jinsom_border_radius',
'type'       => 'spinner',
'unit'       => 'px',
'title' => '电脑端全站弧度',
'default' => 4,
),

array(
'id' => 'jinsom_main_color',
'type'       => 'color',
'title' => '电脑端元素颜色',
'default' => '#5fb878',
),

)
));

//外观布局-首页设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_style',
'title'       => '<span>SNS首页</span>',
'icon'        => 'fa fa-home',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '【注意】这里设置的是电脑端首页，如果想设置移动端首页请点击这里 <a href="#tab=36"><font style="color:#f00;margin-left:10px;">快速传送门</font></a>',
),


array(
'id'                 => 'jinsom_sns_home_default_sort',
'type'               => 'select',
'title'              => 'SNS首页内容默认排序',
'subtitle'              => '如果用户前台选择则以用户的选择为准',
'options'            => array(
'normal'         => '最新发表',
'comment'         => '最后回复',
'rand'         => '随机内容',
'comment_count_month'         => '本月热门',
'comment_count'         => '所有热门',
),
'default'       =>'normal',
),

array(
'id'                 => 'jinsom_sns_home_load_type',
'type'               => 'select',
'title'              => 'SNS首页数据加载类型',
'options'            => array(
'default'         => '点击加载更多',
'page'         => '分页模式',
'scroll'         => '滚动加载更多',
),
'default'       =>'default',
),


array(
'type'    => 'submessage',
'style'   => 'success',
'content' => '幻灯片设置',
),

array(
'id' => 'jinsom_slider_on_off',
'type' => 'switcher',
'default' => false,
'title' => '首页幻灯片',
),

array(
'id' => 'jinsom_slider_add',
'type' => 'group',
'title' => '添加幻灯片',
'dependency' => array('jinsom_slider_on_off','==','true') ,
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '标题',
'subtitle'=>'就是比较大的那行字，可以为空',
),

array(
'id' => 'desc',
'type' => 'textarea',
'title' => '描述',
'subtitle'=>'就是小的那行字，可以为空',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'subtitle'=>'建议尺寸：1920px*400',
'placeholder' => 'https://'
),

array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'subtitle'=>'可以为空',
'placeholder' => 'https://'
) ,


array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口打开',
),

)
), //幻灯片添加结束


array(
'id' => 'jinsom_slider_height',
'type'       => 'spinner',
'unit'       => 'px',
'title' => '幻灯片高度',
'dependency' => array('jinsom_slider_on_off','==','true') ,
'default' => 250,
'subtitle'    =>'全屏和窄屏建议使用400px，小屏建议使用250px',
),


array(
'id'                 => 'jinsom_slider_default_style',
'type'               => 'radio',
'title'              => '幻灯片显示样式',
'dependency' => array('jinsom_slider_on_off','==','true'),
'options'            => array(
'l'         => '全屏',
'm'         => '窄屏',
's'         => '小屏',
),
'default'       =>'s',
),



array(
'id' => 'jinsom_slider_type',
'type' => 'radio',
'title' => '幻灯片类型',
'dependency' => array('jinsom_slider_on_off','==','true') ,
'options' => array(
'slide' => '普通滑动',
'fade' => '淡出淡入',
'cube' => '立体切换',
'coverflow' => '影片切换',
'flip' => '翻转切换',
),
'default' => 'fade',
),

array(
'id' => 'jinsom_slider_overflow_on_off',
'type' => 'switcher',
'dependency' => array('jinsom_slider_default_style|jinsom_slider_on_off|jinsom_slider_type','==|==|!=','m|true|fade') ,
'default' => false,
'title' => '是否允许溢出',
'desc' => '开启则可以看见幻灯片两边未滑动过来的图片',
),


array(
'id' => 'jinsom_slider_time_a',
'type'       => 'spinner',
'unit'       => '毫秒',
'title' => '滑动速度',
'dependency' => array('jinsom_slider_on_off','==','true') ,
'default' => 900,
'subtitle' => '即slider自动滑动开始到结束的时间（单位ms），也是触摸滑动时释放至贴合的时间',
) ,

array(
'id' => 'jinsom_slider_active_on_off',
'type' => 'switcher',
'dependency' => array('jinsom_slider_on_off','==','true') ,
'default' => false,
'title' => '自动播放',
),
         

array(
'id' => 'jinsom_slider_navigation_on_off',
'type' => 'switcher',
'dependency' => array('jinsom_slider_on_off','==','true') ,
'default' => false,
'title' => '前进后退按钮',
) ,

array(
'id' => 'jinsom_slider_pagination_on_off',
'dependency' => array('jinsom_slider_on_off','==','true') ,
'type' => 'switcher',
'default' => false,
'title' => '分页器',
) ,


array(
'type'    => 'submessage',
'style'   => 'success',
'content' => '媒体格子，不添加则不开启',
),


array(
'id' => 'jinsom_media_add',
'type' => 'group',
'title' => '媒体格子设置',
'subtitle' => '首页展示的四个小格子',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '标题',
'dependency' => array('jinsom_media_add_type','!=|!=','a') ,
),

array(
'id' => 'jinsom_media_add_type',
'type' => 'radio',
'title' => '媒体格子类型',
'options' => array(
'a' => '普通图片',
'b' => '特效图文_a',
'c' => '特效图文_b',
'd' => '视频',
),
'default' => 'a',
),

array(
'id' => 'desc',
'type' => 'textarea',
'title' => '描述',
'dependency' => array('jinsom_media_add_type','!=','d') ,
'subtitle'=>'图文特效才会显示',
),

array(
'id'      => 'video_url',
'type'    => 'upload',
'title'   => '视频地址',
'dependency' => array('jinsom_media_add_type','==','d') ,
'placeholder' => 'https://'
),



array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'dependency' => array('jinsom_media_add_type','!=','d') ,
'subtitle'=>'可以为空',
'placeholder' => 'https://'
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'subtitle'=>'如果是视频类型则为封面,尺寸：251px*200px',
'placeholder' => 'https://'
),


)
) , //添加结束  


array(
'type'    => 'submessage',
'style'   => 'success',
'content' => '自定义区域设置',
),



array(
'id' => 'jinsom_slider_top_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【幻灯片头部】自定义内容',
'subtitle' => '支持html,可以用来设置比较重要的通知。等等',
),

array(
'id' => 'jinsom_slider_bottom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【幻灯片底部】自定义内容',
'subtitle' => '支持html，支持短代码',
),

array(
'id' => 'jinsom_media_bottom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【媒体格子底部】自定义内容',
'subtitle' => '就是官网首页那四个格子的底部区域，支持html',
),

array(
'id' => 'jinsom_ajax_menu_top_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【ajax菜单顶部】自定义内容',
'subtitle' => '支持html，支持短代码',
),

array(
'id' => 'jinsom_ajax_menu_bottom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【ajax菜单底部】自定义内容',
'subtitle' => '支持html，支持短代码',
),







)
));


//外观布局-头部设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_style',
'title'       => '<span>头部设置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(


array(
'type' => 'notice',
'style' => 'danger',
'dependency' => array('jinsom_header_type','==','default'),
'content' => '需要添加头部导航菜单请到WordPress后台-外观-菜单进行设置  <a href="/wp-admin/nav-menus.php" target="_blank"><font style="color:#f00;margin-left:10px;">快速传送门</font></a>',
),


array(
'type' => 'notice',
'style' => 'danger',
'dependency' => array('jinsom_header_type','==','custom'),
'content' => '自定义导航栏不支持WordPress自带的菜单，需要显示什么菜单，手动自定义设置即可。',
),

array(
'id'                 => 'jinsom_header_type',
'type'               => 'radio',
'title'              => '头部展示类型',
'options'            => array(
'default'              => '默认设置',
'custom'           => '自定义设置',
),
'default'       =>'default',
),


array(
'id' => 'jinsom_header_avatar_menu_add',
'type' => 'group',
'title' => '头部头像下拉菜单',
'subtitle' => '头部下拉菜单<a href="https://q.jinsom.cn/26691.html" target="_blank" style="color:#f00;">《参考教程》</a>',
'dependency' => array('jinsom_header_type','==','default'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '菜单名称',
'dependency' => array('jinsom_header_avatar_menu_type','!=','custom'),
),

array(
'id'             => 'jinsom_header_avatar_menu_type',
'type'           => 'select',
'title'          => '菜单类型',
'options'      => array(
'home' => '我的主页',
'mywallet' => '我的钱包',
'vip' => '开通/续费会员',
'order' => '我的订单',
'content' => '内容管理',
'language' => '多语言',
'majia' => '马甲切换',
'admin' => '后台管理',
'password' => '修改密码/修改Ta密码',
'key-recharge' => '卡密兑换',
'loginout' => '退出登录',
'custom' => '自定义菜单',
),
),

array(
'type'    => 'content',
'content' => '这是一个两个值的菜单，请用这样的格式填写：<font style="color:#f00;">菜单名称1|菜单名称2</font>',
'dependency' => array('jinsom_header_avatar_menu_type','any','vip,password'),
),

array(
'id' => 'custom',
'type' => 'textarea',
'dependency' => array('jinsom_header_avatar_menu_type','==','custom'),
'title' => '菜单自定义内容',
'default'=>'<li><a href="#" target="_blank">菜单名称</a></li>'
),

)
),






array(
'id' => 'jinsom_header_bg_on_off',
'type' => 'switcher',
'default' => false,
'dependency' => array('jinsom_header_type','==','default'),
'title' => '头部导航样式设置',
), 

array(
'id'    => 'jinsom_header_bg',
'type'  => 'background',
'title' => '导航栏背景风格配置',
'dependency' => array('jinsom_header_bg_on_off|jinsom_header_type','==|==','true|default'),
'default'      => array(
'background-repeat'     => 'repeat-x',
'background-position'   => 'center center',
'background-attachment' => 'scroll',
'background-color'      => '#202223',
),
),

array(
'type'    => 'submessage',
'dependency' => array('jinsom_header_type','==','default'),
'style'   => 'success',
'content' => '菜单字体颜色大小配置',
),

array(
'id'      => 'jinsom_menu_one_font_size',
'type'       => 'spinner',
'unit'       => 'px',
'title'   => '一级菜单字体大小',
'default' => 14,
'min'     => 12,
'dependency' => array('jinsom_header_type','==','default'),
'desc'      => '默认14px',
),

array(
'id'      => 'jinsom_menu_two_font_size',
'type'       => 'spinner',
'unit'       => 'px',
'title'   => '二级菜单字体大小',
'default' => 13,
'min'     => 12,
'dependency' => array('jinsom_header_type','==','default'),
'desc'      => '默认14px',
),

array(
'id'        => 'jinsom_menu_one',
'type'      => 'color_group',
'title'     => '一级菜单颜色配置',
'dependency' => array('jinsom_header_type','==','default'),
'options'   => array(
'normal' => '默认字体颜色',
'current' => '当前菜单字体颜色',
'hover' => 'hover的菜单颜色',
),
'default'   => array(
'normal' => '#999',
'current' => '#fff',
'hover' => '#fff45c',
)
),

array(
'id'        => 'jinsom_menu_two',
'type'      => 'color_group',
'title'     => '二级/三级菜单颜色配置',
'dependency' => array('jinsom_header_type','==','default'),
'options'   => array(
'normal' => '默认字体颜色',
'current' => '当前菜单字体颜色',
'hover' => 'hover字体颜色',
'bg' => 'hover背景颜色',
),
'default'   => array(
'normal' => '#999',
'current' => '#000',
'hover' => '#fff',
'bg' => '#5FB878',
)
),

array(
'id'        => 'jinsom_avatar_menu',
'type'      => 'color_group',
'title'     => '头像下拉菜单',
'dependency' => array('jinsom_header_type','==','default'),
'options'   => array(
'normal' => '默认字体颜色',
'hover' => 'hover字体颜色',
'bg' => 'hover背景颜色',
),
'default'   => array(
'normal' => '#565656',
'hover' => '#fff',
'bg' => '#5FB878',
)
),


array(
'type'    => 'submessage',
'dependency' => array('jinsom_header_type','==','default'),
'style'   => 'success',
'content' => '图标按钮配置',
),



array(
'id' => 'jinsom_header_search_on_off',
'dependency' => array('jinsom_header_type','==','default'),
'type' => 'switcher',
'default' => true,
'title' => '搜索图标',
), 

array(
'id'      => 'jinsom_header_search_color',
'dependency' => array('jinsom_header_search_on_off|jinsom_header_type','==|==','true|default'),
'type'    => 'color',
'title'   => '搜索图标颜色',
'default' => '#a4a4a4',
),

array(
'id' => 'jinsom_header_notice_on_off',
'dependency' => array('jinsom_header_type','==','default'),
'type' => 'switcher',
'default' => true,
'title' => '消息提醒图标',
), 

array(
'id'      => 'jinsom_header_notice_color',
'dependency' => array('jinsom_header_notice_on_off|jinsom_header_type','==|==','true|default'),
'type'    => 'color',
'title'   => '消息提醒图标颜色',
'default' => '#a4a4a4',
),  
        

array(
'id' => 'jinsom_header_publish_on_off',
'dependency' => array('jinsom_header_type','==','default'),
'type' => 'switcher',
'default' => true,
'title' => '发表按钮',
'subtitle' => '<font style="color:#f00;">开启后需要在-内容模块-基本设置-添加发布选项才会显示。</font>',
),  


array(
'id'      => 'jinsom_header_publish_color',
'dependency' => array('jinsom_header_publish_on_off|jinsom_header_type','==|==','true|default'),
'type'    => 'color',
'title'   => '发表按钮图标颜色',
'default' => '#5FB878',
),


array(
'type'    => 'submessage',
'dependency' => array('jinsom_header_type','==','default'),
'style'   => 'success',
'content' => '未登录前的样式',
),


array(
'id' => 'jinsom_header_login_on_off',
'dependency' => array('jinsom_header_type','==','default'),
'type' => 'switcher',
'default' => true,
'title' => '头部登录/注册按钮',
), 

array(
'id'        => 'jinsom_header_login_btn',
'type'      => 'color_group',
'dependency' => array('jinsom_header_login_on_off|jinsom_header_type','==|==','true|default'),
'title'     => '登录按钮颜色',
'options'   => array(
'font' => '字体颜色',
'bg' => '背景颜色',
),
'default'   => array(
'font' => '#ffffff',
'bg' => '#6D89BB',
)
),

array(
'id'        => 'jinsom_header_reg_btn',
'type'      => 'color_group',
'dependency' => array('jinsom_header_login_on_off|jinsom_header_type','==|==','true|default'),
'title'     => '注册按钮颜色',
'options'   => array(
'font' => '字体颜色',
'bg' => '背景颜色',
),
'default'   => array(
'font' => '#ffffff',
'bg' => '#5FB878',
)
),


array(
'type'    => 'submessage',
'dependency' => array('jinsom_header_type','==','default'),
'style'   => 'success',
'content' => '登录后的样式',
),

array(
'id'      => 'jinsom_header_username_color',
'dependency' => array('jinsom_header_type','==','default'),
'type'    => 'color',
'title'   => '用户昵称的字体颜色',
'default' => '#999999',
),

array(
'id' => 'jinsom_header_vip_ico_on_off',
'dependency' => array('jinsom_header_type','==','default'),
'type' => 'switcher',
'default' => false,
'title' => '会员VIP标识',
), 



array(
'id' => 'jinsom_header_nav_html',
'dependency' => array('jinsom_header_type','==','default'),
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '导航栏顶部自定义内容',
'subtitle' => '支持html，支持短代码',
),


array(
'id'         => 'jinsom_header_custom_html',
'type'       => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'      => '电脑端头部自定义底部代码',
'dependency' => array('jinsom_header_type','==','custom'),
'subtitle'        => '支持html和短代码，短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
'default'  =>'<p>Hello world</p>',
),


)
));


//外观布局-偏好设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_style',
'title'       => '<span>偏好设置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '这里仅仅是设置网站默认的初始风格，如果用户使用了偏好设置，则以用户的设置为准。偏好设置仅仅保存在用户的当前电脑，并不会保存在服务器',
),


array(
'id' => 'jinsom_preference_bg_add',
'type' => 'group',
'title' => '偏好设置主题风格配置',
'subtitle' => '若不懂请查看<a href="https://q.jinsom.cn/14803.html" target="_blank" style="color:#f00;">《配置教程》</a><br>默认主题风格是主题的风格',
'button_title' => '添加主题风格',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '显示名称',
),

array(
'id'      => 'img',
'type'    => 'upload',
'title'   => '主题风格的封面图',
'desc' => '100*100px的正方形尺寸',
'placeholder' => 'https://'
),

array(
'id'      => 'css',
'type'    => 'text',
'title'   => 'css链接地址',
'subtitle'   => '如果地址在oss上请注意防盗链',
'desc' => '该主题风格的css路径，一般建议上传到oss/七牛',
'placeholder' => 'https://'
),



)
),


array(
'id'                 => 'jinsom_post_list_type',
'type'               => 'radio',
'title'              => '内容列表样式',
'options'            => array(
'post-style-time'              => '时光轴',
'post-style-block'         => '矩形',
),
'default'       =>'post-style-block',
),

array(
'id'                 => 'jinsom_sidebar_style',
'type'               => 'radio',
'title'              => '侧栏显示位置',
'options'            => array(
'sidebar-style-left'              => '左侧',
'sidebar-style-right'         => '右侧',
),
'default'       =>'sidebar-style-right',
),

array(
'id'                 => 'jinsom_index_default_style',
'type'               => 'radio',
'title'              => '网站版面结构',
'options'            => array(
'layout-single'              => '单栏（没有右侧工具栏）',
'layout-double'         => '双栏',
),
'default'       =>'layout-double',
),


array(
'id'                 => 'jinsom_post_space_default_style',
'type'               => 'radio',
'title'              => '帖子间隔默认样式',
'subtitle'              => '就是论坛帖子内页的评论列表模块，默认是一块块隔开的',
'options'            => array(
'bbs-post-space-on'              => '隔开',
'bbs-post-space-off'         => '拼接',
),
'default'       =>'bbs-post-space-on',
),	

)
));


//外观布局-侧栏设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_style',
'title'       => '<span>侧栏设置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'id'      => 'jinsom_custom_sidebar_number',
'type'       => 'spinner',
'unit'       => '个',
'title'   => '自定义侧栏小工具数量',
'default' => 10,
'min'     => 1,
'subtitle'      => '默认10个，请前往<a href="/wp-admin/widgets.php" target="_blank">《小工具》</a>进行设置',
),


array(
'id'             => 'jinsom_sidebar_toolbar_sorter_e',
'type'           => 'sorter',
'title'          => '电脑端全局右侧栏设置',
'default'        => array(
'enabled'      => array(
'task' => '任务',
'now' => '实时动态',
'chat' => '聊天',
'setting' => '偏好设置',
'top' => '返回顶部',
),
'disabled'     => array(
'publish'   => '发布',
'sort' => '内容排序',
'language' => '多语言',
'bottom' => '到底部',
'notice' => '消息提醒',
'search' => '搜索',
'custom_a' => '自定义-1',
'custom_b' => '自定义-2',
'custom_c' => '自定义-3',
),
),
'subtitle'      => '拖拽排列顺序，拖动模块到右侧可关闭，如果这里关闭了偏好设置，那么全局都关闭了。【建议开启5-6个即可】',
),


array(
'id'         => 'jinsom_sidebar_toolbar_sorter_custom_a',
'type'       => 'textarea',
'title'      => '电脑端右侧栏-自定义-1',
'subtitle'      => '请严格按这种格式填写，自定义按钮的提示语请在自定义css修改',
'default'      => '<li class="custom_a"><span class="title">自定义-1</span><i class="jinsom-icon jinsom-yingyong3"></i></li>',
),

array(
'id'         => 'jinsom_sidebar_toolbar_sorter_custom_b',
'type'       => 'textarea',
'title'      => '电脑端右侧栏-自定义-2',
'subtitle'      => '请严格按这种格式填写，自定义按钮的提示语请在自定义css修改',
'default'      => '<li class="custom_b"><span class="title">自定义-2</span><i class="jinsom-icon jinsom-yingyong3"></i></li>',
),

array(
'id'         => 'jinsom_sidebar_toolbar_sorter_custom_c',
'type'       => 'textarea',
'title'      => '电脑端右侧栏-自定义-3',
'subtitle'      => '请严格按这种格式填写，自定义按钮的提示语请在自定义css修改',
'default'      => '<li class="custom_c"><span class="title">自定义-3</span><i class="jinsom-icon jinsom-yingyong3"></i></li>',
),


array(
'id'             => 'jinsom_sidebar_bbs_toolbar_sorter_a',
'type'           => 'sorter',
'title'          => '论坛帖子内页左侧工具栏设置',
'default'        => array(
'enabled'      => array(
'like' => '喜欢',
'auto' => '自动目录',
'comment' => '评论',
'reward' => '打赏',
'gift' => '送礼物',
'reprint' => '转发',
'back' => '返回论坛',
),
),
'subtitle'      => '拖拽排列顺序，拖动模块到右侧可关闭',
),


)
));


//外观布局-底部设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_style',
'title'       => '<span>底部设置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'id'                 => 'jinsom_footer_type',
'type'               => 'radio',
'title'              => '底部展示类型',
'options'            => array(
'default'              => '默认设置',
'custom'           => '自定义设置',
),
'default'       =>'default',
),


array(
'id'      => 'jinsom_footer_bg',
'type'    => 'color',
'title'   => '底部背景颜色',
'dependency' => array('jinsom_footer_type','==','default'),
'default' => '#000',
),


array(
'id'             => 'jinsom_footer_sorter',
'type'           => 'sorter',
'title'          => '底部模块设置',
'dependency' => array('jinsom_footer_type','==','default'),
'default'        => array(
'enabled'      => array(
'logo'   => 'logo模块',
'menu1' => '菜单1',
'menu2' => '菜单2',
'menu3' => '菜单3',
'code1' => '二维码1',
'code2' => '二维码2',
),
),
'subtitle'      => '拖拽排列顺序，拖动模块到右侧可关闭',
),



array(
'id'    => 'jinsom_footer_tab',
'type'  => 'tabbed',
'dependency' => array('jinsom_footer_type','==','default'),
'title' => '配置底部模块选项',
'tabs'  => array(

array(
'title'  => 'logo模块',
'fields' => array(


array(
'id'      => 'jinsom_footer_logo',
'type'    => 'upload',
'title'   => '底部左侧logo',
'subtitle' => '最大宽度186px,高度不限',
'placeholder' => 'https://',
),

array(
'id' => 'jinsom_footer_logo_text',
'type' => 'textarea',
'title' => 'logo底下的文字',
'subtitle'=>'字数不限，自己根据自己需求填写，支持html',
) ,


),
),

array(
'title'  => '菜单1',
'fields' => array(

//菜单1
array(
'id' => 'jinsom_footer_menu1_title',
'type' => 'text',
'title' => '菜单1标题',
'subtitle'=>'建议不超过5个字',
) ,
 
array(
'id' => 'jinsom_footer_menu1_add',
'type' => 'group',
'title' => '菜单1添加链接',
'button_title' => '添加',
'fields' => array(
array(
'id' => 'title',
'type' => 'text',
'title' => '菜单名称',
) ,
array(
'id' => 'link',
'type' => 'text',
'title' => '菜单链接',
'placeholder' => 'http://',
) ,

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口打开',
),

array(
'id' => 'nofollow',
'type' => 'switcher',
'default' => false,
'title' => 'nofollow',
),  

)
) , //添加结束 

),
),

array(
'title'  => '菜单2',
'fields' => array(

//菜单2
array(
'id' => 'jinsom_footer_menu2_title',
'type' => 'text',
'title' => '菜单2标题',
'subtitle'=>'建议不超过5个字',
) ,
 
array(
'id' => 'jinsom_footer_menu2_add',
'type' => 'group',
'title' => '菜单2添加链接',
'button_title' => '添加',
'fields' => array(
array(
'id' => 'title',
'type' => 'text',
'title' => '菜单名称',
) ,
array(
'id' => 'link',
'type' => 'text',
'title' => '菜单链接',
'placeholder' => 'http://',
) ,

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口打开',
), 

array(
'id' => 'nofollow',
'type' => 'switcher',
'default' => false,
'title' => 'nofollow',
),

)
) , //添加结束 

),
),

array(
'title'  => '菜单3',
'fields' => array(

//菜单3
array(
'id' => 'jinsom_footer_menu3_title',
'type' => 'text',
'title' => '菜单3标题',
'subtitle'=>'建议不超过5个字',
) ,
 
array(
'id' => 'jinsom_footer_menu3_add',
'type' => 'group',
'title' => '菜单3添加链接',
'button_title' => '添加',
'fields' => array(
array(
'id' => 'title',
'type' => 'text',
'title' => '菜单名称',
) ,
array(
'id' => 'link',
'type' => 'text',
'title' => '菜单链接',
'placeholder' => 'http://',
) ,

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口打开',
) , 

array(
'id' => 'nofollow',
'type' => 'switcher',
'default' => false,
'title' => 'nofollow',
),

)
) , //添加结束  

),
),

array(
'title'  => '二维码1',
'fields' => array(

//二维码1标题
array(
'id' => 'jinsom_footer_code1_title',
'type' => 'text',
'title' => '二维码1标题',
'subtitle'=>'建议不超过5个字',
) ,

//二维码1
array(
'id'      => 'jinsom_footer_code1',
'type'    => 'upload',
'title'   => '二维码1',
'subtitle' => '宽110px,高110px',
'placeholder' => 'https://',
),

),
),

array(
'title'  => '二维码2',
'fields' => array(

//二维码2标题
array(
'id' => 'jinsom_footer_code2_title',
'type' => 'text',
'title' => '二维码2标题',
'subtitle'=>'建议不超过5个字',
) ,

//二维码2
array(
'id'      => 'jinsom_footer_code2',
'type'    => 'upload',
'title'   => '二维码2',
'subtitle' => '宽110px,高110px',
'placeholder' => 'https://',
),

),
),

),
),


array(
'id'         => 'jinsom_footer_bottom_text',
'type'       => 'textarea',
'title'      => '最底部版权处的文字',
'default'    => '© 2020 LightSNS 粤ICP备15028684号-3',
'dependency' => array('jinsom_footer_type','==','default'),
'subtitle'        => '支持html和短代码，可以通过代码控制居右、居中等等。短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>'
),


array(
'id'         => 'jinsom_footer_custom_text',
'type'       => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'      => '自定义底部代码',
'dependency' => array('jinsom_footer_type','==','custom'),
'subtitle'        => '支持html和短代码，短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
'default'  =>'<p>Hello world</p>',
),


array(
'id'                 => 'jinsom_statistics_type',
'type'               => 'radio',
'title'              => '统计代码类型',
'subtitle'              => '站长统计代码',
'options'            => array(
'off'    => '不使用统计',
'cnzz'   => '友盟(cnzz)',
'baidu'  => '百度',
),
'default'    =>'off',
),


array(
'id'         => 'jinsom_statistics_cnzz_id',
'type'       => 'text',
'title'      => '友盟(cnzz)统计代码ID',
'dependency' => array('jinsom_statistics_type','==','cnzz'),
'placeholder'=> '1261499055'
),

array(
'id'         => 'jinsom_statistics_baidu_id',
'type'       => 'text',
'title'      => '百度统计代码ID',
'dependency' => array('jinsom_statistics_type','==','baidu'),
'placeholder'=> 'cfdef07d5c548f16c37f8d2dd6a0030e'
),

)
));



//外观布局-自定义代码
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_style',
'title'       => '<span>自定义代码</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => 'LightSNS目前的大部分css和js储存在<a href="https://github.com/jinsom/LightSNS-CDN/releases" target="_blank" style="color: #03A9F4;text-decoration:underline;">Github：LightSNS-CDN</a>，如果你想把文件上传到你的oss或者服务器，请下载上传到你需要的地方，然后修改以下路径，切记勿动文件层级关系。',
),

array(
'id'         => 'jinsom_js_cdn_url',
'type'       => 'text',
'title'      => '网站css/js储存路径url',
'desc' =>'<font style="color:#f00;font-weight:bold;">如果不懂请不要填写，否则网站会无法正常运作！</font>'
),


array(
'type' => 'notice',
'style' => 'danger',
'content' => '美化或修改主题外观能不要改源码尽量不要改，涉及css或js的修改，请填在这个位置，如果大量css/js请单独引入自己自定义的css/js文件',
),

array(
'id'         => 'jinsom_custom_css',
'type'       => 'code_editor',
'settings' => array(
'mode'   => 'css',
),
'default'=>'
.class{color:#f00;}
',
'title'      => '【电脑端】自定义css代码',
'subtitle' => '只能放css代码，不能放html代码，如果自定义css比较少可以在这里写，如果比较多可以单独建一个文件，然后直接在下面引入css文件',
),


array(
'id' => 'jinsom_header_custom_code',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【电脑端】头部自定义',
'subtitle' => '支持引入多个css或js文件,反正可以放html代码，随便你放什么',
),


array(
'id' => 'jinsom_footer_custom_code',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【电脑端】底部自定义',
'subtitle' => '一般用于引入js文件，支持引入多个',
),


array(
'type'    => 'submessage',
'style'   => 'success',
'content' => '下面是移动端的自定义js和css代码',
),

array(
'id'         => 'jinsom_mobile_custom_css',
'type'       => 'code_editor',
'settings' => array(
'mode'   => 'css',
),
'default'=>'
.class{color:#f00;}
',
'title'      => '【移动端】自定义css代码',
'subtitle' => '只能放css代码，不能放html代码，如果自定义css比较少可以在这里写，如果比较多可以单独建一个文件，然后直接在下面引入css文件',
),


array(
'id' => 'jinsom_mobile_header_custom_code',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【移动端】头部自定义',
'subtitle' => '支持引入多个css或js文件,反正可以放html代码，随便你放什么，是在head标签内',
),


array(
'id' => 'jinsom_mobile_footer_custom_code',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '【移动端】底部自定义',
'subtitle' => '一般用于引入js文件，支持引入多个',
),


)
));



//移动端设置
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_mobile',
'title'  => '<span>移动设置</span>',
'icon'   => 'fa fa-th-large',
));


//移动端设置-基本设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_mobile',
'title'       => '<span>基本设置</span>',
'icon'        => 'fa fa-cog',
'fields'      => array(



array(
'id'      => 'jinsom_mobile_default_color',
'type'    => 'color',
'title'   => '移动端默认颜色风格',
'desc'=>'导航栏、菜单栏、发布按钮等默认颜色配置',
'default' => '#46c47c',
),

array(
'id'      => 'jinsom_mobile_default_bg_color',
'type'    => 'color',
'title'   => '移动端页面背景色',
'subtitle'=>'页面底色',
'default' => '#f8f8f8',
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '注意：底部Tab页的消息和“我的”里面的消息只能同时存在一个',
),

array(
'id' => 'jinsom_mobile_tab_add',
'type' => 'group',
'title' => '移动端首页Tab页面添加',
'subtitle' => '建议添加5个选项适中<br><font style="color:#f00;">如果底部不需要发布按钮，也需要添加之后选择隐藏即可</font>',
'max'      => 9,
'button_title' => '添加',
'fields' => array(

array(
'id' => 'header_name',
'type' => 'text',
'title' => '头部名称',
'subtitle' => '头部中间的位置',
),

array(
'id' => 'jinsom_mobile_tab_type',
'type' => 'select',
'title' => '页面类型',
'options'      => array(
'sns' => 'SNS首页',
'notice' => '消息页面',
'find' => '发现页面',
'mine' => '我的页面',
'bbs' => '论坛大厅',
'video' => '视频专题',
'topic' => '话题中心',
'publish' => '发布按钮',
'custom' => '自定义',
),
),

array(
'id' => 'page_id',
'type' => 'number',
'title' => '页面ID',
'default' => 1,
'subtitle' => '创建该页面所对应的页面ID',
'dependency' => array('jinsom_mobile_tab_type','any','bbs,video,topic'),
),

array(
'id' => 'name',
'type' => 'text',
'title' => 'Tab名称',
'subtitle' => '建议2个字',
'dependency' => array('jinsom_mobile_tab_type','!=','publish'),
),


array(
'id' => 'icon',
'type' => 'textarea',
'title' => 'Tab图标',
'placeholder'=>'留空则使用程序自带的图标',
'subtitle' => '可使用程序内置图标或自定义图标，<a href="https://q.jinsom.cn/iconfont" target="_blank" style="color:#f00;">《内置图标参考》</a>',
'dependency' => array('jinsom_mobile_tab_type','!=','publish'),
),



array(
'id' => 'header_left_type',
'type' => 'select',
'title' => '左上角头部显示类型',
'dependency' => array('jinsom_mobile_tab_type','!=','publish'),
'options'      => array(
'no' => '不显示',
'avatar' => '当前用户头像',
'search' => '搜索',
'custom' => '自定义代码',
),
),

array(
'id' => 'header_left_custom',
'type' => 'textarea',
'title' => '左上角头部自定义代码',
'subtitle' => '可以输入文字、图标代码等',
'dependency' => array('header_left_type','==','custom'),
),

array(
'id' => 'header_left_link',
'type' => 'text',
'title' => '左上角头部点击链接',
'subtitle' => '支持短代码',
'dependency' => array('header_left_type','not-any','no,search'),
),

array(
'id' => 'header_right_type',
'type' => 'select',
'title' => '右上角头部显示类型',
'dependency' => array('jinsom_mobile_tab_type','!=','publish'),
'options'      => array(
'no' => '不显示',
'avatar' => '当前用户头像',
'search' => '搜索',
'custom' => '自定义代码',
),
),

array(
'id' => 'header_right_custom',
'type' => 'textarea',
'title' => '右上角头部自定义代码',
'subtitle' => '可以输入文字、图标代码等',
'dependency' => array('header_right_type','==','custom'),
),

array(
'id' => 'header_right_link',
'type' => 'text',
'title' => '右上角头部点击链接',
'subtitle' => '支持短代码',
'dependency' => array('header_right_type','not-any','no,search'),
),





array(
'id' => 'is_login',
'type' => 'switcher',
'title' => '登录才能访问',
'subtitle' => '<font style="color:#f00;">第一个tab不能开启登录访问，发现页面/我的页面默认就是需要登录才能访问</font>',
'dependency' => array('jinsom_mobile_tab_type','not-any|==','notice,mine,publish'),
'default' => false,
),



array(
'id' => 'hide_navbar',
'type' => 'switcher',
'title' => '滚动隐藏头部',
'dependency' => array('jinsom_mobile_tab_type','!=','publish'),
'default' => true,
),

array(
'id' => 'hide_toolbar',
'type' => 'switcher',
'title' => '滚动隐藏底部',
'dependency' => array('jinsom_mobile_tab_type','!=','publish'),
'default' => true,
),


array(
'id'                 => 'publish_icon',
'type'               => 'radio',
'title'              => '选择图标',
'inline'   => true,
'dependency' => array('jinsom_mobile_tab_type','==','publish'),
'options'            => array(
'jinsom-yuanquan'              => '<i class="jinsom-icon jinsom-yuanquan"></i>',
'jinsom-fabu'         => '<i class="jinsom-icon jinsom-fabu"></i>',
'jinsom-zhifeiji'           => '<i class="jinsom-icon jinsom-zhifeiji"></i>',
'jinsom-xiangji'           => '<i class="jinsom-icon jinsom-xiangji"></i>',
'jinsom-fabu1'           => '<i class="jinsom-icon jinsom-fabu1"></i>',
'jinsom-fabu12'  => '<i class="jinsom-icon jinsom-fabu12"></i>',
'jinsom-fabu3'           => '<i class="jinsom-icon jinsom-fabu3"></i>',
'jinsom-fabu2'           => '<i class="jinsom-icon jinsom-fabu2"></i>',
'jinsom-fabiao'           => '<i class="jinsom-icon jinsom-fabiao"></i>',
'jinsom-fabuxuqiu'           => '<i class="jinsom-icon jinsom-fabuxuqiu"></i>',
'jinsom-fabu5'           => '<i class="jinsom-icon jinsom-fabu5"></i>',
'jinsom-ziyuan'           => '<i class="jinsom-icon jinsom-ziyuan"></i>',
'jinsom-fabuzhong'           => '<i class="jinsom-icon jinsom-fabuzhong"></i>',
'jinsom-fabuwoxiu'           => '<i class="jinsom-icon jinsom-fabuwoxiu"></i>',
'jinsom-comiisfashuoshuo'           => '<i class="jinsom-icon jinsom-comiisfashuoshuo"></i>',
'jinsom-dongtai'           => '<i class="jinsom-icon jinsom-dongtai"></i>',
'jinsom-dongtai1'           => '<i class="jinsom-icon jinsom-dongtai1"></i>',
'jinsom-fabu4'           => '<i class="jinsom-icon jinsom-fabu4"></i>',
'jinsom-fabiao2'           => '<i class="jinsom-icon jinsom-fabiao2"></i>',
'jinsom-fabu7'           => '<i class="jinsom-icon jinsom-fabu7"></i>',
'jinsom-fabu8'           => '<i class="jinsom-icon jinsom-fabu8"></i>',
'jinsom-fabu6'           => '<i class="jinsom-icon jinsom-fabu6"></i>',
'jinsom-fblogo'           => '<i class="jinsom-icon jinsom-fblogo"></i>',
),
'default'       =>'jinsom-yuanquan',
),


array(
'id' => 'hide_publish',
'type' => 'switcher',
'title' => '隐藏发布按钮',
'desc' => '<font style="color:#f00;">如果不想在底部显示按钮直接这里关闭即可，但不要删除添加的选项</font>',
'dependency' => array('jinsom_mobile_tab_type','==','publish'),
'default' => false,
),

array(
'id'         => 'publish_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'      => '发布弹层头部自定义区',
'dependency' => array('jinsom_mobile_tab_type','==','publish'),
'subtitle'       => '就是移动端点击发布弹出来的界面，支持html和短代码',
'default'  =>'<p>Hello world</p>',
),


array(
'id'                 => 'jinsom_mobile_tab_custom_type',
'type'               => 'radio',
'title'              => '选择数据展示类型',
'dependency' => array('jinsom_mobile_tab_type','==','custom'),
'inline'   => true,
'default'       =>'html',
'options'            => array(
'html'              => '自定义内容',
'link'         => '打开指定页面',
'require'         => '引入指定页面',
),
),


array(
'id' => 'jinsom_mobile_tab_custom_html',
'type'  => 'code_editor',
'dependency' => array('jinsom_mobile_tab_type|jinsom_mobile_tab_custom_type','==|==','custom|html'),
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '自定义内容',
'subtitle' => '支持html和短代码',
),

array(
'id'         => 'jinsom_mobile_tab_custom_require',
'type'       => 'text',
'title'      => '引入页面的路径',
'desc'      => '<font style="color:#f00;">此地址务必修改为自己的页面所在的正确地址</font>',
'default'      => '[link type="require_home"]/page/xxx.php',
'dependency' => array('jinsom_mobile_tab_type|jinsom_mobile_tab_custom_type','==|==','custom|require'),
'subtitle' =>'支持短代码，[link type="require_home"]是获取网站根目录，<a href="#" target="_blank" style="color:#f00;">如果你不懂请点击《查看教程》</a>'
),

array(
'id'         => 'jinsom_mobile_tab_custom_link',
'type'       => 'text',
'title'      => '页面的链接',
'default'      => 'javascript:',
'desc' => '<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">如果不懂请点击这里查看教程</a>',
'dependency' => array('jinsom_mobile_tab_type|jinsom_mobile_tab_custom_type','==|==','custom|link'),
'subtitle' =>'只能通过js打开页面</a>'
),



)
),




)
));


//移动端设置-左右侧栏
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_mobile',
'title'       => '<span>左右侧栏</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '
左侧栏打开方式：data-panel="left" class="open-panel"  js打开：myApp.openPanel("left");<br>
右侧栏打开方式：data-panel="right" class="open-panel"  js打开：myApp.openPanel("right");
',
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '如果左右侧栏使用了模块，则以模块的功能为准',
),

array(
'id' => 'jinsom_mobile_left_panel_type',
'type' => 'select',
'title' => '移动端左侧栏展示类型',
'subtitle' => '如果不知道有什么区别，可以随便选择测试就知道了',
'options'      => array(
'reveal' => '推叠式',
'cover' => '覆盖式',
),
),

array(
'id' => 'jinsom_mobile_right_panel_type',
'type' => 'select',
'title' => '移动端右侧栏展示类型',
'options'      => array(
'reveal' => '推叠式',
'cover' => '覆盖式',
),
),

array(
'id'=>'jinsom_mobile_left_panel_html',
'type'  => 'code_editor',
'default'  => 'hello LightSNS！',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '移动端左侧栏自定义区域',
'subtitle'=> '支持html和短代码，一般放通知或重要的提示。</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),

array(
'id'=>'jinsom_mobile_right_panel_html',
'type'  => 'code_editor',
'default'  => 'hello LightSNS！',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '移动端右侧栏自定义区域',
'subtitle'=> '支持html和短代码，一般放通知或重要的提示。</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),



)
));

//移动端设置-右侧悬浮
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_mobile',
'title'       => '<span>右侧悬浮</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'id' => 'jinsom_mobile_right_bar_add',
'type' => 'group',
'title' => '移动端右侧悬浮按钮添加',
'max'      => 10,
'button_title' => '添加',
'fields' => array(

array(
'id' => 'remark',
'type' => 'text',
'title' => '备注',
'subtitle' => '不对外展示',
),

array(
'id' => 'jinsom_mobile_right_bar_type',
'type' => 'select',
'title' => '按钮功能',
'options'      => array(
'publish' => '发布',
'top' => '返回顶部',
'reload' => '刷新',
'search' => '搜索',
'close' => '关闭/打开悬浮按钮',
'left' => '打开左侧栏',
'right' => '打开右侧栏',
'custom' => '自定义链接',
'html' => '自定义代码',
),
),

array(
'id' => 'jinsom_mobile_right_bar_type_custom',
'type' => 'text',
'title' => '自定义链接',
'subtitle' => '支持短代码',
'dependency' => array('jinsom_mobile_right_bar_type','==','custom'),
),

array(
'id' => 'jinsom_mobile_right_bar_type_html',
'type' => 'textarea',
'title' => '自定义html代码',
'subtitle' => '支持短代码',
'dependency' => array('jinsom_mobile_right_bar_type','==','html'),
'default'=>'<li class="custom_html"><a href="#" class="link"><i class="jinsom-icon jinsom-yingyongkuai"></i></a></li>'
),

array(
'id' => 'icon',
'type' => 'textarea',
'title' => 'Tab图标',
'placeholder'=>'留空则使用程序自带的图标',
'subtitle' => '可使用程序内置图标或自定义图标，<a href="https://q.jinsom.cn/iconfont" target="_blank" style="color:#f00;">《内置图标参考》</a>',
'dependency' => array('jinsom_mobile_right_bar_type','!=','html'),
),


)
),


)
));


//移动端设置-首页
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_mobile',
'title'       => '<span>SNS首页</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '【注意】这里设置的是移动端首页，如果想设置电脑端首页请点击这里 <a href="#tab=27"><font style="color:#f00;margin-left:10px;">快速传送门</font></a>',
),

array(
'id' => 'jinsom_mobile_home_header_name',
'type' => 'text',
'title' => '头部名称',
'desc' => '留空则不显示，支持输入html代码，比如图片logo，注意：双引号替换为单引号。',
'subtitle' => '<font style="color:#f00;">注意：这个头部名称仅仅针对内页引用的SNS首页</font>',
'default' => '首页',
),


array(
'id' => 'jinsom_mobile_sns_slider_add',
'type' => 'group',
'title' => '移动端首页页面幻灯片',
'subtitle' => '为了美观，建议最少添加三个幻灯片，如果幻灯片要全屏可以直接css调即可，不会可以直接群里问人。',
'button_title' => '添加幻灯片',
'fields' => array(

array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'subtitle'=>'支持短代码获取对应类型的内容地址、论坛地址、话题地址等等。短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
'desc'=>'如果填写外链地址则要关掉app形式打开',
'placeholder' => '[post_link id=123]'
),


array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '图片地址',
'desc'=>'关于尺寸，自己看着弄吧，因为是跟着手机屏幕大小去改变的。',
'placeholder' => 'http://'
),

array(
'id'      => 'desc',
'type'    => 'textarea',
'title'   => '描述',
),

array(
'id' => 'jinsom_mobile_sns_slider_add_app',
'type' => 'switcher',
'default' => true,
'title' => 'app形式打开',
'subtitle'  => '开启之后则以app的形式打开站内链接，如果需要链接到外部地址则关掉这个开关。',
),

array(
'id' => 'target',
'type' => 'switcher',
'default' => false,
'title' => '新窗口打开',
'dependency' => array('jinsom_mobile_sns_slider_add_app','==','false'),
),

)
), 

array(
'id' => 'jinsom_mobile_sns_slider_autoplay',
'type' => 'switcher',
'default' => false,
'title' => '移动端首页幻灯片自动播放',
),

array(
'id' => 'jinsom_mobile_sns_slider_height',
'type'       => 'spinner',
'unit'       => 'vw',
'title' => '移动端首页页面幻灯片高度',
'default' =>35,
'subtitle' => '手机屏幕宽度等于100vw，1vw相当于1/100屏幕宽度，vw是根据屏幕宽度来变化的。',
),


array(
'id' => 'jinsom_mobile_sns_cell_menu_add',
'type' => 'group',
'title' => '移动端首页头部格子菜单',
'subtitle' => '如果不添加则默认关闭，默认一行4个，你可以通过css调整为3个或者5个等等',
'button_title' => '添加格子菜单',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '菜单名称',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '菜单图标',
'subtitle'=>'建议80px*80px的图标即可',
'placeholder' => 'https://'
),


array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'subtitle'=>'支持短代码获取对应类型的内容地址、论坛地址、话题地址等等。短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
'desc'=>'如果填写外链地址则要关掉app形式打开',
'placeholder' => '[topic_link id=123]'
),

array(
'id' => 'login',
'type' => 'switcher',
'default' => false,
'title' => '登录才显示',
'subtitle'  => '开启之后，登录的用户才能看见此菜单',
),

array(
'id' => 'jinsom_mobile_sns_cell_menu_add_app',
'type' => 'switcher',
'default' => true,
'title' => 'app形式打开',
'subtitle'  => '开启之后则以app的形式打开站内链接，如果需要链接到外部地址则关掉这个开关。',
),


)
) ,

array(
'id'=>'jinsom_mobild_home_header_menu_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '首页菜单下面的自定义内容区域',
'subtitle'=> '支持html和短代码，一般放通知或重要的提示。</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),

array(
'id'=>'jinsom_mobile_sns_slider_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '首页幻灯片下面的自定义内容区域',
'subtitle'=> '支持html和短代码，一般放通知或重要的提示。</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),

array(
'id'=>'jinsom_mobile_cell_menu_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '首页格子菜单下面的自定义内容区域',
'subtitle'=> '支持html和短代码，一般放通知或重要的提示。</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),

)
));


//移动端设置-消息页面
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_mobile',
'title'       => '<span>消息页面</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'id'=>'jinsom_mobile_notice_header_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '消息页面头部自定义内容区域',
'subtitle'=> '支持html和短代码，一般放通知或重要的提示。</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),


)
));


//移动端设置-发现页面
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_mobile',
'title'       => '<span>发现页面</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(


array(
'id' => 'jinsom_mobile_find_add',
'type' => 'group',
'title' => '发现页面模块添加',
'button_title' => '添加',
'fields' => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => '模块标题',
'subtitle'   => '如果留空则不显示头部',
),

array(
'id'     => 'jinsom_mobile_find_add_type',
'type'   => 'select',
'title'  => '模块展示类型',
'options'=> array(
'slider'=>'展示幻灯片',
'menu'=>'展示格子菜单',
'bbs'=>'展示论坛',
'topic'=>'展示话题',
'user'=>'展示用户',
'html'=>'展示自定义内容',
),
),

array(
'id' => 'slider_add',
'type' => 'group',
'title' => '幻灯片',
'dependency' => array('jinsom_mobile_find_add_type','==','slider'),
'button_title' => '添加幻灯片',
'fields' => array(

array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'subtitle'=>'支持短代码获取对应类型的内容地址、论坛地址、话题地址等等。短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
'desc'=>'如果填写外链地址则要关掉app形式打开',
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
'id' => 'app',
'type' => 'switcher',
'default' => true,
'title' => 'app形式打开',
'desc'  => '如果是短代码链接请打开这个开关，如果需要跳转到别的网站则关掉这个开关。',
),


)
), 

array(
'id' => 'slider_auto',
'type' => 'switcher',
'default' => false,
'dependency' => array('jinsom_mobile_find_add_type','==','slider'),
'title' => '自动切换',
),

array(
'id' => 'slider_height',
'type'       => 'spinner',
'unit'       => 'vw',
'title' => '幻灯片高度',
'default' =>35,
'dependency' => array('jinsom_mobile_find_add_type','==','slider'),
'subtitle' => '手机屏幕宽度等于100vw，1vw相当于1/100屏幕宽度',
),




array(
'id' => 'menu_add',
'type' => 'group',
'title' => '格子菜单',
'button_title' => '添加菜单',
'dependency' => array('jinsom_mobile_find_add_type','==','menu'),
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '菜单名称',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '菜单图标',
'placeholder' => 'https://'
),


array(
'id' => 'link',
'type' => 'text',
'title' => '菜单链接',
'subtitle'=>'支持短代码，短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
'placeholder' => 'https://'
),

array(
'id' => 'login',
'type' => 'switcher',
'default' => false,
'title' => '登录才显示',
'subtitle'  => '开启之后，登录的用户才能看见此菜单',
),

array(
'id' => 'app',
'type' => 'switcher',
'default' => true,
'title' => 'app形式打开',
'desc'  => '如果是短代码链接请打开这个开关，如果需要跳转到别的网站则关掉这个开关。',
),


)
),

array(
'id'     => 'find_bbs_type',
'type'   => 'select',
'title'  => '展示的论坛类型',
'subtitle'  => '推荐的论坛在论坛设置里面设置',
'dependency' => array('jinsom_mobile_find_add_type','==','bbs'),
'options'=> array(
'commend'=> '推荐的论坛',
'custom'=> '手动输入',
),
'default'=>'commend',
),

array(
'id'     => 'custom_bbs_id',
'type'   => 'textarea',
'title'  => '论坛ID',
'subtitle'  => '多个请ID请使用英文逗号隔开',
'dependency' => array('find_bbs_type|jinsom_mobile_find_add_type','==|==','custom|bbs'),
),

array(
'id'     => 'find_topic_type',
'type'   => 'select',
'title'  => '展示的话题类型',
'dependency' => array('jinsom_mobile_find_add_type','==','topic'),
'options'=> array(
'views'=> '浏览量最多的话题',
'count'=> '内容最多的话题',
'custom'=> '手动输入',
),
'default'=>'views',
),

array(
'id'     => 'custom_topic_id',
'type'   => 'textarea',
'title'  => '话题ID',
'subtitle'  => '多个请ID请使用英文逗号隔开',
'dependency' => array('find_topic_type|jinsom_mobile_find_add_type','==|==','custom|topic'),
),


array(
'id'     => 'find_user_type',
'type'   => 'select',
'title'  => '展示的用户类型',
'dependency' => array('jinsom_mobile_find_add_type','==','user'),
'options'=> array(
'online'=> '活跃用户',
'new'=> '最新注册',
'vip'=> '随机VIP用户',
'verify'=> '随机认证用户',
'rand'=> '随机所有用户',
'custom'=> '手动输入',
),
'default'=>'online',
),

array(
'id'     => 'custom_user_id',
'type'   => 'textarea',
'title'  => '用户ID',
'subtitle'  => '多个请ID请使用英文逗号隔开',
'dependency' => array('find_user_type|jinsom_mobile_find_add_type','==|==','custom|user'),
),

array(
'id'     => 'number',
'type'       => 'spinner',
'unit'       => '个',
'title'  => '展示数量',
'default'  =>10,
'dependency' => array('jinsom_mobile_find_add_type','any','bbs,topic,user'),
),

array(
'id'     => 'style',
'type'   => 'select',
'title'  => '显示样式',
'options'=> array(
'a'=> '格子',
'b'=> '左右并行',
'c'=> '列表(带关注按钮)',
'd'=> '左右滑动(带关注按钮)',
),
'default'=>'a',
'dependency' => array('jinsom_mobile_find_add_type','any','bbs,user'),
),

array(
'id'     => 'more_text',
'type'   => 'text',
'title'  => '更多按钮名称',
'subtitle'  => '留空则不显示更多按钮',
'default'  => '更多',
'dependency' => array('jinsom_mobile_find_add_type','not-any','slider,html'),
),

array(
'id'     => 'more_link',
'type'   => 'text',
'title'  => '更多按钮链接',
'desc'=>'支持短代码，短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
'dependency' => array('jinsom_mobile_find_add_type','not-any','slider,html'),
),

array(
'id'=>'html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '自定义html代码',
'default'=> '<div class="jinsom-find-box html">这里放你要填写的东西</div>',
'dependency' => array('jinsom_mobile_find_add_type','==','html'),
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),


)
), 




)
));


//页面模块-移动端我的页面
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_mobile',
'title'       => '<span>我的页面</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '注意：底部Tab页的消息和“我的”里面的消息只能同时存在一个',
),

array(
'id'     => 'jinsom_mobile_mine_style',
'type'   => 'radio',
'title'  => '显示风格',
'options'=> array(
'list'=>'列表布局(默认)',
'cell'=>'格子布局',
),
'default'=>'list'
),


array(
'id' => 'jinsom_mobile_mine_add',
'type' => 'group',
'title' => '添加模块',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'marks',
'type' => 'text',
'title' => '模块名称',
'placeholder' => '留空则不显示',
'subtitle' => '选择列表布局的时候，这个选项不对外展示，支持短代码和html代码',
),

array(
'id'     => 'jinsom_mobile_mine_type',
'type'   => 'select',
'title'  => '模块展示类型',
'options'=> array(
'list'=>'菜单列表',
'profile'=>'用户资料信息',
'html'=>'自定义代码',
),
),


array(
'id' => 'jinsom_mobile_mine_menu_list_add',
'type' => 'group',
'dependency' => array('jinsom_mobile_mine_type','==','list'),
'title' => '添加菜单',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '名称',
'dependency' => array('jinsom_mobile_mine_menu_list_type','!=','info'),
),

array(
'id'     => 'jinsom_mobile_mine_menu_list_type',
'type'   => 'select',
'title'  => '菜单类型',
'options'=> array(
'wallet' => '钱包',
'vip' => '会员',
'sign' => '签到',
'task'   => '任务',
'follower' => '粉丝',
'following' => '关注',
'visitor' => '访客',
'gift' => '礼物',
'notice' => '消息',
'majia' => '马甲',
'invite'   => '推广',
'collect'   => '收藏',
'blacklist'   => '黑名单',
'history-single'   => '历史浏览',
'bbs' => '关注的论坛',
'topic' => '关注的话题',
'profile' => '资料设置',
'key-recharge' => '卡密兑换',
'custom'   => '自定义菜单',
),
),


array(
'id' => 'icon',
'type' => 'textarea',
'title' => '菜单图标',
'placeholder'=>'留空则使用程序自带的图标',
'subtitle' => '可使用程序内置图标或自定义图标，<a href="https://q.jinsom.cn/iconfont" target="_blank" style="color:#f00;">《内置图标参考》</a>',
),

array(
'id' => 'icon_color',
'type' => 'color',
'title' => '图标颜色',
'default'=>'#00b25e',
),

array(
'id' => 'link',
'type' => 'text',
'title' => '自定义链接',
'desc' => '<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">如果不懂请点击这里查看教程</a>',
'subtitle' => '如果是打开本站页面请使用短代码，如果是外链请使用js形式打开',
'dependency' => array('jinsom_mobile_mine_menu_list_type','==','custom'),
),

array(
'id' => 'right_html',
'type' => 'textarea',
'title' => '右侧自定义区域',
'subtitle' => '支持短代码/html代码',
'dependency' => array('jinsom_mobile_mine_menu_list_type','==','custom'),
),





)
),


array(
'id'=>'jinsom_mobile_mine_type_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'dependency' => array('jinsom_mobile_mine_type','==','html'),
'title'=> '自定义代码',
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),


)
),





)
));




//菜单设置
LightSNS::createSection( $prefix, array(
'title'       => '<span>菜单设置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '【注意】最顶部的导航菜单是在wordpress后台-外观-菜单 里面设置 <a href="/wp-admin/nav-menus.php" target="_blank"><font style="color:#f00;margin-left:10px;">快速传送门</font></a>',
),


array(
'id' => 'jinsom_sns_home_menu_add',
'type' => 'group',
'title' => 'SNS首页菜单',
'subtitle' => '同步移动端设置',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '菜单名称',
),

array(
'id' => 'jinsom_sns_home_menu_type',
'type' => 'select',
'title' => '菜单类型',
'options'      => array(
'all' => '全部',
'words' => '动态',
'music' => '音乐',
'single' => '文章',
'video' => '视频',
'bbs' => '帖子',
'answer' => '问答帖子',
'redbag' => '红包',
'reprint' => '转发',
'commend' => '推荐的内容',
'city' => '同城的内容',
'follow-user' => '关注的用户发布的内容',
'follow-bbs' => '关注的论坛的内容',
'follow-topic' => '关注的话题的内容',
'pay' => '付费可见',
'password' => '密码可见',
'vip' => 'VIP可见',
'login' => '登录可见',
'hot' => '热门的内容',
'rand' => '随机的内容',
'custom-bbs' => '自定义论坛',
'custom-topic' => '自定义话题',
'custom-link' => '打开外链',
'custom-html' => '自定义html内容',
'require' => '引入指定文件',
),
),

array(
'id' => 'time',
'type' => 'select',
'title' => '时间',
'dependency' => array('jinsom_sns_home_menu_type','any','hot,rand'),
'options'  =>array(
'week' => '一周内',
'half_month' => '半月内',
'month' => '一月内',
'year' => '一年内',
'all' => '所有时间的内容',
),
),

array(
'id' => 'bbs_id',
'type' => 'text',
'title' => '论坛ID',
'dependency' => array('jinsom_sns_home_menu_type','==','custom-bbs'),
'subtitle' => '多个论坛ID请用英文逗号隔开',
),

array(
'id' => 'topic_id',
'type' => 'text',
'title' => '话题ID',
'dependency' => array('jinsom_sns_home_menu_type','==','custom-topic'),
'subtitle' => '多个话题ID请用英文逗号隔开',
),

array(
'id' => 'link',
'type' => 'text',
'title' => '链接',
'dependency' => array('jinsom_sns_home_menu_type','==','custom-link'),
'subtitle' => '如果需要打开外链，请使用javascript:方式打开',
),

array(
'id' => 'require',
'type' => 'text',
'title' => '引入文件的路径',
'dependency' => array('jinsom_sns_home_menu_type','==','require'),
'placeholder'=>'[link type=require_home]/xxx.php',
'subtitle' => '一般用于开发定制，请使用短代码：[link type=require_home]',
),

array(
'id' => 'html',
'type' => 'textarea',
'title' => '移动端自定义内容',
'dependency' => array('jinsom_sns_home_menu_type','not-any','custom-link,custom-html,require'),
'subtitle' => '第一个菜单不显示，菜单底下自定义区域，支持html/短代码',
),

array(
'id'=>'custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'dependency' => array('jinsom_sns_home_menu_type','==','custom-html'),
'title'=> '自定义html内容',
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),


array(
'id' => 'waterfall',
'type' => 'switcher',
'default' => false,
'title' => '瀑布流展示',
'subtitle' => '目前只在移动端生效',
// 'dependency' => array('jinsom_sns_home_menu_type','any','redbag,words,video,single,bbs,custom-bbs,follow-bbs'),
),


array(
'id' => 'in_pc',
'type' => 'switcher',
'default' => false,
'title' => '只在电脑端展示',
'dependency' => array('in_mobile','==','false'),
),

array(
'id' => 'in_mobile',
'type' => 'switcher',
'default' => false,
'title' => '只在移动端展示',
'dependency' => array('in_pc','==','false'),
),

)
),


array(
'id' => 'jinsom_member_menu_add',
'type' => 'group',
'title' => '个人主页菜单',
'subtitle' => '同步移动端设置',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '菜单名称',
),

array(
'id' => 'jinsom_member_menu_type',
'type' => 'select',
'title' => '菜单类型',
'options'      => array(
'all' => '全部',
'words' => '动态',
'music' => '音乐',
'single' => '文章',
'video' => '视频',
'bbs' => '帖子',
'answer' => '问答帖子',
'redbag' => '红包',
'reprint' => '转发',
'commend' => '推荐的内容',
'like' => '已喜欢的内容',
'buy' => '已购买的内容',
'pay' => '付费可见',
'password' => '密码可见',
'vip' => 'VIP可见',
'login' => '登录可见',
'follow-page' => '关注类汇集页面',
'custom-bbs' => '自定义论坛',
'custom-topic' => '自定义话题',
'profile' => '资料设置',
'custom-html' => '自定义html内容',
'require' => '引入指定文件',
),
),


array(
'id' => 'bbs_id',
'type' => 'text',
'title' => '论坛ID',
'dependency' => array('jinsom_member_menu_type','==','custom-bbs'),
'subtitle' => '多个论坛ID请用英文逗号隔开',
),

array(
'id' => 'topic_id',
'type' => 'text',
'title' => '话题ID',
'dependency' => array('jinsom_member_menu_type','==','custom-topic'),
'subtitle' => '多个话题ID请用英文逗号隔开',
),

array(
'id' => 'require',
'type' => 'text',
'title' => '引入文件的路径',
'dependency' => array('jinsom_member_menu_type','==','require'),
'placeholder'=>'[link type=require_home]/xxx.php',
'subtitle' => '一般用于开发定制，请使用短代码：[link type=require_home]',
),

array(
'id'=>'custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'dependency' => array('jinsom_member_menu_type','==','custom-html'),
'title'=> '自定义html内容',
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),


array(
'id' => 'in_pc',
'type' => 'switcher',
'default' => false,
'title' => '只在电脑端展示',
'dependency' => array('in_mobile','==','false'),
),

array(
'id' => 'in_mobile',
'type' => 'switcher',
'default' => false,
'title' => '只在移动端展示',
'dependency' => array('in_pc','==','false'),
),

)
),




array(
'id'             => 'jinsom_search_menu_a',
'type'           => 'sorter',
'title'          => '【搜索页面】-菜单设置',
'default'        => array(
'enabled'      => array(
'user' => '用户',
'bbs' => '帖子',
'single' => '文章',
'video' => '视频',
'music' => '音乐',
'words' => '动态',
'topic' => '话题',
'forum' => '论坛',
),
),
),



array(
'id'             => 'jinsom_topic_menu',
'type'           => 'sorter',
'title'          => '【话题详情页面】-菜单设置',
'default'        => array(
'enabled'      => array(
'all' => '全部',
'commend' => '推荐',
'words' => '动态',
'music' => '音乐',
'video' => '视频',
'single' => '文章',
'bbs' => '帖子',
'pay' => '付费',
),
),
),





array(
'id'             => 'jinsom_collect_menu',
'type'           => 'sorter',
'title'          => '【收藏页面】-菜单设置',
'subtitle'          => '收藏菜单',
'default'        => array(
'enabled'      => array(
'all' => '全部',
'words' => '动态',
'music' => '音乐',
'video' => '视频',
'single' => '文章',
'bbs' => '帖子',
'redbag' => '红包',
'goods' => '商品',
),
),
),


)
));

//发表模块
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_publish',
'title'  => '<span>内容模块</span>',
'icon'   => 'fa fa-pencil-square-o',
));


//发布模块-基本设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>基本设置</span>',
'icon'        => 'fa fa-cog',
'fields'      => array(

array(
'id' => 'jinsom_publish_add',
'type' => 'group',
'title' => '发布选项添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '选项名称',
),

array(
'id' => 'jinsom_publish_add_type',
'type' => 'select',
'title' => '选项类型',
'options'      => array(
'words' => '动态',
'music' => '音乐',
'video' => '视频',
'single' => '文章',
'redbag' => '红包',
'secret' => '树洞/匿名',
'follow-bbs' => '关注的论坛',
'commend-bbs' => '推荐的论坛',
'bbs' => '指定的论坛',
'custom' => '自定义发布类型',
),
),

array(
'id' => 'bbs_id',
'type' => 'number',
'title' => '论坛ID',
'dependency' => array('jinsom_publish_add_type','==','bbs'),
),

array(
'id'         => 'custom',
'type'       => 'text',
'title'      => '自定义发布链接',
'dependency' => array('jinsom_publish_add_type','==','custom'),
'subtitle' =>'支持短代码，https://或者javascript:xxxxx()'
),

array(
'id' => 'icon',
'type' => 'textarea',
'title' => '图标代码',
'placeholder'=>'留空则使用程序自带的图标',
'subtitle' => '可使用程序内置图标或自定义图标，<a href="https://q.jinsom.cn/iconfont" target="_blank" style="color:#f00;">《内置图标参考》</a>',
),

array(
'id' => 'color',
'type' => 'color',
'title' => '显示颜色',
'default'=>'#f48924'
),

array(
'id' => 'in_pc',
'type' => 'switcher',
'default' => false,
'title' => '只在电脑端展示',
'dependency' => array('in_mobile','==','false'),
),

array(
'id' => 'in_mobile',
'type' => 'switcher',
'default' => false,
'title' => '只在移动端展示',
'dependency' => array('in_pc','==','false'),
),


)
),


array(
'id' => 'jinsom_publish_application_add',
'type' => 'group',
'title' => '发表应用选项添加',
'subtitle' => '有其他灵感和更好的玩法可以跟我反馈',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '应用名称',
),

array(
'id' => 'type',
'type' => 'select',
'title' => '应用类型',
'options'      => array(
'link' => '链接',
'goods' => '商品',
'challenge' => '我的挑战',
'pet' => '我的宠物',
),
),

array(
'id' => 'power',
'type' => 'select',
'title' => '使用权限',
'options'      => array(
'all' => '所有人',
'vip' => 'VIP用户',
'admin' => '管理团队',
),
),

array(
'id' => 'icon',
'type' => 'textarea',
'title' => '图标代码',
'placeholder'=>'留空则使用程序自带的图标',
'subtitle' => '可使用程序内置图标或自定义图标，<a href="https://q.jinsom.cn/iconfont" target="_blank" style="color:#f00;">《内置图标参考》</a>',
),

array(
'id' => 'color',
'type' => 'color',
'title' => '图标颜色',
'default'=>'#333'
),

)
),


array(
'id'=>'jinsom_delete_time_max',
'type'=>'spinner',
'unit'=>'小时',
'default'=> 24,
'title'=> '内容删除时间',
'subtitle'=> '用户发表内容之后，超过这个时间后不允许进行删除，管理团队(管理员、网站管理、大版主)不限制',
),


array(
'id'=>'jinsom_edit_time_max',
'type'=>'spinner',
'unit'=>'小时',
'default'=> 24,
'title'=> '内容编辑时间',
'subtitle'=> '用户发表内容之后，超过这个时间后不允许进行编辑，管理团队(管理员、网站管理、大版主)不限制',
),



array(
'id'=>'jinsom_hide_words_max',
'type'=>'spinner',
'unit'=>'字',
'default'=> 1000,
'title'=> '动态隐藏内容字数上限',
'desc'=> '【注意】：仅仅是动态的隐藏内容字数最大上限,论坛帖子隐藏内容上限请前往-主题设置-论坛设置',
),



array(
'id'=>'jinsom_publish_posts_cnt_fold_number',
'type'=>'spinner',
'unit'=>'字',
'default'=> 200,
'title'=> '电脑端内容折叠字数',
'subtitle'=> '动态、音乐、视频的内容超过指定字数会自动折叠',
),

array(
'id'         => 'jinsom_publish_posts_cnt_fold_height',
'type'=>'spinner',
'unit'=>'px',
'default'    => 300,
'title'      => '电脑端内容折叠高度',
'subtitle'       => '动态、音乐、视频的内容折叠高度，默认300px',
),


array(
'type' => 'notice',
'style' => 'success',
'content' => '为什么电脑端和移动端分开设置呢？因为屏幕大小原因，如果都按照电脑端的，会显示的不协调。',
),

array(
'id'=>'jinsom_mobile_content_more_fold_number',
'type'=>'spinner',
'unit'=>'字',
'default'=> 200,
'title'=> '移动端内容折叠字数',
'subtitle'=> '动态、音乐、视频的内容超过指定字数进行折叠',
),

array(
'id'         => 'jinsom_mobile_content_more_fold_height',
'type'=>'spinner',
'unit'=>'vw',
'default'    => 50,
'title'      => '移动端内容折叠高度',
'desc'       => '动态、音乐、视频的内容折叠高度，默认50vw。（100vw就相当于手机屏幕的宽度）',
),



array(
'id'         => 'jinsom_publish_price_mini',
'type'=>'spinner',
'unit'=>'金币',
'default'    => 10,
'title'      => '发布售价最小值',
'desc'       => '用户发表动态、音乐、视频、文章时，售价的最小值',
),


array(
'id'         => 'jinsom_publish_price_max',
'type'=>'spinner',
'unit'=>'金币',
'default'    => 1000,
'title'      => '发布售价最大值',
'desc'       => '用户发表动态、音乐、视频、文章时，售价的最大值',
),



array(
'id'=>'jinsom_publish_mobile_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '【移动端】发布页面底部自定义区域',
'subtitle'=> '仅针对动态、音乐、视频、文章，帖子发布页面是每个论坛单独设置的，支持html和短代码',
),



)
));


//发布模块-动态设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>动态设置</span>',
'icon'        => 'fa fa-commenting',
'fields'      => array(

array(
'id'      => 'jinsom_publish_words_cover',
'type'    => 'upload',
'title'   => '动态默认封面图',
'subtitle'      => '用于瀑布流展示',
),

array(
'id' => 'jinsom_publish_words_pending',
'type' => 'switcher',
'default' => false,
'title' => '开启审核',
'subtitle' => '开启之后，用户发表的【动态】需要通过审核才展示出来。',
),

array(
'id'         => 'jinsom_publish_posts_cnt_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 250,
'title'      => '内容字数上限',
'subtitle'       => '发表动态的时候，内容最大允许发表多少字',
),

array(
'id'         => 'jinsom_publish_posts_title_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 60,
'title'      => '标题字数上限',
'subtitle'       => '发表动态的时候，标题最大允许发表多少字',
),


array(
'id'         => 'jinsom_publish_words_add_topic_max',
'type'=>'spinner',
'unit'=>'个',
'default'    => 3,
'title'      => '插入话题数量上限',
'subtitle'       => '发表动态的时候，允许插入话题数量上限',
),


array(
'id'         => 'jinsom_publish_words_add_images_max',
'type'=>'spinner',
'unit'=>'张',
'default'    => 12,
'title'      => '插入图片数量上限',
'subtitle'       => '发表动态的时候，允许插入图片数量上限',
),

array(
'id' => 'jinsom_publish_words_topic_on_off',
'type' => 'switcher',
'default' => false,
'title' => '强制添加话题',
),

array(
'id'                 => 'jinsom_publish_words_power',
'type'               => 'radio',
'title'              => '发布权限',
'subtitle'       => '动态发布需要的权限，满足权限的用户才可以进行发布',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_publish_words_exp',
'type'=>'spinner',
'unit'=>'经验值',
'default'    => 10,
'title'      => '发布权限_指定经验值',
'dependency' => array('jinsom_publish_words_power','==','exp') ,
'subtitle'       => '用户需要达到这个经验值才可以发表动态',
),

array(
'id'         => 'jinsom_publish_words_honor_arr',
'type'=>'textarea',
'title'      => '发布权限_指定头衔',
'dependency' => array('jinsom_publish_words_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_publish_words_verify_arr',
'type'=>'textarea',
'title'      => '发布权限_指定认证类型',
'dependency' => array('jinsom_publish_words_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),



array(
'id'             => 'jinsom_words_power_sorter_a',
'type'           => 'sorter',
'title'          => '权限模块',
'default'        => array(
'enabled'      => array(
'pay'   => '付费可见',
'password' => '密码可见',
'private' => '私密',
'vip' => 'VIP可见',
'login' => '登录可见',
'comment' => '回复可见',
'verify' => '认证可见',
'follow' => '粉丝可见',
),
),
'subtitle'      => '前台发布框，用户发布可以使用的权限功能模块',
),



)
));


//发布模块-音乐设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>音乐设置</span>',
'icon'        => 'fa fa-music',
'fields'      => array(

array(
'id'      => 'jinsom_publish_music_cover',
'type'    => 'upload',
'title'   => '音乐默认封面图',
'subtitle'      => '用于瀑布流展示',
),

array(
'id' => 'jinsom_publish_music_pending',
'type' => 'switcher',
'default' => false,
'title' => '开启审核',
'subtitle' => '开启之后，用户发表的【音乐】需要通过审核才展示出来。',
),

array(
'id'         => 'jinsom_publish_music_cnt_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 250,
'title'      => '内容字数上限',
'subtitle'       => '发表音乐的时候，内容最大允许发表多少字',
),

array(
'id'         => 'jinsom_publish_music_title_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 60,
'title'      => '标题字数上限',
'subtitle'       => '发表音乐的时候，标题最大允许发表多少字',
),


array(
'id'         => 'jinsom_publish_music_add_topic_max',
'type'=>'spinner',
'unit'=>'个',
'default'    => 3,
'title'      => '插入话题数量上限',
'subtitle'       => '发表音乐的时候，允许插入话题数量上限',
),



array(
'id' => 'jinsom_publish_music_topic_on_off',
'type' => 'switcher',
'default' => false,
'title' => '强制添加话题',
),


array(
'id'                 => 'jinsom_publish_music_power',
'type'               => 'radio',
'title'              => '发布权限',
'subtitle'       => '音乐发布需要的权限，满足权限的用户才可以进行发布',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_publish_music_exp',
'type'=>'spinner',
'unit'=>'经验值',
'default'    => 10,
'title'      => '发布权限_指定经验值',
'dependency' => array('jinsom_publish_music_power','==','exp') ,
'subtitle'       => '用户需要达到这个经验值才可以发表',
),

array(
'id'         => 'jinsom_publish_music_honor_arr',
'type'=>'textarea',
'title'      => '发布权限_指定头衔',
'dependency' => array('jinsom_publish_music_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_publish_music_verify_arr',
'type'=>'textarea',
'title'      => '发布权限_指定认证类型',
'dependency' => array('jinsom_publish_music_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),

array(
'id'             => 'jinsom_music_power_sorter_a',
'type'           => 'sorter',
'title'          => '权限模块',
'default'        => array(
'enabled'      => array(
'pay'   => '付费可见',
'password' => '密码可见',
'private' => '私密',
'vip' => 'VIP可见',
'login' => '登录可见',
),
),
'subtitle'      => '前台发布框，用户发布可以使用的权限功能模块',
),



)
));


//发布模块-视频设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>视频设置</span>',
'icon'        => 'fa fa-play-circle',
'fields'      => array(

array(
'id' => 'jinsom_publish_video_pending',
'type' => 'switcher',
'default' => false,
'title' => '开启审核',
'subtitle' => '开启之后，用户发表的【视频】需要通过审核才展示出来。',
),



array(
'id'      => 'jinsom_publish_video_cover',
'type'    => 'upload',
'title'   => '视频默认封面图',
'desc'      => '如果用户发布视频没有上传封面图，则默认使用这个封面图',
),

array(
'id'         => 'jinsom_publish_video_cnt_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 250,
'title'      => '内容字数上限',
'subtitle'       => '发表视频的时候，内容最大允许发表多少字',
),

array(
'id'         => 'jinsom_publish_video_title_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 60,
'title'      => '标题字数上限',
'subtitle'       => '发表视频的时候，标题最大允许发表多少字',
),


array(
'id'         => 'jinsom_publish_video_add_topic_max',
'type'=>'spinner',
'unit'=>'个',
'default'    => 3,
'title'      => '插入话题数量上限',
'subtitle'       => '发表视频的时候，允许插入话题数量上限',
),



array(
'id' => 'jinsom_publish_video_topic_on_off',
'type' => 'switcher',
'default' => false,
'title' => '强制添加话题',
),

array(
'id'                 => 'jinsom_publish_video_power',
'type'               => 'radio',
'title'              => '发布权限',
'subtitle'       => '视频发布需要的权限，满足权限的用户才可以进行发布',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_publish_video_exp',
'type'=>'spinner',
'unit'=>'经验值',
'default'    => 10,
'title'      => '发布权限_指定经验值',
'dependency' => array('jinsom_publish_video_power','==','exp') ,
'subtitle'       => '用户需要达到这个经验值才可以发表',
),

array(
'id'         => 'jinsom_publish_video_honor_arr',
'type'=>'textarea',
'title'      => '发布权限_指定头衔',
'dependency' => array('jinsom_publish_video_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_publish_video_verify_arr',
'type'=>'textarea',
'title'      => '发布权限_指定认证类型',
'dependency' => array('jinsom_publish_video_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),

array(
'id'             => 'jinsom_video_power_sorter_a',
'type'           => 'sorter',
'title'          => '权限模块',
'default'        => array(
'enabled'      => array(
'pay'   => '付费可见',
'password' => '密码可见',
'private' => '私密',
'vip' => 'VIP可见',
'login' => '登录可见',
'comment' => '回复可见',
'verify' => '认证可见',
'follow' => '粉丝可见',
),
),
'subtitle'      => '前台发布框，用户发布可以使用的权限功能模块',
),

)
));


//发布模块-文章设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>文章设置</span>',
'icon'        => 'fa fa-file',
'fields'      => array(

array(
'id' => 'jinsom_publish_single_pending',
'type' => 'switcher',
'default' => false,
'title' => '开启审核',
'subtitle' => '开启之后，用户发表的【文章】需要通过审核才展示出来。',
),

array(
'id'      => 'jinsom_single_default_cover',
'type'    => 'upload',
'title'   => '文章默认封面图',
'subtitle' => '目前仅用于侧栏小工具调用文章的时候展示，长方形，建议尺寸：100px*63px',
),

array(
'id'         => 'jinsom_publish_single_cnt_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 250,
'title'      => '内容字数上限',
'subtitle'       => '发表文章的时候，内容最大允许发表多少字',
),

array(
'id'         => 'jinsom_publish_single_title_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 60,
'title'      => '标题字数上限',
'subtitle'       => '发表文章的时候，标题最大允许发表多少字',
),

array(
'id'         => 'jinsom_publish_single_excerp_max_words',
'type'=>'spinner',
'unit'=>'字',
'default'    => 130,
'title'      => '文章/帖子摘要字数',
'desc'       => '文章摘要显示的字数，【帖子的首页摘要也使用这个值】',
),

array(
'id'=>'jinsom_single_hide_words_max',
'type'=>'spinner',
'unit'=>'字',
'default'=> 10000,
'title'=> '文章隐藏内容字数上限',
'desc'=> '【注意】：仅仅是文章的隐藏内容字数最大上限,由于是富文本，代码也计入字数',
),


array(
'id'         => 'jinsom_publish_single_add_topic_max',
'type'=>'spinner',
'unit'=>'个',
'default'    => 10,
'title'      => '插入话题数量上限',
'subtitle'       => '发表文章的时候，允许插入话题数量上限',
),



array(
'id' => 'jinsom_publish_single_topic_on_off',
'type' => 'switcher',
'default' => false,
'title' => '强制添加话题',
),

array(
'id' => 'jinsom_publish_single_upload_file',
'type' => 'switcher',
'default' => false,
'title' => '插入附件功能（文件、视频、音乐）',
),

array(
'id'                 => 'jinsom_publish_single_power',
'type'               => 'radio',
'title'              => '发布权限',
'subtitle'       => '文章发布需要的权限，满足权限的用户才可以进行发布',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_publish_single_exp',
'type'=>'spinner',
'unit'=>'经验值',
'default'    => 10,
'title'      => '发布权限_指定经验值',
'dependency' => array('jinsom_publish_single_power','==','exp') ,
'subtitle'       => '用户需要达到这个经验值才可以发表',
),

array(
'id'         => 'jinsom_publish_single_honor_arr',
'type'=>'textarea',
'title'      => '发布权限_指定头衔',
'dependency' => array('jinsom_publish_single_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_publish_single_verify_arr',
'type'=>'textarea',
'title'      => '发布权限_指定认证类型',
'dependency' => array('jinsom_publish_single_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),

array(
'id'             => 'jinsom_single_power_sorter_a',
'type'           => 'sorter',
'title'          => '权限模块',
'default'        => array(
'enabled'      => array(
'pay'   => '付费可见',
'password' => '密码可见',
'private' => '私密',
'vip' => 'VIP可见',
'login' => '登录可见',
'comment' => '回复可见',
'verify' => '认证可见',
'follow' => '粉丝可见',
),
),
'subtitle'      => '前台发布框，用户发布可以使用的权限功能模块',
),



array(
'id' => 'jinsom_single_copyright_info',
'type' => 'textarea',
'title' => '电脑端文章版权内容',
'subtitle' =>'支持html和短代码，短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>'
),

array(
'id' => 'jinsom_mobile_single_copyright_info',
'type' => 'textarea',
'title' => '移动端文章版权内容',
'subtitle' =>'支持html，<font style="color:#f00;">【不支持短代码】</font>',
'default' =>'未经允许，禁止转载本站所有原创内容'
),


array(
'id'=>'jinsom_publish_single_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '文章发布页-头部自定义（电脑端）',
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),

array(
'id'=>'jinsom_single_content_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '文章详情页-头部自定义（电脑端）',
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),

array(
'id'=>'jinsom_single_content_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title'=> '文章详情页-底部自定义（电脑端）',
'subtitle'=> '支持html和短代码</br>短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>',
),


)
));


//发布模块-帖子设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>帖子设置</span>',
'icon'        => 'fa fa-sticky-note',
'fields'      => array(



array(
'id'      => 'jinsom_bbs_default_cover',
'type'    => 'upload',
'title'   => '帖子默认封面图',
'subtitle' => '论坛格子布局和瀑布流布局，尺寸自己把握，每个人网站宽度不一样，这里也会不一样的。',
),

array(
'id' => 'jinsom_bbs_no_power_show_title_on_off',
'type' => 'switcher',
'default' => false,
'title' => '开启权限帖子标题',
'desc' => '开启后，如果用户没有权限浏览帖子，但也展示帖子标题',
),


array(
'id'=>'jinsom_bbs_content_number',
'type'=>'spinner',
'unit'=>'字',
'default'=> 5000,
'title'=> '内容字数上限',
'subtitle'=> '论坛发帖，内容限制的最大字数上限',
),


array(
'id'=>'jinsom_bbs_title_number',
'type'=>'spinner',
'unit'=>'字',
'default'=> 30,
'title'=> '标题字数上限',
'subtitle'=> '论坛发帖，标题限制的最大字数上限',
),

array(
'id'=>'jinsom_bbs_comment_words_max',
'type'=>'spinner',
'unit'=>'字',
'default'=> 5000,
'title'=> '帖子回复字数上限',
'desc'=> '帖子的评论内容字数最大上限，动态评论字数上限请到-主题设置-发表模块-基本设置<br>备注：论坛的是富文本编辑器，代码也计入字数。',
),

array(
'id'=>'jinsom_bbs_hide_words_max',
'type'=>'spinner',
'unit'=>'字',
'default'=> 10000,
'title'=> '帖子隐藏内容字数上限',
'desc'=> '帖子的隐藏内容字数最大上限，动态隐藏内容字数上限请到-主题设置-发表模块-基本设置<br>备注：论坛的是富文本编辑器，代码也计入字数。',
),

array(
'id'=>'jinsom_bbs_pay_price_mini',
'type'=>'spinner',
'unit'=>'金币',
'default'=> 10,
'title'=> '付费帖子最低售价',
),

array(
'id'=>'jinsom_bbs_pay_price_max',
'type'=>'spinner',
'unit'=>'金币',
'default'=> 1000,
'title'=> '付费帖子最高售价',
),

array(
'id'=>'jinsom_answer_price_mini',
'type'=>'spinner',
'unit'=>'金币',
'default'=> 10,
'title'=> '问答悬赏最低金额',
),

array(
'id'=>'jinsom_answer_price_max',
'type'=>'spinner',
'unit'=>'金币',
'default'=> 1000,
'title'=> '问答悬赏最高金额',
),



array(
'id'=>'jinsom_vote_allow_time',
'type'=>'spinner',
'unit'=>'小时',
'default'=> 12,
'title'=> '新用户允许投票时间',
'desc'=> '开启后，新注册用户多少小时之后才允许进行投票，如果不开启则设置为0',
),


array(
'id'         => 'jinsom_publish_bbs_add_topic_max',
'type'=>'spinner',
'unit'=>'个',
'default'    => 10,
'title'      => '发布帖子最大允许插入的话题数量',
),

array(
'id' => 'jinsom_publish_bbs_topic_on_off',
'type' => 'switcher',
'default' => false,
'title' => '强制用户发布帖子时添加话题',
),


array(
'id' => 'jinsom_bbs_copyright_info',
'type' => 'textarea',
'title' => '电脑端帖子版权内容',
'subtitle' =>'支持html和短代码，短代码使用说明：<a href="https://q.jinsom.cn/10962.html" target="_blank" style="color:#f00;">https://q.jinsom.cn/10962.html</a>'
),

array(
'id' => 'jinsom_mobile_bbs_copyright_info',
'type' => 'textarea',
'title' => '移动端帖子版权内容',
'subtitle' =>'支持html，<font style="color:#f00;">【不支持短代码】</font>',
'default' =>'未经允许，禁止转载本站所有原创内容'
),



)
));

//发布模块-转发设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>转发设置</span>',
'icon'        => 'fa fa-mail-forward',
'fields'      => array(

array(
'id' => 'jinsom_publish_reprint_on_off',
'type' => 'switcher',
'default' => true,
'title' => '站内转发',
'subtitle' => '开启之后，用户允许转发站内的内容',
),

)
));


//发布模块-红包设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>红包设置</span>',
'icon'        => 'fa jinsom-icon jinsom-hongbao',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '红包个数最大只能100个，不能进行修改。',
),


array(
'id'      => 'jinsom_publish_redbag_cover',
'type'    => 'upload',
'title'   => '红包默认封面图',
'subtitle'      => '用于瀑布流展示',
),

array(
'id'         => 'jinsom_redbag_min',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '发红包最低金额',
'desc'       => '允许用户发红包的最低金额，默认100 <font style="color:#f00;">防止出现小数，建议不要小于100金币</font>',
'default'    =>100,
),

array(
'id'         => 'jinsom_redbag_max',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '发红包最高金额',
'desc'       => '允许用户发红包的最高金额，默认10000',
'default'    =>10000,
),

array(
'id'         => 'jinsom_redbag_desc_number',
'type'       => 'spinner',
'unit'       => '字',
'title'      => '红包贺语最大字数',
'default'    =>30,
),


array(
'id'         => 'jinsom_redbag_default_desc',
'type'       => 'textarea',
'title'      => '默认红包贺语',
'default'    =>'恭喜发财！',
),


array(
'id' => 'jinsom_redbag_img_add',
'type' => 'group',
'title' => '红包封面添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '封面名称',
),

array(
'id' => 'img',
'type' => 'upload',
'title' => '封面图片',
'desc' => '一般为透明底色的png图片',
),


array(
'id' => 'vip',
'type' => 'switcher',
'title' => 'VIP专属',
),


)
) ,



)
));


//发布模块-秘密设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>匿名设置</span>',
'icon'        => 'fa jinsom-icon jinsom-niming',
'fields'      => array(


array(
'id'         => 'jinsom_secret_header_name',
'type'       => 'text',
'title'      => '头部名称',
'subtitle'       => '移动端头部名称',
'default'    =>'匿名说',
),


array(
'id'                 => 'jinsom_publish_secret_power',
'type'               => 'radio',
'title'              => '匿名发布权限',
'subtitle'       => '匿名发布需要的权限，满足权限的用户才可以进行发布',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_publish_secret_exp',
'type'=>'spinner',
'unit'=>'经验值',
'default'    => 10,
'title'      => '发布权限_指定经验值',
'dependency' => array('jinsom_publish_secret_power','==','exp') ,
'subtitle'       => '用户需要达到这个经验值才可以发表',
),

array(
'id'         => 'jinsom_publish_secret_honor_arr',
'type'=>'textarea',
'title'      => '发布权限_指定头衔',
'dependency' => array('jinsom_publish_secret_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_publish_secret_verify_arr',
'type'=>'textarea',
'title'      => '发布权限_指定认证类型',
'dependency' => array('jinsom_publish_secret_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),


array(
'id'         => 'jinsom_secret_credit',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '发布花费金币',
'subtitle'       => '用户每次发布匿名需要花费的金币',
'default'    =>100,
),


array(
'id'         => 'jinsom_secret_content_min',
'type'       => 'spinner',
'unit'       => '字',
'title'      => '匿名内容最低字数',
'subtitle'       => '用户最少允许发多少字的内容',
'default'    =>1,
),

array(
'id'         => 'jinsom_secret_content_max',
'type'       => 'spinner',
'unit'       => '字',
'title'      => '匿名内容最多字数',
'subtitle'       => '用户最多允许发多少字的内容',
'default'    =>300,
),

array(
'id'         => 'jinsom_secret_textarea_placeholder',
'type'       => 'textarea',
'title'      => '发布框提示语',
'default'    =>'把秘密说给树洞听...',
),


array(
'id' => 'jinsom_secret_color_add',
'type' => 'group',
'title' => '发布颜色添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'remarks',
'type' => 'text',
'title' => '备注',
'subtitle'=>'不对外展示',
),

array(
'id' => 'color',
'type' => 'color',
'title' => '颜色',
),


array(
'id' => 'vip',
'type' => 'switcher',
'title' => 'VIP专属',
),


)
) ,


array(
'id' => 'jinsom_secret_type_add',
'type' => 'group',
'title' => '发布类型添加',
'subtitle' => '你可以理解这里的类型相当于标签，用户发布的选择一个类型，比如：心情，感情，学业等等',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '类型名称',
),


)
) ,


array(
'id' => 'jinsom_secret_rand_name',
'type' => 'textarea',
'title' => '随机昵称',
'subtitle' => '请用英文逗号隔开，如果为空则所有昵称都显示“匿名”',
'placeholder' => '张三,李四,王五',
),


array(
'id'         => 'jinsom_secret_rand_avatar_url',
'type'       => 'text',
'title'      => '匿名随机头像库的url地址',
'placeholder'=>'https://xxxxx.com/xxxx/',
'subtitle' =>'别漏了最后的斜杠/，如果不设置则使用非匿名的头像',
'desc'   => '支持外链，如果你网站是https，外链一定要支持https。格式：<font style="color:#f00;">https://xxxxx.com/xxxx/</font>',
),


array(
'id'         => 'jinsom_secret_rand_avatar_number',
'type'       => 'spinner',
'title'      => '匿名随机头像库的数量',
'subtitle'   => '头像格式为png格式',
'desc'   => '默认为40，请填你匿名随机头像库的头像数量',
'default'    =>40,
'unit'     => '个',
),


array(
'id' => 'jinsom_secret_publish_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '匿名发布页底下自定义区域',
'subtitle' => '支持html和短代码',
),




)
));



//发布模块-评论设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>评论回复</span>',
'icon'        => 'fa fa-comment-o',
'fields'      => array(

array(
'id'                 => 'jinsom_comment_power',
'type'               => 'radio',
'title'              => '评论权限',
'subtitle'       => '仅仅针对动态，音乐，视频，文章的评论权限，帖子的回复权限每个论坛单独设置',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_comment_power_exp',
'type'=>'spinner',
'unit'=>'经验值',
'default'    => 10,
'title'      => '评论权限_指定经验值',
'dependency' => array('jinsom_comment_power','==','exp') ,
'subtitle'       => '用户需要达到这个经验值才可以评论内容',
),

array(
'id'         => 'jinsom_comment_power_honor_arr',
'type'=>'textarea',
'title'      => '评论权限_指定头衔',
'dependency' => array('jinsom_comment_power','==','honor_arr') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_comment_power_verify_arr',
'type'=>'textarea',
'title'      => '评论权限_指定认证类型',
'dependency' => array('jinsom_comment_power','==','verify_arr') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),

array(
'id'=>'jinsom_mobile_post_list_comment_list',
'type'=>'switcher',
'default'=> false,
'title'=> '移动端列表是否显示最新3条评论',
),


array(
'id'=>'jinsom_comment_words_max',
'type'=>'spinner',
'unit'=>'字',
'default'=> 300,
'title'=> '评论最多允许字数',
'subtitle'=> '动态、音乐、视频、文章的评论内容字数最大上限，帖子评论字数请到-主题设置-论坛设置',
),

array(
'id'=>'jinsom_comment_words_mini',
'type'=>'spinner',
'unit'=>'字',
'default'=> 0,
'title'=> '评论最少允许字数',
'subtitle'=> '动态、音乐、视频、文章的评论内容字数最小值，如果不限制则输入0，如果输入1则评论字数不能少于1个字，以此类推。',
),


array(
'id' => 'jinsom_bbs_quick_reply_add',
'type' => 'group',
'title' => '快捷回复词',
'subtitle' => '就是回帖的时候预设一些快捷回复，点击之后直接显示对应的回复内容，方便用户回复。备注：电脑端目前只有论坛一级回复有',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '名称',
'subtitle'=>'快捷回复的显示的名称，一般建议2个字',
),

array(
'id' => 'content',
'type' => 'textarea',
'title' => '内容',
'subtitle'=>'快捷回复对应的内容，就是点击名称之后写入的内容',
),



)
) ,


)
));





//发布模块-编辑器设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>编辑器设置</span>',
'icon'        => 'fa fa-list-alt',
'fields'      => array(

array(
'id'             => 'jinsom_bbs_edit_ranking_o',
'type'           => 'sorter',
'title'          => '论坛编辑器功能',
'default'        => array(
'enabled'      => array(
'source'        => '源代码',
'bold' => '加粗',
'italic' => '斜体',
'underline' => '下划线',
'strikethrough' => '删除线',
'a-1' => '分隔符|',
'forecolor' => '字体颜色',
'backcolor' => '背景颜色',
'link' => '超链接',
'unlink' => '取消链接',
'a-2' => '分隔符|',
'lineheight' => '行间距',
'indent' => '首行缩进',
'justifyleft' => '居左对齐',
'justifyright' => '居右对齐',
'justifycenter' => '居中对齐',
'insertorderedlist' => '有序列表',
'insertunorderedlist' => '无序列表',
'a-3' => '分隔符|',
'paragraph' => '段落格式',
'fontsize' => '字号',
'insertcode' => '代码语言',
'preview' => '预览',
),
'disabled'     => array(
'removeformat' => '清除格式',
'horizontal' => '分隔线',
'inserttable' => '插入表格',
'insertframe' => '插入iframe',
'blockquote' => '引用',
'rowspacingtop' => '段前距',
'rowspacingbottom' => '段后距',
'rowspacingbottom' => '段后距',
'undo' => '撤销(后退)',
'redo' => '恢复(前进)',
'fontfamily' => '字体选择',
'a-4' => '分隔符|',
),
),
'subtitle'      => '拖拽排列顺序，移动模块到右侧可关闭',
),


array(
'id'             => 'jinsom_single_edit_ranking_o',
'type'           => 'sorter',
'title'          => '文章编辑器',
'default'        => array(
'enabled'      => array(
'source'        => '源代码',
'bold' => '加粗',
'italic' => '斜体',
'underline' => '下划线',
'strikethrough' => '删除线',
'a-1' => '分隔符|',
'forecolor' => '字体颜色',
'backcolor' => '背景颜色',
'link' => '超链接',
'unlink' => '取消链接',
'a-2' => '分隔符|',
'lineheight' => '行间距',
'indent' => '首行缩进',
'justifyleft' => '居左对齐',
'justifyright' => '居右对齐',
'justifycenter' => '居中对齐',
'insertorderedlist' => '有序列表',
'insertunorderedlist' => '无序列表',
'a-3' => '分隔符|',
'paragraph' => '段落格式',
'fontsize' => '字号',
'insertcode' => '代码语言',
'preview' => '预览',
),
'disabled'     => array(
'removeformat' => '清除格式',
'horizontal' => '分隔线',
'inserttable' => '插入表格',
'insertframe' => '插入iframe',
'blockquote' => '引用',
'rowspacingtop' => '段前距',
'rowspacingbottom' => '段后距',
'undo' => '撤销(后退)',
'redo' => '恢复(前进)',
'fontfamily' => '字体选择',
'a-4' => '分隔符|',
),
),
'subtitle'      => '拖拽排列顺序，移动模块到右侧可关闭',
),


array(
'id'             => 'jinsom_bbs_pay_edit_ranking_o',
'type'           => 'sorter',
'title'          => '文章/论坛隐藏内容编辑器',
'default'        => array(
'enabled'      => array(
'source'        => '源代码',
'bold' => '加粗',
'forecolor' => '字体颜色',
'backcolor' => '背景颜色',
'link' => '超链接',
'unlink' => '取消链接',
'a-1' => '分隔符|',
'lineheight' => '行间距',
'indent' => '首行缩进',
'a-2' => '分隔符|',
'insertcode' => '代码语言',
'preview' => '预览',
),
'disabled'     => array(
'paragraph' => '段落格式',
'fontsize' => '字号',
'justifyleft' => '居左对齐',
'justifyright' => '居右对齐',
'justifycenter' => '居中对齐',
'insertorderedlist' => '有序列表',
'insertunorderedlist' => '无序列表',
'removeformat' => '清除格式',
'horizontal' => '分隔线',
'inserttable' => '插入表格',
'italic' => '斜体',
'underline' => '下划线',
'strikethrough' => '删除线',
'insertframe' => '插入iframe',
'blockquote' => '引用',
'rowspacingtop' => '段前距',
'rowspacingbottom' => '段后距',
'undo' => '撤销(后退)',
'redo' => '恢复(前进)',
'fontfamily' => '字体选择',
'a-3' => '分隔符|',
'a-4' => '分隔符|',
),
),
'subtitle'      => '拖拽排列顺序，移动模块到右侧可关闭',
),




array(
'id'             => 'jinsom_bbs_reply_edit_ranking_o',
'type'           => 'sorter',
'title'          => '论坛回帖编辑器',
'default'        => array(
'enabled'      => array(
'bold' => '加粗',
'italic' => '斜体',
'underline' => '下划线',
'strikethrough' => '删除线',
'a-1' => '分隔符|',
'forecolor' => '字体颜色',
'backcolor' => '背景颜色',
'link' => '超链接',
'a-2' => '分隔符|',
'insertcode' => '代码语言',
'preview' => '预览',
),
'disabled'     => array(
'source'        => '源代码',
'unlink' => '取消链接',
'lineheight' => '行间距',
'indent' => '首行缩进',
'justifyleft' => '居左对齐',
'justifyright' => '居右对齐',
'justifycenter' => '居中对齐',
'insertorderedlist' => '有序列表',
'insertunorderedlist' => '无序列表',
'paragraph' => '段落格式',
'fontsize' => '字号',
'removeformat' => '清除格式',
'horizontal' => '分隔线',
'inserttable' => '插入表格',
'insertframe' => '插入iframe',
'blockquote' => '引用',
'rowspacingtop' => '段前距',
'rowspacingbottom' => '段后距',
'undo' => '撤销(后退)',
'redo' => '恢复(前进)',
'fontfamily' => '字体选择',
'a-3' => '分隔符|',
'a-4' => '分隔符|',
),
),
'subtitle'      => '拖拽排列顺序，移动模块到右侧可关闭',
),




)
));

//发布模块-表情设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_publish',
'title'       => '<span>表情设置</span>',
'icon'        => 'fa jinsom-icon jinsom-weixiao-',
'fields'      => array(


array(
'type' => 'notice',
'style' => 'danger',
'content' => '注意：如果不配置则无法使用表情，官方默认表情素材请在用户群文件下载。',
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '注意：每个表情包必须是从1开始命名的png格式，如果使用gif的表情，只需要把gif后缀改为png即可。',
),

array(
'id'         => 'jinsom_smile_url',
'type'       => 'text',
'title'      => '存放表情包的目录路径',
'placeholder'=>'https://xxxxx.com/xxxx/',
'subtitle' =>'如果有疑问，请查看<a href="https://q.jinsom.cn/14126.html" target="_blank">《配置教程》</a>',
'desc'   => '支持外链，如果你网站是https，外链一定要支持https。格式：<font style="color:#f00;">https://xxxxx.com/xxxx/</font>',
),


array(
'id' => 'jinsom_smile_add',
'type' => 'group',
'title' => '添加表情包',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '表情包名称',
),

array(
'id' => 'number',
'type'=>'spinner',
'title' => '表情包数量',
),

array(
'id'         => 'smile_url',
'type'       => 'text',
'title'      => '表情包文件夹名称',
'subtitle' =>'比如：smile-1，smile-2',
),

)
) ,

)
));


//礼物模块
LightSNS::createSection( $prefix, array(
'title'       => '<span>礼物模块</span>',
'icon'        => 'fa fa-gift',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '添加好礼物数据之后，用户可以在个人主页里面给对方赠送礼物。电脑端和移动端都可以赠送礼物。',
),

array(
'id' => 'jinsom_gift_on_off',
'type' => 'switcher',
'default' => true,
'title' => '礼物功能',
),

array(
'id' => 'jinsom_gift_add',
'type' => 'group',
'title' => '添加礼物数据',
'dependency' => array('jinsom_gift_on_off','==','true'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '礼物名称',
'subtitle' => '唯一性，不能重复！',
),

array(
'id'      => 'images',
'type'    => 'upload',
'title'   => '礼物图片',
'placeholder' => 'https://',
'subtitle'=>'必须是透明底的正方形的图片，尺寸不能小于100px*100px，建议尺寸是：200px*200px',
),


array(
'id' => 'credit',
'type'=>'spinner',
'title' => '礼物售价',
'unit'=>'金币',
'desc'=>'就是用户需要花费多少金币才能购买这个礼物',
),

array(
'id' => 'm',
'type'=>'spinner',
'title' => '礼物魅力值',
'unit'=>'魅力',
'desc'=>'被赠送者会增加多少魅力值，【注意】可以设置负数，赠送之后对方会减掉魅力值',
),

array(
'id' => 'gift_number',
'type'=>'spinner',
'title' => '礼物积分',
'default'=>0,
'unit'=>'积分',
'desc'=>'被赠送者会获得多少礼物积分，禁止为负数',
),

array(
'id' => 'vip',
'type' => 'switcher',
'default' => false,
'title' => 'VIP用户专属',
'subtitle' => '开启之后，这个礼物只能VIP用户才能送给别人',
),



)
), 


array(
'id'      => 'jinsom_gift_credit_ratio',
'type'       => 'spinner',
'title'   => '礼物积分转金币比例',
'dependency' => array('jinsom_gift_on_off','==','true'),
'default' => 2,
'subtitle'   => '当给对方送礼物，对方可以获得对应的礼物积分。',
'desc'      => '默认2：1，也就是说100礼物积分可以转换50金币',
),



)
));

//商城模块
LightSNS::createSection( $prefix, array(
'title'       => '<span>商城模块</span>',
'icon'        => 'fa fa-shopping-bag',
'fields'      => array(


array(
'id' => 'jinsom_shop_on_off',
'type' => 'switcher',
'default' => true,
'title' => '商城功能',
'subtitle' => '关闭之后，所有商品都无法被购买',
),


array(
'id'      => 'jinsom_shop_goods_slug_name',
'type'       => 'text',
'title'   => '商城商品路径',
'default' => 'goods',
'subtitle'   => '<font style="color:#f00;">修改了之后请一定要到WordPress后台-固定链接-重新保存-生效</font>',
),


array(
'id'                 => 'jinsom_shop_kefu_type',
'type'               => 'radio',
'title'              => '客服类型',
'subtitle'              => '用户点击客服所触发的功能，<font style="color:#f00;">每个商品可以单独设置客服，如果不设置则使用这里的设置</font>',
'options'            => array(
'im'              => '调用IM聊天',
'qq'         => '调用QQ聊天',
'link'           => '跳转指定链接',
'no'           => '不显示',
),
'default'       =>'im',
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
'id' => 'jinsom_shop_single_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端商品页面头部自定义区域',
'subtitle' => '支持html和短代码',
),

array(
'id' => 'jinsom_shop_single_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端商品页面底部自定义区域',
'subtitle' => '支持html和短代码',
),


)
));

//任务系统
LightSNS::createSection( $prefix, array(
'title'       => '<span>任务模块</span>',
'icon'        => 'fa jinsom-icon jinsom-flag',
'fields'      => array(


array(
'type' => 'notice',
'style' => 'danger',
'content' => '注意：所有任务的任务ID都是唯一性的，不能重复！！！',
),

array(
'id' => 'jinsom_task_treasure_on_off',
'type' => 'switcher',
'default' => false,
'title' => '宝箱任务',
'subtitle' => '开启之后，用户可以通过累计任务数量打开宝箱',
),


array(
'id' => 'jinsom_task_treasure_add',
'type' => 'group',
'title' => '宝箱任务添加',
'subtitle' => '宝箱素材下载请点击<a href="https://q.jinsom.cn/15511.html" style="color:#f00;" target="_blank">《这里》</a>',
'dependency' => array('jinsom_task_treasure_on_off','==','true') ,
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '宝箱名称',
'placeholder' => '青铜宝箱',
),

array(
'id' => 'id',
'type' => 'text',
'title' => '任务ID',
'placeholder'=>'task001',
'desc' => '<font style="color:#f00;">必填、唯一性，建议使用字母+数字，添加之后不要再次修改，否则对应的任务用户可以重复领取奖励。</font>',
),

array(
'id'      => 'number',
'type'       => 'spinner',
'title'   => '需要完成的任务数量',
'default' => 10,
'unit'=>'个',
'desc'   => '当用户累计完成的任务数达到这个数就可以开启这个宝箱',
),

array(
'id'      => 'close_img',
'type'    => 'upload',
'title'   => '关闭的宝箱图标',
'placeholder' => 'https://'
),

array(
'id'      => 'open_img',
'type'    => 'upload',
'title'   => '打开的宝箱图标',
'placeholder' => 'https://'
),

array(
'id' => 'reward_add',
'type' => 'group',
'title' => '宝箱奖励添加',
'subtitle' => '开启宝箱可获得的奖励',
'button_title' => '添加',
'fields' => array(

array(
'id'     => 'reward_type',
'type'   => 'select',
'title'  => '奖励类型',
'options'=> array(
'credit'=>'金币',
'exp'=>'经验',
'vip'=>'VIP天数',
'vip_number'=>'成长值',
'sign'=>'补签卡',
'nickname'=>'改名卡',
'honor'=>'头衔',
'charm'=>'魅力值',
),
'default'=>'credit',
),

array(
'id' => 'reward_number',
'type'=>'spinner',
'title' => '奖励的数量',
'dependency' => array('reward_type','!=','honor'),
'default'=>10,
),

array(
'id' => 'reward_honor',
'type'=>'text',
'title' => '头衔名称',
'desc' => '用户领取这个宝箱可以获得这个头衔',
'dependency' => array('reward_type','==','honor'),
),


)
),


)
),

array(
'id' => 'jinsom_task_day_add',
'type' => 'group',
'title' => '每日任务添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '任务名称',
'subtitle' => '用户看到当前的任务名称',
),

array(
'id' => 'id',
'type' => 'text',
'title' => '任务ID',
'placeholder'=>'task001',
'desc' => '<font style="color:#f00;">必填、唯一性，建议使用字母+数字，添加之后不要再次修改，否则对应的任务用户可以重复领取奖励。</font>',
),

array(
'id' => 'desc',
'type' => 'textarea',
'title' => '任务说明',
'subtitle' => '可以简单描述一下你的任务',
),

array(
'id'     => 'jinsom_task_day_event_type',
'type'   => 'select',
'title'  => '事件类型',
'options'=> array(
'publish'=>'发表动态(动态、音乐、文章、视频)',
'comment'=>'评论内容(动态、音乐、文章、视频)',
'publish_bbs'=>'发表帖子',
'comment_bbs'=>'回复帖子',
'follow'=>'关注用户',
'chat'=>'聊天',
'like'=>'喜欢内容',
'comment_up'=>'评论点赞',
'draw'=>'抽奖',
'gift'=>'送礼物',
'reward'=>'打赏',
'buy'=>'购买付费内容',
'visit'=>'访问他人主页',
'login'=>'每日登录',
'sign'=>'签到',
'invite'=>'邀请注册',
'ad'=>'点击广告/赞助商',
'pet_times'=>'孵化宠物',
'pet_steal_times'=>'捕获宠物',
),
'default'=>'publish',
),

array(
'id' => 'event_number',
'type'=>'spinner',
'title' => '事件完成次数',
'dependency' => array('jinsom_task_day_event_type|jinsom_task_day_event_type','!=|!=','sign|login') ,
'default'=>1,
'unit'=>'次',
),

array(
'id' => 'reward_add',
'type' => 'group',
'title' => '任务奖励添加',
'subtitle' => '完成当前任务可获得的奖励',
'button_title' => '添加',
'fields' => array(

array(
'id'     => 'reward_type',
'type'   => 'select',
'title'  => '奖励类型',
'options'=> array(
'credit'=>'金币',
'exp'=>'经验',
'vip'=>'VIP天数',
'vip_number'=>'成长值',
'sign'=>'补签卡',
'nickname'=>'改名卡',
'charm'=>'魅力值',
),
'default'=>'credit',
),

array(
'id' => 'reward_number',
'type'=>'spinner',
'title' => '普通用户奖励的数量',
'default'=>10,
),

array(
'id' => 'reward_number_vip',
'type'=>'spinner',
'title' => 'VIP用户奖励的数量',
'default'=>20,
),

)
),

)
), 



array(
'id' => 'jinsom_task_base_add',
'type' => 'group',
'title' => '成长任务添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '任务名称',
'subtitle' => '用户看到当前的任务名称',
),

array(
'id' => 'id',
'type' => 'text',
'title' => '任务ID',
'placeholder'=>'task001',
'desc' => '<font style="color:#f00;">必填、唯一性，建议使用字母+数字，添加之后不要再次修改，否则对应的任务用户可以重复领取奖励。</font>',
),

array(
'id' => 'desc',
'type' => 'textarea',
'title' => '任务说明',
'subtitle' => '可以简单描述一下你的任务',
),

array(
'id'     => 'event_type',
'type'   => 'select',
'title'  => '事件类型',
'options'=> array(
'phone'=>'绑定手机号',
'email'=>'绑定邮箱号',
'question'=>'设置安全问题',
'desc'=>'填写个人说明',
'qq'=>'绑定QQ登录',
'wechat'=>'绑定微信登录',
'weibo'=>'绑定微博登录',
'member_bg'=>'设置个人主页背景',
'honor'=>'使用头衔',
'avatar'=>'上传头像',
'is_vip'=>'成为会员',
'is_verify'=>'成为认证用户',
'reward'=>'累计打赏达到指定数量',
'adopt'=>'累计被采纳达到指定数量',
'follower'=>'粉丝达到指定数量',
'exp'=>'经验值达到指定数量',
'vip_number'=>'成长值达到指定数量',
'recharge'=>'充值达到指定数量[金币]',
'credit'=>'金币达到指定数量',
'visitor'=>'人气达到指定数量',
'charm'=>'魅力值达到指定数量',
'sign'=>'累计签到达到指定天数',
'content_number'=>'累计发表内容达到指定数量 (所有内容)',
'pet_times'=>'累计孵化宠物',
'pet_steal_times'=>'累计捕获宠物',
'cuctom_key'=>'存在指定字段值(meta_key)',
),
'default'=>'phone',
),


array(
'id' => 'event_number',
'type'=>'spinner',
'title' => '事件完成数量',
'dependency' => array('event_type','not-any','phone,email,question,desc,qq,wechat,weibo,member_bg,honor,avatar,is_vip,is_verify,cuctom_key'),
'default'=>10,
),

array(
'id' => 'event_key',
'type'=>'text',
'title' => '字段值',
'subtitle' => '格式：<font style="color:#f00;">字段key|数量</font>，如果仅判断是否存在，数量填1',
'dependency' => array('event_type','==','cuctom_key'),
'default'=>'test|1',
),


array(
'id' => 'reward_add',
'type' => 'group',
'title' => '任务奖励添加',
'subtitle' => '完成当前任务可获得的奖励',
'button_title' => '添加',
'fields' => array(

array(
'id'     => 'reward_type',
'type'   => 'select',
'title'  => '奖励类型',
'options'=> array(
'credit'=>'金币',
'exp'=>'经验',
'vip'=>'VIP天数',
'vip_number'=>'成长值',
'sign'=>'补签卡',
'nickname'=>'改名卡',
'honor'=>'头衔',
'charm'=>'魅力值',
),
'default'=>'credit',
),

array(
'id' => 'reward_number',
'type'=>'spinner',
'title' => '对应奖励的数量',
'dependency' => array('reward_type','!=','honor'),
'default'=>10,
),

array(
'id' => 'reward_honor',
'type'=>'text',
'title' => '头衔名称',
'desc' => '用户完成任务可以获得这个头衔',
'dependency' => array('reward_type','==','honor'),
),

)
),

)
), 

)
));

//论坛设置
LightSNS::createSection( $prefix, array(
'title'       => '<span>论坛设置</span>',
'icon'        => 'fa fa-comments',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '如何创建论坛？如果不懂请参考<a href="https://q.jinsom.cn/6309.html" target="_blank" style="color:#f00;">《这篇教程》</a>',
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '这里是论坛公共设置部分，每个论坛的单独设置在论坛界面的背景封面右上角的小齿轮，如果不懂可以点击这里<a href="https://q.jinsom.cn/10551.html" target="_blank" style="color:#f00;">《查看教程》</a>',
),

array(
'id'      => 'jinsom_bbs_name',
'type'    => 'text',
'title'   => '论坛的名称',
'subtitle' => '比如：圈子、小组、贴吧',
'default' => '论坛'
),

array(
'id'      => 'jinsom_bbs_default_avatar',
'type'    => 'upload',
'title'   => '论坛默认头像',
'subtitle' => '150px*150px,正方形就行，留空使用默认',
'placeholder' => 'https://'
),

array(
'id'      => 'jinsom_bbs_default_bg_pc',
'type'    => 'upload',
'title'   => '电脑端论坛默认背景封面',
'subtitle' => '高度180px，这里是全局的设置，每个论坛可以单独设置的',
'placeholder' => 'https://'
),

array(
'id'      => 'jinsom_bbs_default_bg_mobile',
'type'    => 'upload',
'title'   => '移动端论坛默认背景封面',
'subtitle' => '尺寸跟随屏幕自适应，这里是全局的设置，每个论坛可以单独设置的',
'placeholder' => 'https://'
),


array(
'id'                 => 'jinsom_apply_bbs_power',
'type'               => 'radio',
'title'              => '论坛申请权限',
'subtitle'       => '哪些用户才允许申请提交开通论坛，申请之后需要管理员审核才会通过',
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'exp'             => '指定经验值',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_apply_bbs_power_exp',
'type'=>'spinner',
'unit'=>'经验值',
'default'    => 10,
'title'      => '论坛申请权限_指定经验值',
'dependency' => array('jinsom_apply_bbs_power','==','exp') ,
'subtitle'       => '用户需要达到这个经验值才可以申请开通论坛',
),


array(
'id'         => 'jinsom_bbs_commend_list',
'type'=>'textarea',
'title'      => '推荐的论坛',
'placeholder'       => '请输入父级论坛ID，多个ID请用英文逗号隔开，建议只设置没有权限的板块',
),


array(
'id'      => 'jinsom_bbs_hide',
'type'    => 'checkbox',
'title'=> 'SNS首页不对外显示的论坛',
'subtitle'=> '设置之后，对应的论坛的帖子不会在SNS首页显示。',
'options' => 'categories',
'query_args'=>array(
'exclude'=>array(1),
'hide_empty'=>false
)
),

array(
'id'      => 'jinsom_bbs_show_hide',
'type'    => 'checkbox',
'title'=> '论坛大厅不对外显示的论坛',
'subtitle'=> '设置之后，对应的论坛的帖子不会在论坛大厅的帖子列表显示。',
'options' => 'categories',
'query_args'=>array(
'exclude'=>array(1),
'hide_empty'=>false
)
),

array(
'id'      => 'jinsom_search_bbs_hide',
'type'    => 'checkbox',
'title'=> '搜索结果不对外显示的论坛',
'subtitle'=> '设置之后，对应的论坛的帖子不会在搜索结果里面',
'options' => 'categories',
'query_args'=>array(
'exclude'=>array(1),
'hide_empty'=>false
)
),


array(
'type' => 'notice',
'style' => 'danger',
'content' => '论坛瀑布流公共设置',
) ,

array(
'id' => 'jinsom_waterfull_margin',
'type'=>'spinner',
'title' => '瀑布流间距',
'unit'=>'px',
'default'=>'15',
'subtitle'=>'右边距和下边距，默认15px',
),


)
));


//话题设置
LightSNS::createSection( $prefix, array(
'title'       => '<span>话题设置</span>',
'icon'        => 'fa fa-hashtag',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '这里是话题的公共设置部分，每个话题的单独设置在话题界面的背景封面右上角的小齿轮',
),


array(
'id'      => 'jinsom_topic_name',
'type'    => 'text',
'title'   => '话题的名称',
'subtitle' => '比如：标签、印记',
'default' => '话题'
),

array(
'id'      => 'jinsom_topic_default_avatar',
'type'    => 'upload',
'title'   => '话题默认头像',
'desc' => '80px*80px,正方形就行，大一点也无所谓，留空使用默认',
'placeholder' => 'https://'
),

array(
'id'      => 'jinsom_topic_default_bg',
'type'    => 'upload',
'title'   => '话题默认背景封面',
'desc' => '尺寸自己看着设置，因为涉及单栏和双栏。',
'placeholder' => 'https://'
),


array(
'id' => 'jinsom_topic_sns_hide',
'type' => 'textarea',
'title' => 'SNS首页不显示指定话题ID的内容',
'subtitle'=>'多个话题ID请用英文逗号隔开，如果某个内容含有指定的话题，则该内容不会显示在sns首页“全部”的菜单里面',
'placeholder' => '1,2,3,4,5,6'
),

array(
'id' => 'jinsom_topic_default_desc',
'type' => 'textarea',
'title' => '话题默认描述',
'default'=>'这是一个很神秘的话题...',
),


)
));




//功能应用
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_page',
'title'  => '<span>功能应用</span>',
'icon'   => 'fa fa-file',
));



//功能应用-小黑屋
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_page',
'title'       => '<span>小黑屋</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'id' => 'jinsom_blacklist_bail_number',
'type' => 'spinner',
'title' => '保释每天所需要的金币',
'subtitle' => '保释黑名单用户每天所需要的金币',
'default' => 1000,
),


array(
'id' => 'jinsom_blacklist_page_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '小黑屋头部自定义区域',
'subtitle' => '支持html',
'default' =>'<h1>社区监狱</h1>'
),

array(
'id' => 'jinsom_blacklist_page_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '小黑屋底部自定义区域',
'subtitle' => '支持html',
),

)
));


//功能应用-幸运转盘
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_page',
'title'       => '<span>幸运转盘</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '该功能目前只有移动端，入口短代码：<font style="color:#f00">[link type=mobile_page]/mywallet/lottery.php</font>',
),

array(
'id' => 'jinsom_lottery_on_off',
'type' => 'switcher',
'default' => false,
'title' => '开启幸运转盘',
),

array(
'id'         => 'jinsom_lottery_min',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '最低下注金额',
'dependency' => array('jinsom_lottery_on_off','==','true') ,
'default'    =>10,
),

array(
'id'         => 'jinsom_lottery_max',
'type'       => 'spinner',
'unit'       => '金币',
'title'      => '最高下注金额',
'dependency' => array('jinsom_lottery_on_off','==','true') ,
'default'    =>1000,
),

array(
'id'         => 'jinsom_lottery_play_max',
'type'       => 'spinner',
'unit'       => '次',
'title'      => '每天抽奖次数上限',
'dependency' => array('jinsom_lottery_on_off','==','true') ,
'desc'       => '每天允许玩大转盘的次数上限，默认20次',
'default'    =>20,
),



array(
'id' => 'jinsom_lottery_add',
'type' => 'group',
'title' => '大转盘选项设置',
'min'      => 12,
'max'      => 12,
'dependency' => array('jinsom_lottery_on_off','==','true') ,
'subtitle' => '必须添加12项，不能多也不能少。',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'number',
'type'       => 'spinner',
'unit'       => '倍',
'title' => '中奖倍数',
'desc' => '用户最终获得金币=下注金额*倍数，可以为小数，比如0.5',
),

array(
'id' => 'chance',
'type'       => 'spinner',
'title' => '当前选项的中奖概率',
'desc' => '请填入1-100数，12项选项的概率相加必须为100',
),

array(
'id' => 'msg',
'type' => 'textarea',
'title' => '中奖之后的提示信息',
),


)
) ,



array(
'id'         => 'jinsom_lottery_rule_post_id',
'type'       => 'spinner',
'dependency' => array('jinsom_lottery_on_off','==','true') ,
'title'      => '大转盘规则说明页ID',
'subtitle'       => '只需要填对应的文章ID或者页面ID即可。',
),

)
));



//功能应用-宠物乐园
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_page',
'title'       => '<span>宠物乐园</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '该功能目前只有移动端，入口短代码：<font style="color:#f00">[link type=mobile_page]/pet.php</font>',
),

array(
'id'     => 'jinsom_pet_steal_type',
'type'   => 'select',
'title'  => '偷取模式',
'options'=> array(
'all'=>'全被偷取',
'half'=>'一半偷取',
'rand'=>'随机偷取',
),
'default'=>'all',
),

array(
'id' => 'jinsom_pet_protect_time',
'type' => 'spinner',
'title' => '保护期时间',
'subtitle' => '宠物蛋孵化好了之后，过了保护期时间才允许被偷取',
'unit'       => '秒',
'default' => 600,
),

array(
'id' => 'jinsom_pet_steal_commission',
'type' => 'spinner',
'title' => '偷取手续费',
'subtitle' => '仅仅针对偷取行为需要收取手续费',
'unit'       => '%',
'default' => 0,
),

array(
'id' => 'jinsom_pet_same_number',
'type' => 'spinner',
'title' => '同时孵化相同的宠物蛋的个数',
'subtitle' => '就是同样的宠物蛋可以同时孵化几个',
'unit'       => '个',
'default' => 1,
),

array(
'id'      => 'jinsom_pet_nest_img',
'type'    => 'upload',
'title'   => '宠物窝的图标',
'subtitle' => '可以直接扒官网的或者自己作图',
'placeholder' => 'https://'
),

array(
'id'      => 'jinsom_pet_steal_img',
'type'    => 'upload',
'title'   => '可偷取标志',
'subtitle' => '正方形小图标：如果该玩家的宠物可以被捕获会显示一个标志，官网的是一个蛋',
'placeholder' => 'https://'
),


array(
'id' => 'jinsom_pet_add',
'type' => 'group',
'title' => '添加宠物蛋',
'subtitle' => '添加的宠物蛋会在商城里面出售',
'button_title' => '添加',
'accordion_title' => '新增的宠物蛋',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '名称',
),

array(
'id' => 'price_egg',
'type' => 'spinner',
'title' => '宠物蛋售价',
'unit'       => '金币',
'default' => 10,
),

array(
'id' => 'price_pet',
'type' => 'spinner',
'title' => '宠物售价',
'subtitle' => '孵化之后的宠物售价',
'unit'       => '金币',
'default' => 100,
),

array(
'id'      => 'img_egg',
'type'    => 'upload',
'title'   => '宠物蛋图标',
'subtitle'=>'建议尺寸为100*100的png图标',
'placeholder' => 'https://'
),

array(
'id'      => 'img_pet',
'type'    => 'upload',
'title'   => '宠物图标',
'subtitle' => '孵化之后的宠物图标，建议尺寸为100*100的png图标',
'placeholder' => 'https://'
),


array(
'id' => 'time',
'type' => 'spinner',
'title' => '孵化时间',
'unit'       => '分钟',
'default' => 10,
),

array(
'id' => 'vip',
'type' => 'switcher',
'title' => 'VIP专属',
'subtitle' => '开启之后，只允许vip用户购买',
'default' => false,
),


)
) ,


array(
'id' => 'jinsom_pet_nest_add',
'type' => 'group',
'title' => '添加宠物窝',
'button_title' => '添加',
'accordion_title' => '新增的宠物窝',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '名称',
'subtitle' => '<font style="color:#f00;">名称不能重复，必须是唯一</font>',
),

array(
'id'                 => 'jinsom_pet_nest_type',
'type'               => 'select',
'title'              => '解锁条件',
'options'            => array(
'free'         => '免费',
'credit'       => '金币',
'vip'  => 'VIP会员',
'exp'     => '经验值达到指定值',
'vip_number'     => '成长值达到指定值',
'charm'     => '魅力值达到指定值',
'visitor'     => '人气值达到指定值',
),
'default'     =>'free',
),

array(
'id'         => 'jinsom_pet_nest_type_credit',
'type'=>'spinner',
'unit'=>'金币',
'default'    => 1000,
'title'      => '解锁条件_指定金币',
'dependency' => array('jinsom_pet_nest_type','==','credit'),
'subtitle'       => '用户需要花费指定金币解锁',
),

array(
'id'         => 'jinsom_pet_nest_type_exp',
'type'=>'spinner',
'unit'=>'经验值',
'default'    => 1000,
'title'      => '解锁条件_经验值大于',
'dependency' => array('jinsom_pet_nest_type','==','exp') ,
'subtitle'       => '用户经验值需要大于设定值才可以解锁',
),

array(
'id'         => 'jinsom_pet_nest_type_vip_number',
'type'=>'spinner',
'unit'=>'成长值',
'default'    => 1000,
'title'      => '解锁条件_成长值大于',
'dependency' => array('jinsom_pet_nest_type','==','vip_number') ,
'subtitle'       => '用户成长值需要大于设定值才可以解锁',
),

array(
'id'         => 'jinsom_pet_nest_type_charm',
'type'=>'spinner',
'unit'=>'魅力值',
'default'    => 1000,
'title'      => '解锁条件_魅力值大于',
'dependency' => array('jinsom_pet_nest_type','==','charm') ,
'subtitle'       => '用户魅力值需要大于设定值才可以解锁',
),

array(
'id'         => 'jinsom_pet_nest_type_visitor',
'type'=>'spinner',
'unit'=>'人气值',
'default'    => 1000,
'title'      => '解锁条件_人气值大于',
'dependency' => array('jinsom_pet_nest_type','==','visitor') ,
'subtitle'       => '用户人气值需要大于设定值才可以解锁',
),

)
) ,

array(
'id' => 'jinsom_pet_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '头部自定义内容',
),

array(
'id' => 'jinsom_pet_footer_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '底部自定义内容',
),


)
));


//功能应用-在线挑战
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_page',
'title'       => '<span>在线挑战</span>',
'icon'        => 'fa fa-file-o',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '该功能目前只有移动端，入口短代码：<font style="color:#f00">[link type=mobile_page]/challenge.php</font>',
),


array(
'id' => 'jinsom_challenge_on_off',
'type' => 'switcher',
'default' => true,
'title' => '挑战功能开启',
),


array(
'id'         => 'jinsom_challenge_page_title',
'type'       => 'text',
'title'      => '页面标题',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'default'       => '在线挑战',
),



array(
'id'                 => 'jinsom_challenge_power',
'type'               => 'radio',
'title'              => '在线挑战功能使用权限',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'options'            => array(
'login'              => '登录用户',
'vip'         => 'VIP用户',
'verify'           => '所有认证的用户',
'honor'           => '所有拥有头衔的用户',
'admin'             => '管理团队',
'exp'             => '指定经验值',
'honor_arr'             => '指定头衔',
'verify_arr'             => '指定认证类型',
),
'default'       =>'login',
),

array(
'id'         => 'jinsom_challenge_power_exps',
'type'       => 'spinner',
'unit'       => '经验值',
'default'    => 10,
'title'      => '在线挑战权限_指定经验值',
'dependency' => array('jinsom_challenge_power|jinsom_challenge_on_off','==|==','exp|true') ,
'desc'       => '用户需要达到这个经验值才可以使用',
),

array(
'id'         => 'jinsom_challenge_power_honor_arr',
'type'=>'textarea',
'title'      => '在线挑战权限_指定头衔',
'dependency' => array('jinsom_challenge_power|jinsom_challenge_on_off','==|==','honor_arr|true') ,
'placeholder' =>'头衔1,头衔2,头衔3',
'subtitle'       => '可以指定多个头衔，用英文逗号隔开。用户只要拥有对应的头衔就有权限',
),

array(
'id'         => 'jinsom_challenge_power_verify_arr',
'type'=>'textarea',
'title'      => '在线挑战权限_指定认证类型',
'dependency' => array('jinsom_challenge_power|jinsom_challenge_on_off','==|==','verify_arr|true') ,
'placeholder' =>'个人认证,企业认证,达人认证',
'subtitle'       => '可以指定多个认证类型，用英文逗号隔开。',
),



array(
'id'      => 'jinsom_challenge_times',
'type'       => 'spinner',
'unit'       => '次',
'title'   => '每天允许发起挑战次数',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'default' => 30,
),

array(
'id'      => 'jinsom_challenge_poundage',
'type'       => 'spinner',
'unit'       => '%',
'title'   => '挑战手续费/次',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'default' => 10,
),

array(
'id' => 'jinsom_challenge_number_add',
'type' => 'group',
'title' => '添加默认挑战金额',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'button_title' => '添加',
'fields' => array(

array(
'id' => 'number',
'type'       => 'spinner',
'unit'       => '金币',
'title' => '数量',
),

)
),

array(
'id'         => 'jinsom_challenge_default_color',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'type'       => 'color',
'title'      => '发起挑战按钮背景色',
'default'       => '#4e54c8',
),


array(
'id'         => 'jinsom_challenge_default_desc',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'type'       => 'textarea',
'title'      => '默认挑战宣言',
'default'       => '不服就来干！',
),

array(
'id' => 'jinsom_challenge_header_html',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '在线挑战头部自定义区域',
'subtitle' => '一般说明注意事项，支持html和短代码',
),

array(
'id' => 'jinsom_challenge_publish_footer_html',
'dependency' => array('jinsom_challenge_on_off','==','true'),
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '发起挑战底部自定义区域',
'subtitle' => '一般说明手续费和玩法，支持html和短代码',
),


)
));


//支付充值
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_wallet',
'title'  => '<span>支付充值</span>',
'icon'   => 'fa fa-folder',
));


//支付充值-支付配置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_wallet',
'title'       => '<span>支付配置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'id' => 'jinsom_pay_add',
'type' => 'group',
'title' => '支付选项添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '选项名称',
),

array(
'id' => 'jinsom_pay_add_type',
'type' => 'select',
'title' => '支付类型',
'options'      => array(
'alipay_pc' => '支付宝电脑网站支付',
'alipay_mobile' => '支付宝H5支付',
'alipay_code' => '支付宝当面付',
'wechatpay_pc' => '微信电脑扫码支付',
'wechatpay_mobile' => '微信H5支付',
'wechatpay_mp' => '微信公众号支付',
'xunhupay_wechat_pc' => '迅虎微信电脑扫码支付',
'xunhupay_wechat_mobile' => '迅虎微信H5支付',
'creditpay' => '金币支付(仅在会员充值生效)',
'epay_alipay' => '易支付-支付宝',
'epay_wechatpay' => '易支付-微信',
'mapay_alipay' => '码支付-支付宝',
'mapay_wechatpay' => '码支付-微信',
),
),


)
),

array(
'id'    => 'jinsom_pay_tab',
'type'  => 'tabbed',
'title' => '支付配置',
'subtitle' => '如果使用支付功能，请先上传SDK文件，点击这里<a href="https://q.jinsom.cn/36888.html" target="_blank" style="color:#f00;">《查看教程》并下载</a>',
'tabs'  => array(

array(
'title'  => '支付宝支付',
'fields' => array(

array(
'type' => 'notice',
'style' => 'success',
'content' => '包含：电脑端支付、H5支付、当面付',
),


array(
'id'         => 'jinsom_alipay_h5_appid',
'type'       => 'text',
'title'      => '应用的APPID',
'desc'      => '时间年份开头的，不是PID。查看位置：账户中心->密钥管理->开放平台密钥-APPID',
'placeholder' => '2018070660500526'
),

array(
'id'         => 'jinsom_alipay_h5_public_key',
'type'       => 'textarea',
'title'      => '支付宝公钥',
'subtitle'      => '首先你自己通过<a href="https://docs.open.alipay.com/291/105971" target="_blank" style="color:#f00;text-decoration:underline;">RSA签名验签工具</a>生成一个公钥和私钥，然后把生成的公钥填在支付宝后台可以获得<font style="color:#f00">支付宝公钥</font>，然后把<font style="color:#f00">支付宝公钥</font>填在这里就行了',
'desc'      => '查看位置：账户中心->密钥管理->开放平台密钥-设置/查看-支付宝公钥',
),


array(
'id'         => 'jinsom_alipay_h5_private_key',
'type'       => 'textarea',
'title'      => '私钥',
'subtitle'      => '通过<a href="https://docs.open.alipay.com/291/105971" target="_blank" style="color:#f00;text-decoration:underline;">RSA签名验签工具</a>生成的私钥',
'desc'      => '填写对应签名算法类型的私钥，如何生成密钥参考：<a href="https://docs.open.alipay.com/291/105971" target="_blank">https://docs.open.alipay.com/291/105971</a>和<a href="https://docs.open.alipay.com/200/105310" target="_blank">https://docs.open.alipay.com/200/105310</a>',
),


),
),


array(
'title'  => '微信支付',
'fields' => array(

array(
'type' => 'notice',
'style' => 'success',
'content' => '包含：电脑扫码支付、H5支付、公众号支付<br>电脑端扫码需要1，2，4项信息。手机端H5需要全部四项信息。公众号支付也需要下面四项信息。',
),

array(
'id'         => 'jinsom_wechatpay_mchid',
'type'       => 'text',
'title'      => '微信支付商户号（PartnerID）',
'desc'       => '通过微信支付商户资料审核后邮件发送',
'placeholder' => '1511612451'
),

array(
'id'         => 'jinsom_wechatpay_appid',
'type'       => 'text',
'title'      => '公众号APPID',
'desc'       => '通过微信支付商户资料审核后邮件发送',
'placeholder' => 'wx7bef2*******589'
),

array(
'id'         => 'jinsom_wechatpay_appkey',
'type'       => 'text',
'title'      => '公众号AppSecret',
'desc'       => '微信支付申请对应的公众号的APP Key',
),

array(
'id'         => 'jinsom_wechatpay_apiKey',
'type'       => 'text',
'title'      => '微信商户支付密钥',
'desc'       => '帐户设置-安全设置-API安全-API密钥-设置API密钥',
),

),
),


array(
'title'  => '迅虎支付',
'fields' => array(

array(
'id'         => 'jinsom_xunhu_appid',
'type'       => 'text',
'title'      => '迅虎APPID',
'subtitle'   => '<a href="https://admin.xunhupay.com/sign-up/6382.html" target="_blank" style="color:#f00;">请点击这里申请：https://admin.xunhupay.com/sign-up/6382.html</a>',
'desc'      => '查看位置：迅虎后台面板-支付渠道管理-我的支付渠道',
'placeholder' => '201906120111'
),

array(
'id'         => 'jinsom_xunhu_appsecret',
'type'       => 'text',
'title'      => '迅虎AppSecret',
'desc'      => '查看位置：迅虎后台面板-支付渠道管理-我的支付渠道',
),

array(
'id'         => 'jinsom_xunhu_api',
'type'       => 'text',
'title'      => '迅虎支付网关',
'subtitle'      => '默认不需要修改，如果使用托管需要修改',
'default'      => 'https://api.xunhupay.com/payment/do.html',
),


),
),


array(
'title'  => '易支付',
'fields' => array(

array(
'id'         => 'jinsom_epay_pid',
'type'       => 'text',
'title'      => '商户ID',
),

array(
'id'         => 'jinsom_epay_key',
'type'       => 'text',
'title'      => '商户密钥',
),

array(
'id'         => 'jinsom_epay_api',
'type'       => 'text',
'title'      => '接口api',
'default'      => 'https://q.jinsom.cn',
),


),
),


array(
'title'  => '码支付',
'fields' => array(

array(
'id'         => 'jinsom_mapay_pid',
'type'       => 'text',
'title'      => '商户ID',
),

array(
'id'         => 'jinsom_mapay_key',
'type'       => 'text',
'title'      => '商户密钥',
),

array(
'id'         => 'jinsom_mapay_api',
'type'       => 'text',
'title'      => '接口api',
'subtitle'      => '必须以 / 结尾',
'default'      => 'https://q.jinsom.cn/',
),


),
),


),
),


array(
'type' => 'notice',
'style' => 'danger',
'content' => '以下信息已经失效，仅保留一个版本用于迁移数据，请把以下设置数据复制到上面！',
),

array(
'id'         => 'jinsom_alipay_h5_appid',
'type'       => 'text',
'title'      => '应用的APPID',
'desc'      => '时间年份开头的，不是PID。查看位置：账户中心->密钥管理->开放平台密钥-APPID',
'placeholder' => '2018070660500526'
),

array(
'id'         => 'jinsom_alipay_h5_public_key',
'type'       => 'textarea',
'title'      => '支付宝公钥',
'subtitle'      => '首先你自己通过<a href="https://docs.open.alipay.com/291/105971" target="_blank" style="color:#f00;text-decoration:underline;">RSA签名验签工具</a>生成一个公钥和私钥，然后把生成的公钥填在支付宝后台可以获得<font style="color:#f00">支付宝公钥</font>，然后把<font style="color:#f00">支付宝公钥</font>填在这里就行了',
'desc'      => '查看位置：账户中心->密钥管理->开放平台密钥-设置/查看-支付宝公钥',
),


array(
'id'         => 'jinsom_alipay_h5_private_key',
'type'       => 'textarea',
'title'      => '私钥',
'subtitle'      => '通过<a href="https://docs.open.alipay.com/291/105971" target="_blank" style="color:#f00;text-decoration:underline;">RSA签名验签工具</a>生成的私钥',
'desc'      => '填写对应签名算法类型的私钥，如何生成密钥参考：<a href="https://docs.open.alipay.com/291/105971" target="_blank">https://docs.open.alipay.com/291/105971</a>和<a href="https://docs.open.alipay.com/200/105310" target="_blank">https://docs.open.alipay.com/200/105310</a>',
),



array(
'type' => 'notice',
'style' => 'danger',
'content' => '
微信支付全家桶（电脑端扫码(Native)，手机端H5支付，公众号支付(JSAPI)），电脑端扫码需要1，2，4项信息。手机端H5需要全部四项信息。公众号支付也需要下面四项信息。</br>
微信H5支付指的是在移动端普通浏览器发起的支付，而公众号支付是指的在移动端微信里面发起的支付。</br>
微信各种支付申请接入条件和流程：<a href="http://kf.qq.com/product/wechatpaymentmerchant.html#hid=339" target="_blank">http://kf.qq.com/product/wechatpaymentmerchant.html#hid=339</a>
',
),

array(
'id'         => 'jinsom_wechatpay_mchid',
'type'       => 'text',
'title'      => '微信支付商户号（PartnerID）',
'desc'       => '通过微信支付商户资料审核后邮件发送',
'placeholder' => '1511612451'
),

array(
'id'         => 'jinsom_wechatpay_appid',
'type'       => 'text',
'title'      => '公众号APPID',
'desc'       => '通过微信支付商户资料审核后邮件发送',
'placeholder' => 'wx7bef2*******589'
),

array(
'id'         => 'jinsom_wechatpay_appkey',
'type'       => 'text',
'title'      => '公众号AppSecret',
'desc'       => '微信支付申请对应的公众号的APP Key',
),

array(
'id'         => 'jinsom_wechatpay_apiKey',
'type'       => 'text',
'title'      => '微信商户支付密钥',
'desc'       => '帐户设置-安全设置-API安全-API密钥-设置API密钥',
),


array(
'type' => 'notice',
'style' => 'danger',
'content' => '迅虎微信支付（正规微信官方接口，支持个人申请，支持移动端和电脑端），【迅虎支付名称请到多语言包里面修改】',
),


array(
'id'         => 'jinsom_xunhu_appid',
'type'       => 'text',
'title'      => '迅虎APPID',
'subtitle'   => '<a href="https://admin.xunhupay.com/sign-up/6382.html" target="_blank" style="color:#f00;">请点击这里申请：https://admin.xunhupay.com/sign-up/6382.html</a>',
'desc'      => '查看位置：迅虎后台面板-支付渠道管理-我的支付渠道',
'placeholder' => '201906120111'
),

array(
'id'         => 'jinsom_xunhu_appsecret',
'type'       => 'text',
'title'      => '迅虎AppSecret',
'desc'      => '查看位置：迅虎后台面板-支付渠道管理-我的支付渠道',
),

array(
'id'         => 'jinsom_xunhu_api',
'type'       => 'text',
'title'      => '迅虎支付网关',
'subtitle'      => '默认不需要修改，如果使用托管需要修改',
'default'      => 'https://api.xunhupay.com/payment/do.html',
),


)
));


//我的钱包-金币充值
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_wallet',
'title'       => '<span>金币充值</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'id' => 'jinsom_recharge_number_add',
'type' => 'group',
'title' => '金币充值套餐',
'subtitle' => '就是充值界面，快捷充值的设定套餐，每个套餐的价格不要设置一样。',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'number',
'type'       => 'spinner',
'unit'       => '金币',
'title' => '金币数量',
),

array(
'id' => 'price',
'type'       => 'spinner',
'unit'       => '元',
'title' => '金币售价',
),


)
) ,


array(
'id' => 'jinsom_recharge_credit_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端充值金币顶部自定义',
'subtitle' => '支持html和短代码',
),


array(
'id' => 'jinsom_mobile_recharge_footer_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '移动端充值金币底部自定义',
'subtitle' => '支持html和短代码',
),


)
));



//我的钱包-开通会员
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_wallet',
'title'       => '<span>VIP 会员</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '如需要配置会员等级和会员成长值 请到基础设置-会员设置里面进行配置 <a href="#tab=4"><font style="color:#f00;margin-left:10px;">快速传送门</font></a>',
),



array(
'id' => 'jinsom_recharge_vip_add',
'type' => 'group',
'title' => 'VIP会员套餐',
'subtitle' => '就是开通会员界面，快捷开通的设定，一般为3个月，6个月，12个月',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'time',
'type'       => 'spinner',
'unit'       => '月',
'title' => '会员时长',
'subtitle' => '填1就是代表一个月，填6就是六个月，<font style="color:#f00;">如果需要永久会员，那么填大于120的数字</font>',
),

array(
'id' => 'credit_price',
'type'       => 'spinner',
'unit'       => '金币',
'title' => '金币售价',
'subtitle' => '如果开启了金币支付,则使用此售价',
),

array(
'id' => 'rmb_price',
'type'       => 'spinner',
'unit'       => '元',
'title' => '人民币售价',
'subtitle' => '如果开启了微信支付或者支付宝支付,则使用此售价',
),


)
) ,



array(
'id'         => 'jinsom_vip_number_month',
'type'       => 'spinner',
'unit'       => '成长值',
'step'     => 10,
'default'  => 100,
'title'      => '每开通一个月会员可获得成长值',
'subtitle'       => '如果不赠送成长值则输入0',
),


array(
'id' => 'jinsom_recharge_vip_header_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端开通会员顶部自定义',
'subtitle' => '支持html和短代码',
),

array(
'id' => 'jinsom_mobile_recharge_vip_footer_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '移动端开通会员底部自定义',
'subtitle' => '支持html和短代码',
),


)
));


//支付充值-卡密兑换
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_wallet',
'title'       => '<span>卡密兑换</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'id'         => 'jinsom_pay_key_placeholder',
'type'       => 'text',
'title'      => '卡密兑换输入框提示语',
'default'      => '请输入32位的卡密',
),

array(
'id'         => 'jinsom_pay_key_btn_text',
'type'       => 'text',
'title'      => '卡密兑换按钮名称',
'default'      => '卡密兑换',
),

array(
'id' => 'jinsom_key_recharge_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端卡密兑换自定义',
'subtitle' => '支持html和短代码，一般作为卡密充值提示之类的东西。引导用户。',
'default' => '<a href="#" target="_blank" style="margin-top: 10px;text-align: center;font-size: 14px;color: #999;text-decoration: underline;display: block;">如何获取卡密？快速点击这里查看</a>'
),

array(
'id' => 'jinsom_mobile_key_recharge_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '移动端卡密兑换底部自定义',
'subtitle' => '支持html和短代码，一般作为卡密充值提示之类的东西。引导用户。',
),


)
));


//支付充值-转账设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_wallet',
'title'       => '<span>转账设置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'id' => 'jinsom_transfer_on_off',
'type' => 'switcher',
'default' => false,
'title' => '转账功能',
),


array(
'id'                 => 'jinsom_transfer_power',
'type'               => 'radio',
'title'              => '转账功能使用权限',
'subtitle'      => '就是允许哪些人使用转账功能。',
'dependency' => array('jinsom_transfer_on_off','==','true') ,
'options'            => array(
'login'              => '所有登录用户',
'vip'         => 'VIP用户',
'verify'           => '认证用户',
'exp'             => '指定经验值',
),
'default'       =>'login',
),

array(
'id'      => 'jinsom_transfer_exp',
'type'       => 'spinner',
'unit'       => '经验值',
'title'   => '转账权限功能_指定经验值',
'dependency' => array('jinsom_transfer_power|jinsom_transfer_on_off','==|==','exp|true') ,
'default' => 100,
'desc'      => '默认100经验值，用户经验值需要大于或等于这个值才能使用转账功能',
),


array(
'id'      => 'jinsom_transfer_mini',
'type'       => 'spinner',
'unit'       => '金币',
'title'   => '转账最低金额',
'dependency' => array('jinsom_transfer_on_off','==','true') ,
'default' => 100,
),

array(
'id'      => 'jinsom_transfer_max',
'type'       => 'spinner',
'unit'       => '金币',
'title'   => '转账最高金额',
'dependency' => array('jinsom_transfer_on_off','==','true') ,
'default' => 10000,
),

array(
'id'      => 'jinsom_transfer_poundage',
'type'       => 'spinner',
'unit'       => '%',
'title'   => '转账手续费/笔',
'dependency' => array('jinsom_transfer_on_off','==','true') ,
'default' => 1,
'desc'      => '默认1%，根据转账的金额*转账手续费百分百得出的金额，如果含有小数则默认取整，如果数值小于转账最低手续费。则按最低手续费扣取。',
),


array(
'id'      => 'jinsom_transfer_poundage_mini',
'type'       => 'spinner',
'unit'       => '金币',
'title'   => '每笔手续费最低费用',
'dependency' => array('jinsom_transfer_on_off','==','true') ,
'default' => 10,
'desc'      => '就是转账的手续费最低是多少金币，默认10金币，',
),

)
));


//我的钱包-提现设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_wallet',
'title'       => '<span>提现设置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'id' => 'jinsom_cash_on_off',
'type' => 'switcher',
'default' => false,
'title' => '提现功能',
),



array(
'id'      => 'jinsom_cash_ratio',
'type'       => 'spinner',
'title'   => '提现比例',
'dependency' => array('jinsom_cash_on_off','==','true') ,
'default' => 10000,
'desc'      => '默认10000：1，也就是说10000虚拟币（金币、钻石、水晶、点券）可以提现1元人民币',
),

array(
'id'      => 'jinsom_cash_mini_number',
'type'       => 'spinner',
'unit'       => '金币',
'title'   => '最小提现金额',
'dependency' => array('jinsom_cash_on_off','==','true') ,
'default' => 5,
),

array(
'id'                 => 'jinsom_cash_power',
'type'               => 'radio',
'title'              => '提现功能使用权限',
'subtitle'      => '就是允许哪些人使用提现功能。',
'dependency' => array('jinsom_cash_on_off','==','true') ,
'options'            => array(
'login'              => '所有登录用户',
'vip'         => 'VIP用户',
'verify'           => '认证用户',
'exp'             => '指定经验值(等级)',
),
'default'       =>'login',
),

array(
'id'      => 'jinsom_cash_exp',
'type'       => 'spinner',
'unit'       => '经验值',
'title'   => '提现权限功能_指定经验值',
'dependency' => array('jinsom_cash_power|jinsom_cash_on_off','==|==','exp|true') ,
'default' => 100,
'desc'      => '默认100经验值，用户经验值需要大于或等于这个值才能使用提现功能',
),


array(
'id'      => 'jinsom_cash_poundage',
'type'       => 'spinner',
'unit'       => '%',
'title'   => '提现手续费/笔',
'dependency' => array('jinsom_cash_on_off','==','true') ,
'default' => 0,
),

array(
'id' => 'jinsom_cash_header_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '电脑端提现顶部自定义',
'subtitle' => '支持html和短代码，一般作为开通提示之类的东西。引导用户。',
'default'=>'<div style="font-size: 12px;color: #f00;text-align: center;">提现手续费为xx%每笔，若提现失败，手续费则不退还</div>'
),


array(
'id' => 'jinsom_mobile_cash_footer_custom_html',
'type'  => 'code_editor',
'settings' => array(
'mode'   => 'htmlmixed',
),
'title' => '移动端提现底部自定义',
'subtitle' => '支持html和短代码，一般作为开通提示之类的东西。引导用户。',
),


)
));








//储存设置
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_upload',
'title'  => '<span>'.__('储存设置','jinsom').'</span>',
'icon'   => 'fa fa-cloud-upload',
));


//储存设置-储存方式
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_upload',
'title'       => '<span>储存方式</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '使用第三方对象储存请务必点下载上传对应的SDK文件，<a href="https://q.jinsom.cn/36888.html" target="_blank" style="color:#f00;">《配置教程》</a>',
'dependency' => array('jinsom_upload_style','not-any','local,ftp'),
),


array(
'id'                 => 'jinsom_upload_style',
'type'               => 'select',
'title'              => '选择网站文件储存方式',
'options'            => array(
'local'              => '本地服务器',
'aliyun_oss'         => '阿里云oss',
'cos'           => '腾讯云cos',
'qiniu'           => '七牛云kodo',
'upyun'           => '又拍云upyun',
'obs'           => '华为云obs',
'bos'           => '百度云bos',
'ftp'           => '远程FTP',
'custom'           => '自定义储存类型',
),
'default'       =>'local',
),


array(
'id'         => 'jinsom_upload_custom_obj_require_url',
'type'       => 'text',
'title'      => '自定义储存文件加载地址',
'subtitle'      =>'仅用于二次开发/定制服务，如果不懂请不要乱配置，支持短代码',
'placeholder'=>'[link type=require_home]/page/xxx.php',
'desc'=>'[link type=require_home]',
'dependency' => array('jinsom_upload_style','==','custom'),
),


array(
'id'         => 'jinsom_upload_aliyun_oss_url',
'type'       => 'text',
'title'      => '阿里云地域节点(EndPoint)',
'dependency' => array('jinsom_upload_style','==','aliyun_oss'),
'desc'       => '<font style="color:#f00;">不是Bucket域名，如果你网站是https请加上https:// 反之用http://</font>',
'placeholder' => 'https://oss-cn-qingdao.aliyuncs.com'
),


array(
'id'         => 'jinsom_upload_aliyun_oss_bucket',
'type'       => 'text',
'title'      => '阿里云Bucket名称',
'dependency' => array('jinsom_upload_style','==','aliyun_oss'),
),  

array(
'id'         => 'jinsom_upload_aliyun_oss_domain',
'type'       => 'text',
'title'      => 'oss自定义域名',
'subtitle'      => '别漏了http',
'placeholder'      => 'http://',
'dependency' => array('jinsom_upload_style','==','aliyun_oss'),
'desc'       => '<font style="color:#f00;">如果使用oss默认域名，则这里一定要留空，不用填！</font>',
),


//远程FTP
array(
'id'         => 'jinsom_upload_ftp_server',
'type'       => 'text',
'title'      => '远程FTP地址（IP）',
'subtitle'       => '直接填IP地址，不需要填写端口',
'placeholder'=>'127.0.0.1',
'dependency' => array('jinsom_upload_style','==','ftp'),
),

array(
'id'         => 'jinsom_upload_ftp_username',
'type'       => 'text',
'title'      => '远程FTP帐号',
'dependency' => array('jinsom_upload_style','==','ftp'),
),

array(
'id'         => 'jinsom_upload_ftp_password',
'type'       => 'text',
'title'      => '远程FTP密码',
'dependency' => array('jinsom_upload_style','==','ftp'),
),  

array(
'id'         => 'jinsom_upload_ftp_domain',
'type'       => 'text',
'title'      => '远程FTP域名',
'subtitle'       => '注意区分https://和http://',
'dependency' => array('jinsom_upload_style','==','ftp'),
'placeholder' => 'https://img.jinsom.cn'
),


//七牛
array(
'id'         => 'jinsom_upload_qiniu_url',
'type'       => 'text',
'title'      => '七牛云域名',
'subtitle'       => '如使用https需要在七牛上传证书',
'dependency' => array('jinsom_upload_style','==','qiniu'),
'placeholder' => 'http://或https://'
),

array(
'id'         => 'jinsom_upload_qiniu_id',
'type'       => 'text',
'title'      => '七牛云AccessKey',
'dependency' => array('jinsom_upload_style','==','qiniu'),
),

array(
'id'         => 'jinsom_upload_qiniu_key',
'type'       => 'text',
'title'      => '七牛云SecretKey',
'dependency' => array('jinsom_upload_style','==','qiniu'),
),

array(
'id'         => 'jinsom_upload_qiniu_bucket',
'type'       => 'text',
'title'      => '七牛云空间名称',
'dependency' => array('jinsom_upload_style','==','qiniu'),
),  

//又拍云
array(
'id'         => 'jinsom_upload_upyun_domain',
'type'       => 'text',
'title'      => '又拍云域名',
'dependency' => array('jinsom_upload_style','==','upyun'),
'placeholder' => 'http://或https://'
),

array(
'id'         => 'jinsom_upload_upyun_id',
'type'       => 'text',
'title'      => '又拍云操作员名称',
'dependency' => array('jinsom_upload_style','==','upyun'),
),

array(
'id'         => 'jinsom_upload_upyun_key',
'type'       => 'text',
'title'      => '又拍云操作员密码',
'dependency' => array('jinsom_upload_style','==','upyun'),
),

array(
'id'         => 'jinsom_upload_upyun_bucket',
'type'       => 'text',
'title'      => '又拍云服务名称',
'dependency' => array('jinsom_upload_style','==','upyun'),
), 
   
//华为云
array(
'id'         => 'jinsom_upload_obs_endpoint',
'type'       => 'text',
'title'      => '华为云地域节点(EndPoint)',
'dependency' => array('jinsom_upload_style','==','obs'),
'placeholder' => 'http://或https://'
),

array(
'id'         => 'jinsom_upload_obs_id',
'type'       => 'text',
'title'      => '华为云SecretId',
'dependency' => array('jinsom_upload_style','==','obs'),
),

array(
'id'         => 'jinsom_upload_obs_key',
'type'       => 'text',
'title'      => '华为云SecretKey',
'dependency' => array('jinsom_upload_style','==','obs'),
),

array(
'id'         => 'jinsom_upload_obs_bucket',
'type'       => 'text',
'title'      => '华为云储存桶名称',
'dependency' => array('jinsom_upload_style','==','obs'),
), 

array(
'id'         => 'jinsom_upload_obs_domain',
'type'       => 'text',
'title'      => '华为云obs自定义域名',
'subtitle'      =>'需要填写http',
'placeholder'      =>'留空则使用默认域名',
'dependency' => array('jinsom_upload_style','==','obs'),
), 

//百度云bos
array(
'id'         => 'jinsom_upload_bos_endpoint',
'type'       => 'text',
'title'      => '百度云地域节点(EndPoint)',
'subtitle'      => '注意区分http或https',
'desc'=>'<font style="color:#f00;">如果自带的域名是：http://lightsns.bj.bcebos.com，那么地域节点是：http://bj.bcebos.com</font>',
'dependency' => array('jinsom_upload_style','==','bos'),
'placeholder' => 'http://bj.bcebos.com'
),

array(
'id'         => 'jinsom_upload_bos_id',
'type'       => 'text',
'title'      => '百度云SecretId',
'dependency' => array('jinsom_upload_style','==','bos'),
),

array(
'id'         => 'jinsom_upload_bos_key',
'type'       => 'text',
'title'      => '百度云SecretKey',
'dependency' => array('jinsom_upload_style','==','bos'),
),

array(
'id'         => 'jinsom_upload_bos_bucket',
'type'       => 'text',
'title'      => '百度云储存桶名称',
'dependency' => array('jinsom_upload_style','==','bos'),
), 

array(
'id'         => 'jinsom_upload_bos_domain',
'type'       => 'text',
'title'      => '百度云obs域名',
'subtitle'      =>'需要填写http，不能留空，请填写默认的域名或自定义的',
'dependency' => array('jinsom_upload_style','==','bos'),
), 



//cos
array(
'id'         => 'jinsom_upload_cos_bucket',
'type'       => 'text',
'title'      => '存储桶名称',
'subtitle'      => '格式：BucketName-APPID',
'placeholder'      => 'lightsns-1251666440',
'dependency' => array('jinsom_upload_style','==','cos'),
), 

array(
'id'         => 'jinsom_upload_cos_region',
'type'       => 'text',
'title'      => '储存桶所属地域',
'placeholder'      => 'ap-shanghai',
'dependency' => array('jinsom_upload_style','==','cos'),
),            

           

array(
'id'         => 'jinsom_upload_cos_domain',
'type'       => 'text',
'title'      => 'cos自定义域名',
'subtitle'      => '需要填写https://',
'dependency' => array('jinsom_upload_style','==','cos'),
'desc'       => '<font style="color:#f00;">如果使用默认域名，则这里一定要留空，不用填！</font>',
),


)
));


//储存设置-格式大小
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_upload',
'title'       => '<span>格式大小</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(


array(
'id'=>'jinsom_mobile_gif_size_max',
'type'=>'spinner',
'unit'=>'MB',
'default'=> 1,
'title'=> '移动端gif动态图片上传最大的限制',
'subtitle'=> '默认1MB，移动端评论回复和发表动态、文章允许上传的gif图片的最大限制',
),

array(
'type' => 'notice',
'style' => 'success',
'content' => '附件的上传格式和大小设置（文章、论坛、）',
) ,

array(
'id'         => 'jinsom_upload_file_max',
'type'       => 'spinner',
'unit'       => 'M',
'default'    => 10,
'title'      => '附件允许上传的最大值',
'subtitle'       => '发表内容插入附件最大上传的文件值',
),

array(
'id'         => 'jinsom_upload_file_type',
'type'       => 'text',
'default'    => "jpg,png,gif,zip,mp3,mp4,wav,txt,doc,xls,pdf",
'title'      => '附件允许上传的格式',
'desc'       => '默认支持：jpg,png,gif,zip,mp3,mp4,wav,txt,doc,xls,pdf',
),

array(
'type' => 'notice',
'style' => 'success',
'content' => '发表动态，图片上传格式和大小设置',
) ,

array(
'id'         => 'jinsom_upload_publish_words_max',
'type'       => 'spinner',
'unit'       => 'M',
'default'    => 2,
'title'      => '动态模块图片上传大小',
'subtitle'       => '发表动态的时候最大上传的文件值',
),

array(
'id'         => 'jinsom_upload_publish_words_type',
'type'       => 'text',
'default'    => "jpg,png,gif,jpeg",
'title'      => '动态模块图片上传的格式',
'desc'       => '默认支持：jpg,png,gif',
),


array(
'type' => 'notice',
'style' => 'success',
'content' => '发表音乐，音乐文件上传格式和大小设置',
) ,
array(
'id'         => 'jinsom_upload_publish_music_max',
'type'       => 'spinner',
'unit'       => 'M',
'default'    => 10,
'title'      => '音乐模块音乐上传大小',
'subtitle'       => '发表音乐的时候，音乐文件最大上传的文件值,默认10M',
),

array(
'id'         => 'jinsom_upload_publish_music_type',
'type'       => 'text',
'default'    => "mp3,wav",
'title'      => '音乐模块音乐上传的文件格式',
'desc'       => '默认支持：mp3,wav，多个后缀务必英文逗号隔开',
),


array(
'type' => 'notice',
'style' => 'success',
'content' => '发表视频，视频文件上传格式和大小设置',
) ,
array(
'id'         => 'jinsom_upload_publish_video_max',
'type'       => 'spinner',
'unit'       => 'M',
'default'    => 50,
'title'      => '视频模块上传大小',
'subtitle'       => '发表视频的时候，视频文件最大上传的文件值,默认50M',
),

array(
'id'         => 'jinsom_upload_publish_video_type',
'type'       => 'text',
'default'    => "mp4,m3u8,flv,mov,MOV,MP4,FLV",
'title'      => '视频模块上传的文件格式',
'subtitle'       => '视频播放器仅支持mp4,m3u8,flv,mov格式，并不是你填什么格式就支持什么格式，是你只能从这四个格式里面去选。',
),



array(
'type' => 'notice',
'style' => 'success',
'content' => '文章封面的上传格式和大小设置',
) ,
array(
'id'         => 'jinsom_upload_publish_single_img_max',
'type'       => 'spinner',
'unit'       => 'M',
'default'    => 1,
'title'      => '文章封面图的上传大小',
'subtitle'       => '文章封面图的最大上传的文件值,默认1M',
),

array(
'id'         => 'jinsom_upload_publish_single_img_type',
'type'       => 'text',
'default'    => "jpg,png,gif,jpeg",
'title'      => '文章封面的上传的文件格式',
'desc'       => '默认支持：jpg,png,gif',
),



array(
'type' => 'notice',
'style' => 'success',
'content' => 'IM聊天的图片上传格式和大小设置',
) ,
array(
'id'         => 'jinsom_upload_publish_im_img_max',
'type'       => 'spinner',
'unit'       => 'M',
'default'    => 2,
'title'      => 'IM聊天的图片上传大小',
'subtitle'       => 'IM聊天的图片的最大上传的文件值,默认2M',
),

array(
'id'         => 'jinsom_upload_publish_im_img_type',
'type'       => 'text',
'default'    => "jpg,png,gif,jpeg",
'title'      => 'IM聊天的图片上传的文件格式',
'desc'       => '默认支持：jpg,png,gif',
),


array(
'type' => 'notice',
'style' => 'success',
'content' => '论坛图片的上传格式和大小设置 <font style="color:#f00;">（包含编辑器截图上传）</font>',
) ,
array(
'id'         => 'jinsom_upload_publish_bbs_img_max',
'type'       => 'spinner',
'unit'       => 'M',
'default'    => 2,
'title'      => '论坛图片的上传大小',
'subtitle'       => '论坛图片的最大上传的文件值,默认2M',
),

array(
'id'         => 'jinsom_upload_publish_bbs_img_type',
'type'       => 'text',
'default'    => "jpg,png,gif,jpeg",
'title'      => '论坛图片的上传的文件格式',
'desc'       => '默认支持：jpg,png,gif',
),



array(
'type' => 'notice',
'style' => 'success',
'content' => '头像的上传格式和大小设置',
) ,
array(
'id'         => 'jinsom_upload_publish_avatar_max',
'type'       => 'spinner',
'unit'       => 'M',
'default'    => 2,
'title'      => '头像的上传大小',
'subtitle'       => '头像最大上传的文件值,默认2M',
),


array(
'id'         => 'jinsom_upload_publish_avatar_type',
'type'       => 'text',
'default'    => "jpg,png,gif,jpeg",
'title'      => '头像的上传的文件格式',
'desc'       => '默认支持：jpg,png,gif',
),



)
));


//储存设置-样式规则
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_upload',
'title'       => '<span>样式规则</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '请填写对应的对象储存的样式规则，如果不使用关闭即可。',
),

array(
'id'                 => 'jinsom_upload_obj_style',
'type'               => 'switcher',
'title'              => '样式规则',
'subtitle'              => '只针对第三方对象储存，本地和远程ftp除外',
'default'       =>false,
),

array(
'id'         => 'jinsom_upload_style_oss_avatar',
'type'       => 'text',
'title'      => '头像',
'subtitle'   => '只针对手动上传的头像',
'dependency' => array('jinsom_upload_obj_style','==',true),
),

array(
'id'         => 'jinsom_upload_style_oss_words',
'type'       => 'text',
'title'      => '动态图片',
'dependency' => array('jinsom_upload_obj_style','==',true),
),

array(
'id'         => 'jinsom_upload_style_oss_words_thum',
'type'       => 'text',
'title'      => '动态缩略图',
'dependency' => array('jinsom_upload_obj_style','==',true),
),

array(
'id'         => 'jinsom_upload_style_oss_bbs_single_list',
'type'       => 'text',
'title'      => '文章/帖子列表封面图',
'subtitle'   => '移动端也使用这个规则',
'dependency' => array('jinsom_upload_obj_style','==',true),
),

array(
'id'         => 'jinsom_upload_style_oss_bbs_single_content',
'type'       => 'text',
'title'      => '文章/帖子内容页原图',
'subtitle'   => '移动端也使用这个规则',
'dependency' => array('jinsom_upload_obj_style','==',true),
),

array(
'id'         => 'jinsom_upload_style_oss_bbs_single_content_thum',
'type'       => 'text',
'title'      => '文章/帖子内容页缩略图',
'subtitle'   => '移动端也使用这个规则',
'dependency' => array('jinsom_upload_obj_style','==',true),
),

array(
'id'         => 'jinsom_upload_style_oss_bbs_list',
'type'       => 'text',
'title'      => '论坛网格/瀑布流列表封面',
'subtitle'   => '移动端也使用这个规则',
'dependency' => array('jinsom_upload_obj_style','==',true),
),

array(
'id'         => 'jinsom_upload_style_colect_list',
'type'       => 'text',
'title'      => '收藏的图片的封面',
'subtitle'   => '移动端也使用这个规则',
'dependency' => array('jinsom_upload_obj_style','==',true),
),



)
));


//储存设置-图片压缩
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_upload',
'title'       => '<span>图片压缩</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '图片压缩功能目前仅仅针对移动端的图片上传（头像除外）',
),

array(
'type' => 'notice',
'style' => 'danger',
'content' => '因为移动端的图片都偏大，如果不是特殊需求，不建议修改，请保留默认。',
),

array(
'id'    => 'jinsom_mobile_publish_img_quality',
'type'  => 'slider',
'title' => '发布上传图片（动态/文章/帖子）',
'subtitle' => '数值越低，图片越模糊，默认值是0.7',
'default'=>0.7,
'step'=>0.1,
'min'=>0.1,
'max'=>1,
'unit'=>'压缩值',
),

array(
'id'    => 'jinsom_mobile_comment_img_quality',
'type'  => 'slider',
'title' => '评论回复图片',
'subtitle' => '数值越低，图片越模糊，默认值是0.7',
'default'=>0.7,
'step'=>0.1,
'min'=>0.1,
'max'=>1,
'unit'=>'压缩值',
),

)
));


//登录注册
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_login',
'title'  => '<span>登录注册</span>',
'icon'   => 'fa fa-qq',
));


//登录注册-登录设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_login',
'title'       => '<span>基本设置<n>新</n></span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(


array(
'id' => 'jinsom_login_on_off',
'type' => 'switcher',
'default' => false,
'title' => '强制登录',
'subtitle'   => '开启之后，用户需要登录之后才可以访问网站',
),

array(
'id'      => 'jinsom_login_page_bg',
'type'    => 'upload',
'title'   => '电脑端登录内页背景图',
'dependency' => array('jinsom_login_on_off','==','true') ,
'subtitle'=>'建议尺寸：2560*1600，适应2k屏幕',
'placeholder' => 'https://'
),

array(
'id'      => 'jinsom_mobile_login_bg',
'type'    => 'upload',
'title'   => '移动端登录/注册背景图',
'subtitle'=>'尺寸就跟设置手机壁纸一样就行了',
'placeholder' => 'https://',
),


array(
'id'      => 'jinsom_login_placeholder',
'type'    => 'text',
'title'   => '<i></i>登录输入框提示语',
'default'=>'邮箱/手机号',
'placeholder' => '邮箱/手机号'
),


array(
'id' => 'jinsom_login_add',
'type' => 'group',
'title' => '登录选项添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '选项名称',
),

array(
'id' => 'jinsom_login_add_type',
'type' => 'select',
'title' => '登录类型',
'options'      => array(
'username' => '密码登录(使用邮箱/手机号/用户名登录)',
'phone' => '手机号快捷登录',
'qq' => 'QQ登录',
'wechat_code' => '微信扫码登录(电脑端显示)',
// 'wechat_follow' => '微信关注公众号登录(电脑端显示)',
'wechat_mp' => '微信公众号登录(微信端显示)',
'weibo' => '微博登录',
'github' => 'Github登录',
'alipay' => '支付宝登录',
'password' => '忘记密码',
'custom' => '自定义登录',
),
),


array(
'id'         => 'custom',
'type'       => 'text',
'title'      => '自定义登录链接',
'dependency' => array('jinsom_login_add_type','==','custom'),
'subtitle' =>'支持短代码，https://或者javascript:xxxxx()'
),

array(
'id' => 'icon',
'type' => 'textarea',
'title' => '图标代码',
'placeholder'=>'留空则使用程序自带的图标',
'subtitle' => '可使用程序内置图标或自定义图标，<a href="https://q.jinsom.cn/iconfont" target="_blank" style="color:#f00;">《内置图标参考》</a>',
),

array(
'id' => 'color',
'type' => 'color',
'title' => '图标颜色',
'default'=>'#47ae69'
),

array(
'id' => 'in_pc',
'type' => 'switcher',
'default' => false,
'title' => '只在电脑端展示',
'dependency' => array('in_mobile','==','false'),
),

array(
'id' => 'in_mobile',
'type' => 'switcher',
'default' => false,
'title' => '只在移动端展示',
'dependency' => array('in_pc','==','false'),
),


)
),



array(
'id' => 'jinsom_reg_add',
'type' => 'group',
'title' => '注册选项添加',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'name',
'type' => 'text',
'title' => '选项名称',
),

array(
'id' => 'jinsom_reg_add_type',
'type' => 'select',
'title' => '注册类型',
'options'      => array(
'simple' => '简单注册(只需要填昵称/密码)',
'email' => '邮箱注册',
'phone' => '手机号注册',
'invite' => '邀请码注册',
'qq' => 'QQ注册',
'wechat_code' => '微信扫码注册(电脑端显示)',
// 'wechat_follow' => '微信关注公众号注册(电脑端显示)',
'wechat_mp' => '微信公众号注册(微信端显示)',
'weibo' => '微博注册',
'github' => 'Github注册',
'alipay' => '支付宝注册',
'password' => '忘记密码',
'custom' => '自定义注册',
),
),


array(
'id'         => 'custom',
'type'       => 'text',
'title'      => '自定义注册链接',
'dependency' => array('jinsom_reg_add_type','==','custom'),
'subtitle' =>'支持短代码，https://或者javascript:xxxxx()'
),

array(
'id' => 'icon',
'type' => 'textarea',
'title' => '图标代码',
'placeholder'=>'留空则使用程序自带的图标',
'subtitle' => '可使用程序内置图标或自定义图标，<a href="https://q.jinsom.cn/iconfont" target="_blank" style="color:#f00;">《内置图标参考》</a>',
),

array(
'id' => 'color',
'type' => 'color',
'title' => '图标颜色',
'default'=>'#47ae69'
),

array(
'id' => 'in_pc',
'type' => 'switcher',
'default' => false,
'title' => '只在电脑端展示',
'dependency' => array('in_mobile','==','false'),
),

array(
'id' => 'in_mobile',
'type' => 'switcher',
'default' => false,
'title' => '只在移动端展示',
'dependency' => array('in_pc','==','false'),
),


)
),



)
));

//登录注册-注册设置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_login',
'title'       => '<span>注册设置</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(



array(
'id' => 'jinsom_reg_email_code_on_off',
'type' => 'switcher',
'default' => true,
'title' => '邮箱注册需要验证码',
'desc'   => '此功能需要开启邮件服务，如果关闭之后，用户邮箱注册和绑定邮箱不需要验证码验证',
),


array(
'id'         => 'jinsom_reg_name_min',
'type'       => 'spinner',
'unit'       => '字',
'title'      => '注册昵称长度最少为',
'subtitle' => '一个数字、符号、文字分别占一个字',
'default' => 2,
),

array(
'id'         => 'jinsom_reg_name_max',
'type'       => 'spinner',
'unit'       => '字',
'title'      => '注册昵称长度最多为',
'subtitle' => '一个数字、符号、文字分别占一个字',
'default' => 12,
),

array(
'id'         => 'jinsom_reg_password_min',
'type'       => 'spinner',
'unit'       => '位',
'title'      => '注册密码长度最少为',
'subtitle' => '一个数字、符号、文字分别占一个字',
'default' => 6,
),

array(
'id'         => 'jinsom_reg_password_max',
'type'       => 'spinner',
'unit'       => '位',
'title'      => '注册密码长度最多为',
'subtitle' => '一个数字、符号、文字分别占一个字',
'default' => 20,
),



array(
'id' => 'jinsom_reg_doc_add',
'type' => 'group',
'title' => '注册条款添加',
'subtitle' => '如果开启了强制登录，注册条款的页面用户不需要登录也可以进行查看',
'button_title' => '添加',
'default'=>array(
array(
'name'=>'服务条款',
'url'=>''
),
array(
'name'=>'隐私政策',
'url'=>''
)
),
'fields' => array(

array(
'id' => 'name',
'type'   => 'text',
'title' => '条款名称',
),

array(
'id' => 'url',
'type'   => 'text',
'title' => '条款地址',
'placeholder' => 'https://',
),

)
) ,



array(
'id'         => 'jinsom_invite_code_get_url',
'type'       => 'text',
'title'      => '邀请码获取地址',
'desc'      => '支持短代码，点击那个“获取邀请码”按钮跳转的地址，建议使用短代码',
),



array(
'id' => 'jinsom_reg_notice',
'type' => 'textarea',
'title' => '用户注册之后收到的邮件消息',
'default' => '欢迎你加入！祝你生活愉快！',
'subtitle' => '邮件和通知栏消息都会提醒（不支持html）'
),


)
));


//登录注册-第三方配置
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_login',
'title'       => '<span>第三方配置</span>',
'icon'        => 'fa fa-wechat',
'fields'      => array(


array(
'type' => 'notice',
'style' => 'danger',
'content' => '如果网站使用了社交登录（QQ、微信、微博、github、支付宝），请务必下载上传登录模块的sdk到网站根目录，具体请看：https://q.jinsom.cn/36888.html',
),

array(
'id'    => 'jinsom_social_login_tab',
'type'  => 'tabbed',
'title' => '社交登录配置',
'subtitle' => '如果使用社交登录，请先上传SDK，点击这里<a href="https://q.jinsom.cn/36888.html" target="_blank" style="color:#f00;">《查看教程》</a>',
'tabs'  => array(

array(
'title'  => 'QQ登录',
'fields' => array(

array(
'type' => 'notice',
'style' => 'success',
'content' => '回调地址：'.home_url('/Extend/oauth/qq/index.php'),
),

array(
'id' => 'jinsom_qq_login_unionid_on_off',
'type' => 'switcher',
'default' => false,
'title' => 'unionid',
'subtitle' => '平台统一ID信息',
'desc' => '<font style="color:#f00;">【注意：】如果QQ互联上面没有获取绑定，请勿开启！！</font>',
),

array(
'id'         => 'jinsom_qq_login_appid',
'type'       => 'text',
'title'      => 'QQ APP ID',
'desc' =>'QQ互联申请地址:<a href="https://connect.qq.com/" target="_blank" style="color:#f00;">https://connect.qq.com</a>',
),

array(
'id'         => 'jinsom_qq_login_appkey',
'type'       => 'text',
'title'      => 'QQ APP Key',
),


),
),


array(
'title'  => '微博登录',
'fields' => array(

array(
'type' => 'notice',
'style' => 'success',
'content' => '回调地址：'.home_url('/Extend/oauth/weibo/index.php'),
),

array(
'id'         => 'jinsom_login_weibo_key',
'type'       => 'text',
'title'      => '微博 App Key',
'desc' =>'微博开放平台申请地址:<a href="http://open.weibo.com/" target="_blank" style="color:#f00;">http://open.weibo.com/</a>',
),

array(
'id'         => 'jinsom_login_weibo_secret',
'type'       => 'text',
'title'      => '微博 App Secret',
),


),
),


array(
'title'  => '微信(电脑扫码)',
'fields' => array(

array(
'type' => 'notice',
'style' => 'success',
'content' => '回调地址：'.home_url(),
),

array(
'id'         => 'jinsom_login_wechat_code_key',
'type'       => 'text',
'title'      => '微信 AppID',
'desc' =>'微信开放平台申请地址:<a href="https://open.weixin.qq.com" target="_blank" style="color:#f00;">https://open.weixin.qq.com</a>',
),

array(
'id'         => 'jinsom_login_wechat_code_secret',
'type'       => 'text',
'title'      => '微信 AppSecret',
),


),
),

array(
'title'  => '微信(公众号)',
'fields' => array(

array(
'type' => 'notice',
'style' => 'success',
'content' => '回调地址：'.home_url(),
),

array(
'id' => 'jinsom_wechat_mp_auto_login_on_off',
'type' => 'switcher',
'default' => false,
'title' => '自动登录',
'subtitle' => '在微信打开网站自动登录',
),

array(
'id'         => 'jinsom_login_wechat_mp_key',
'type'       => 'text',
'title'      => '微信 AppID',
'desc' =>'公众号平台申请地址:<a href="https://mp.weixin.qq.com/" target="_blank" style="color:#f00;">https://mp.weixin.qq.com/</a>',
),

array(
'id'         => 'jinsom_login_wechat_mp_secret',
'type'       => 'text',
'title'      => '微信 AppSecret',
),


),
),


array(
'title'  => 'Github登录',
'fields' => array(

array(
'type' => 'notice',
'style' => 'success',
'content' => '回调地址：'.home_url('/Extend/oauth/github/index.php'),
),

array(
'id'         => 'jinsom_login_github_id',
'type'       => 'text',
'title'      => 'Github Client ID',
'desc' =>'申请地址:<a href="https://github.com/settings/developers" target="_blank" style="color:#f00;">https://github.com/settings/developers</a>',
),

array(
'id'         => 'jinsom_login_github_secret',
'type'       => 'text',
'title'      => 'Github Client secrets',
),


),
),

array(
'title'  => '支付宝登录',
'fields' => array(

array(
'type' => 'notice',
'style' => 'success',
'content' => '回调地址：'.home_url('/Extend/oauth/alipay/index.php'),
),

array(
'id'         => 'jinsom_login_alipay_appid',
'type'       => 'text',
'title'      => '支付宝应用APPID',
'desc' =>'申请地址:<a href="https://open.alipay.com/platform/home.htm" target="_blank" style="color:#f00;">https://open.alipay.com/platform/home.htm</a>',
),

array(
'id'         => 'jinsom_login_alipay_rsaKey',
'type'       => 'textarea',
'title'      => '支付宝应用私钥',
'subtitle'      => '自己使用工具生成的那个私钥',
),


),
),


),
),



)
));






//短信服务
LightSNS::createSection( $prefix, array(
'title'       => '<span>短信服务</span>',
'icon'        => 'fa fa-envelope',
'fields'      => array(

array(
'id'                 => 'jinsom_sms_style',
'type'               => 'radio',
'title'              => '短信服务',
'subtitle'              => '如果关闭则用户注册、修改手机号的时候不需要获取验证码',
'options'            => array(
'close'       =>'关闭',
'ali'              => '阿里云短信',
'tent'         => '腾讯云短信',
),
'default'       =>'close',
),

array(
'id' => 'jinsom_bind_phone_on_off',
'type' => 'switcher',
'default' => false,
'title' => '强制绑定手机号',
'dependency' => array('jinsom_sms_style','!=','close'),
'subtitle'   => '开启后，用户需绑定手机号才能浏览网站',
),

array(
'id'      => 'jinsom_bind_phone_use_for',
'type'    => 'checkbox',
'dependency' => array('jinsom_sms_style','!=','close'),
'title'=> '需要绑定手机号才能使用的功能',
'options'    => array(
'publish' => '发布内容',
'comment' => '评论回复',
'buy' =>'付费购买',
'reward' => '打赏',
'sign' => '签到',
'chat' =>'聊天',
'transfer' =>'转账',
'withdrawals' =>'提现',
'vote' =>'投票',
'activity' =>'活动报名',
),
),


array(
'id'         => 'jinsom_alidayu_sm_name',
'type'       => 'text',
'title'      => '短信签名名称（不是模版名称！）',
'dependency' => array('jinsom_sms_style','==','ali'),
'placeholder' => '轻社交'
),

array(
'id'         => 'jinsom_alidayu_sm_id',
'type'       => 'text',
'title'      => '短信模版ID',
'desc'       =>'这个模版是用于注册，获取的验证码模版ID',
'dependency' => array('jinsom_sms_style','==','ali'),
'placeholder' => 'SMS_44220021'
),

array(
'type'    => 'content',
'dependency' => array('jinsom_sms_style','==','ali'),
'content' => '阿里云短信模版格式：<font style="color:#f00;">您的手机验证码是：${code}，若非本人操作，请忽略！</font>（仅供参考，自己可以根据自身业务逻辑去改写）',
),


array(
'id'         => 'jinsom_tentsms_appid',
'type'       => 'text',
'title'      => '腾讯短信SDK AppID',
'dependency' => array('jinsom_sms_style','==','tent'),
'desc' =>'查看位置：腾讯短信后台-应用管理-应用列表-系统默认应用-SDK AppID',
'placeholder'=>'1400295665',
),

array(
'id'         => 'jinsom_tentsms_appkey',
'type'       => 'text',
'title'      => '腾讯短信App Key',
'dependency' => array('jinsom_sms_style','==','tent'),
'subtitle'      => '查看位置：腾讯短信后台-应用管理-应用列表-系统默认应用-App Key',
'placeholder'=>'a4e3efb*********3baeee43ec',
),

array(
'id'         => 'jinsom_tentsms_sign',
'type'       => 'text',
'title'      => '腾讯短信签名（注意：非签名ID）',
'dependency' => array('jinsom_sms_style','==','tent'),
'subtitle'      => '查看位置：腾讯短信后台-国内短信-签名管理-内容',
'placeholder'=>'LightSNS轻社交',
),

array(
'id'         => 'jinsom_tentsms_templid',
'type'       => 'text',
'title'      => '腾讯短信模版ID',
'dependency' => array('jinsom_sms_style','==','tent'),
'subtitle'      => '查看位置：腾讯短信后台-国内短信-正文模版管理-ID',
'placeholder'=>'494923',
),

array(
'type'    => 'content',
'dependency' => array('jinsom_sms_style','==','tent'),
'content' => '腾讯云短信模版格式：<font style="color:#f00;">你的验证码为：{1}，若非本人操作，请勿泄露。</font>（仅供参考，自己可以根据自身业务逻辑去改写）',
),

)
));


//邮件服务
LightSNS::createSection( $prefix, array(
'title'       => '<span>邮件服务<n>新</n></span>',
'icon'        => 'fa fa-envelope',
'fields'      => array(

array(
'id'                 => 'jinsom_email_style',
'type'               => 'radio',
'title'              => '邮件服务',
'desc'              => '关闭之后所有涉及邮箱的功能也相应关闭，比如邮箱提醒设置，邮箱账号绑定和修改，邮箱注册',
'options'            => array(
'close'       =>'关闭',
'smtp'              => 'SMTP服务',
'aliyun'         => '阿里云邮件推送',
),
'default'       =>'close',
),

array(
'id' => 'jinsom_bind_email_on_off',
'type' => 'switcher',
'default' => false,
'title' => '强制绑定邮箱',
'dependency' => array('jinsom_email_style','!=','close'),
'subtitle'   => '开启之，用户需绑定邮箱才能浏览网站',
),



array(
'id'      => 'jinsom_bind_email_use_for',
'type'    => 'checkbox',
'dependency' => array('jinsom_email_style','!=','close'),
'title'=> '需要绑定邮箱才能使用的功能',
'options'    => array(
'publish' => '发布内容',
'comment' => '评论回复',
'buy' =>'付费购买',
'reward' => '打赏',
'sign' => '签到',
'chat' =>'聊天',
'transfer' =>'转账',
'withdrawals' =>'提现',
'vote' =>'投票',
'activity' =>'活动报名',
),
),


array(
'id'         => 'jinsom_mail_notice_on_off',
'type'       => 'switcher',
'title'      => '消息提醒使用邮件通知',
'desc'      => '关闭后，当用户有互动消息的时候不会通过邮件提醒，但邮箱的验证码注册、修改密码不影响',
'dependency' => array('jinsom_email_style','!=','close'),
'default' => true
),


array(
'id'         => 'jinsom_mail_name',
'type'       => 'text',
'title'      => '发件人名称',
'dependency' => array('jinsom_email_style','!=','close'),
'placeholder' => '例如：jinsom'
),

array(
'id'         => 'jinsom_email_aliyun_from',
'type'       => 'text',
'title'      => '发信邮件地址',
'dependency' => array('jinsom_email_style','==','aliyun'),
'desc' => '在阿里云邮件推送控制台创建的发信地址，例如：jinsom@q.jinsom.cn',
),

array(
'id'         => 'jinsom_mail_host',
'type'       => 'text',
'title'      => 'SMTP 主机',
'dependency' => array('jinsom_email_style','==','smtp'),
'placeholder' => '例如：smtp.163.com'
),
array(
'id'     => 'jinsom_mail_smtpsecure',
'type'   => 'select',
'title'  => '加密类型',
'dependency' => array('jinsom_email_style','==','smtp'),
'options'=> array(
'none'=>'无',
'ssl'=>'SSL/TLS',
'tls'=>'STARTTLS',
),
'default'=>'none',
),
array(
'id'         => 'jinsom_mail_port',
'type'       => 'text',
'title'      => 'SMTP 端口',
'dependency' => array('jinsom_email_style','==','smtp'),
'placeholder' => '例如：80、465'
),
array(
'id'         => 'jinsom_mail_user',
'type'       => 'text',
'title'      => 'SMTP 用户名',
'dependency' => array('jinsom_email_style','==','smtp'),
'placeholder' => '例如：xxxxx@163.com'
),
array(
'id'         => 'jinsom_mail_password',
'type'       => 'text',
'title'      => 'SMTP 密码',
'dependency' => array('jinsom_email_style','==','smtp'),
'placeholder' => '例如：******',
'desc' => '国内一般用的是授权码'
),


)
));



//AccessKey
LightSNS::createSection( $prefix, array(
'title'       => '<span>AccessKey<k>重要</k></span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type' => 'notice',
'style' => 'danger',
'content' => '如果你使用了阿里云和腾讯云的某些功能服务，请在这里填写相关的AccessKey ID和AccessKey Secret',
),


array(
'type' => 'notice',
'style' => 'success',
'content' => '阿里云目前使用的服务：短信、对象储存oss、邮件推送',
),

array(
'id'         => 'jinsom_upload_aliyun_oss_id',
'type'       => 'text',
'title'      => '阿里云AccessKey ID',
'desc' => '查看方式：控制面板-右上角头像下拉菜单-AccessKey 管理，<font style="color:#F00;">如果使用子用户 AccessKey，记得给与对应功能的权限</font>',
),

array(
'id'         => 'jinsom_upload_aliyun_oss_key',
'type'       => 'text',
'title'      => '阿里云AccessKey Secret',
'desc' => '查看方式：控制面板-右上角头像下拉菜单-AccessKey 管理，<font style="color:#F00;">如果使用子用户 AccessKey，记得给与对应功能的权限</font>',
),


array(
'type' => 'notice',
'style' => 'success',
'content' => '腾讯云目前使用的服务：人机验证、对象储存cos',
),


array(
'id'         => 'jinsom_upload_cos_secretid',
'type'       => 'text',
'title'      => '腾讯云SecretId',
'placeholder'      => 'AKIDxiFXXKuEKG***7P9kLvHH5f182h8kH0U',
'subtitle' => '查看方式：腾讯云右上角用户名下拉菜单-访问管理-访问密钥-API密钥管理-SecretId',
), 


array(
'id'         => 'jinsom_upload_cos_secretkey',
'type'       => 'text',
'title'      => '腾讯云SecretKey',
'subtitle' => '查看方式：腾讯云右上角用户名下拉菜单-访问管理-访问密钥-API密钥管理-SecretKey',
), 



)
));


LightSNS::createSection( $prefix, array(
'title'       => '<span>其他功能</span>',
'icon'        => 'fa fa-cube',
'fields'      => array(

array(
'id'                 => 'jinsom_location_on_off',
'type'               => 'radio',
'title'              => '位置定位',
'subtitle' => '发表位置、个人资料左侧位置显示、资料卡片位置显示',
'options'            => array(
'no'              => '关闭',
'taobao'         => '淘宝定位接口（免费 100次/日）',
'baidu'         => '百度位置服务（免费 1000次/日）',
'qq'         => '腾讯位置服务（免费 10000次/日）<font style="color:#f00;">推荐</font>',
'gaode'         => '高德位置服务（免费 30万次/日）',
'ip138'           => 'ip138.com（付费）',
),
'default'       =>'no',
),

array(
'id'         => 'jinsom_qqlbs_key',
'type'=>'text',
'title'      => '腾讯位置服务Key',
'dependency' => array('jinsom_location_on_off','==','qq') ,
'placeholder' =>'4GABZ-DNZEG-****-****-GEITV-CIFVB',
'subtitle' => '请到<a href="https://lbs.qq.com/" target="_blank">https://lbs.qq.com/</a>申请接口',
'desc' => '如果不懂，请查看<a href="https://q.jinsom.cn/16795.html" target="_blank" style="color:#f00;">《腾讯位置服务配置教程》</a>申请接口'
),

array(
'id'         => 'jinsom_baidulbs_key',
'type'=>'text',
'title'      => '百度位置服务Key',
'dependency' => array('jinsom_location_on_off','==','baidu') ,
'placeholder' =>'6zY0RnSqIi3**********pGqh3YoZx',
'subtitle' => '请到<a href="http://lbsyun.baidu.com/apiconsole/key?application=key" target="_blank">http://lbsyun.baidu.com/apiconsole/key?application=key</a>申请接口',
'desc' => '如果不懂，请查看<a href="https://q.jinsom.cn/33531.html" target="_blank" style="color:#f00;">《百度位置服务配置教程》</a>申请接口'
),

array(
'id'         => 'jinsom_gaodelbs_key',
'type'=>'text',
'title'      => '高德位置服务Key',
'dependency' => array('jinsom_location_on_off','==','gaode') ,
'placeholder' =>'08cabc96510a*********149091f92c6',
'subtitle' => '请到<a href="https://console.amap.com/dev/key/app" target="_blank">https://console.amap.com/dev/key/app</a>申请接口',
),

array(
'id'         => 'jinsom_ip138_token',
'type'=>'text',
'title'      => '付费接口(ip138.com)_TOKEN',
'dependency' => array('jinsom_location_on_off','==','ip138') ,
'placeholder' =>'3d6520ca4be6******13a228a1f90186e',
'subtitle' => '请到<a href="http://user.ip138.com/ip/" target="_blank">http://user.ip138.com/ip/</a>申请接口'
),

array(
'id' => 'jinsom_instantclick_on_off',
'type' => 'switcher',
'default' => false,
'title' => '即时加载',
'subtitle' => '开启后网页加载速度会更快，目前还处于测试中，可能某些功能不能完全兼容',
),



)
));

//模块扩展
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_module',
'title'  => '<span>模块扩展</span>',
'icon'   => 'fa fa-asterisk',
));

//模块扩展-模块安装
LightSNS::createSection( $prefix, array(
'parent'      => 'jinsom_module',
'title'       => '<span>模块安装</span>',
'icon'        => 'fa fa-th-large',
'fields'      => array(

array(
'type'       => 'module',
),

)
));


//插件扩展设置
LightSNS::createSection($prefix,
array(
'id'    => 'jinsom_plugins',
'title'  => '<span>插件扩展</span>',
'icon'   => 'fa fa-puzzle-piece',
));




//面板设置
LightSNS::createSection( $prefix, array(
'title'       => '<span>面板设置</span>',
'icon'        => 'fa fa-star',
'fields'      => array(

array(
'id'                 => 'jinsom_panel_skin',
'type'               => 'radio',
'title'              => '面板风格',
'options'            => array(
'dark'              => '深色',
'light'           => '浅色',
),
'default'       =>'dark',
),


array(
'id'         => 'jinsom_panel_name',
'type'       => 'text',
'title'      => '设置面板logo名称',
'desc'      => '如果是中文，建议五个字内',
'default' => 'LightSNS'
),


array(
'id' => 'jinsom_panel_menu_add',
'type' => 'group',
'title' => '面板头部菜单',
'subtitle'      => '可以添加一些常用的网站链接在顶部方便日常使用',
'button_title' => '添加',
'fields' => array(

array(
'id' => 'title',
'type' => 'text',
'title' => '菜单名称',
),

array(
'id' => 'link',
'type' => 'text',
'title' => '菜单链接',
'placeholder' => 'http://',
),


),
'default' => array(
array(
'title' => '网站首页',
'link' => '/',
),
array(
'title' => 'LightSNS官网',
'link' => 'https://q.jinsom.cn/',
),
)
) ,


)
));




LightSNS::createSection( $prefix, array(
'title'       => '<span>设置备份<k>重要</k></span>',
'icon'        => 'fa fa-cloud',
'fields'      => array(

array(
'type' => 'backup',
),

)
));

LightSNS::createSection( $prefix, array(
'title'       => '<span>更新授权</span>',
'icon'        => 'fa fa-key',
'fields'      => array(

array(
'type'    => 'verify',
),


)
));

}else{
$prefix = 'jinsom_options';
LightSNS::createOptions($prefix);
LightSNS::createSection($prefix,array());
}
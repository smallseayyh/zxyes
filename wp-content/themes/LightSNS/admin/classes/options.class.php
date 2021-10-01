<?php 
//Theme URI:https:// q.jinsom .cn
if( ! class_exists( 'LightSNS_Options' ) ) {
class LightSNS_Options {


public $unique       = '';
public $notice       = '';
public $abstract     = 'options';
public $sections     = array();
public $options      = array();
public $errors       = array();
public $pre_tabs     = array();
public $pre_fields   = array();
public $pre_sections = array();


public $args         = array(


'framework_title' => 'LightSNS',
'framework_class' => '',
'menu_title'      => '主题配置',
'menu_slug'       => 'jinsom',
'menu_type'       => 'menu',
'menu_capability' => 'manage_options',
'menu_icon'       => null,
'menu_position'   => null,
'menu_hidden'     => false,
'menu_parent'     => '',

'show_bar_menu'      => false,
'show_sub_menu'      => false,
'show_network_menu'  => true,
'show_in_customizer' => false,

'show_search'        => false,
'show_reset_all'     => false,
'show_reset_section' => false,
'show_footer'        => true,
'show_all_options'   => true,
'sticky_header'      => true,
'save_defaults'      => true,


'admin_bar_menu_icon'     => 'fa fa-th-large',
'admin_bar_menu_priority' => 80,


'database'       => '',
'transient_time' => 0,


'contextual_help'         => array(),
'contextual_help_sidebar' => '',


);



public function __construct( $key, $params = array() ) {

$this->unique   = $key;
$this->args     = apply_filters( "csf_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
$this->sections = apply_filters( "csf_{$this->unique}_sections", $params['sections'], $this );


$this->pre_tabs     = $this->pre_tabs( $this->sections );
$this->pre_fields   = $this->pre_fields( $this->sections );
$this->pre_sections = $this->pre_sections( $this->sections );

$this->get_options();
$this->save_defaults();

add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
add_action( 'admin_bar_menu', array( &$this, 'add_admin_bar_menu' ), $this->args['admin_bar_menu_priority'] );

if( ! empty( $this->args['show_network_menu'] ) ) {
add_action( 'network_admin_menu', array( &$this, 'add_admin_menu' ) );
}


//parent::__construct();

}


public static function instance( $key, $params = array() ) {
return new self( $key, $params );
}

public function pre_tabs( $sections ) {

$result  = array();
$parents = array();
$count   = 100;

foreach( $sections as $key => $section ) {
if( ! empty( $section['parent'] ) ) {
$section['priority'] = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
$parents[$section['parent']][] = $section;
unset( $sections[$key] );
}
$count++;
}

foreach( $sections as $key => $section ) {
$section['priority'] = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
if( ! empty( $section['id'] ) && ! empty( $parents[$section['id']] ) ) {
$section['subs'] = wp_list_sort( $parents[$section['id']], array( 'priority' => 'ASC' ), 'ASC', true );
}
$result[] = $section;
$count++;
}

return wp_list_sort( $result, array( 'priority' => 'ASC' ), 'ASC', true );
}

public function pre_fields( $sections ) {

$result  = array();

foreach( $sections as $key => $section ) {
if( ! empty( $section['fields'] ) ) {
foreach( $section['fields'] as $field ) {
$result[] = $field;
}
}
}

return $result;
}

public function pre_sections( $sections ) {

$result = array();

foreach( $this->pre_tabs as $tab ) {
if( ! empty( $tab['subs'] ) ) {
foreach( $tab['subs'] as $sub ) {
$result[] = $sub;
}
}
if( empty( $tab['subs'] ) ) {
$result[] = $tab;
}
}

return $result;
}


public function add_admin_bar_menu( $wp_admin_bar ) {

if( ! empty( $this->args['show_bar_menu'] ) && empty( $this->args['menu_hidden'] ) ) {

global $submenu;

$menu_slug = $this->args['menu_slug'];
$menu_icon = ( ! empty( $this->args['admin_bar_menu_icon'] ) ) ? '<span class="jinsom-panel-ab-icon ab-icon '. $this->args['admin_bar_menu_icon'] .'"></span>' : '';

$wp_admin_bar->add_node( array(
'id'    => $menu_slug,
'title' => $menu_icon . $this->args['menu_title'],
'href'  => ( is_network_admin() ) ? network_admin_url( 'admin.php?page='. $menu_slug ) : admin_url( 'admin.php?page='. $menu_slug ),
) );

if( ! empty( $submenu[$menu_slug] ) ) {
foreach( $submenu[$menu_slug] as $key => $menu ) {
$wp_admin_bar->add_node( array(
'parent' => $menu_slug,
'id'     => $menu_slug .'-'. $key,
'title'  => $menu[0],
'href'   => ( is_network_admin() ) ? network_admin_url( 'admin.php?page='. $menu[2] ) : admin_url( 'admin.php?page='. $menu[2] ),
) );
}
}

if( ! empty( $this->args['show_network_menu'] ) ) {
$wp_admin_bar->add_node( array(
'parent' => 'network-admin',
'id'     => $menu_slug .'-network-admin',
'title'  => $menu_icon . $this->args['menu_title'],
'href'   => network_admin_url( 'admin.php?page='. $menu_slug ),
) );
}

}

}




//设置默认数据
public function save_defaults(){
$tmp_options = $this->options;
foreach( $this->pre_fields as $field ) {
if( ! empty( $field['id'] ) ) {
$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
$field_value   = ( isset( $this->options[$field['id']] ) ) ? $this->options[$field['id']] : $field_default;
$this->options[$field['id']] = $field_value;
}
}
if(empty( $tmp_options ) ) {
update_option($this->unique,$this->options);
}
}


//展示设置数据
public function get_options(){
$this->options=get_option($this->unique);
if(empty($this->options)){$this->options=array();}
return $this->options;
}




public function add_admin_menu() {

extract( $this->args );

if( $menu_type === 'submenu' ) {

$menu_page = call_user_func( 'add_submenu_page', $menu_parent, $menu_title, $menu_title, $menu_capability, $menu_slug, array( &$this, 'add_options_html' ) );

} else {

$menu_page = call_user_func( 'add_menu_page', $menu_title, $menu_title.' <span class="update-plugins count-1">核心</span>', $menu_capability, $menu_slug, array( &$this, 'add_options_html' ), $menu_icon, $menu_position );

if( ! empty( $this->args['show_sub_menu'] ) && count( $this->pre_tabs ) > 1 ) {


$tab_key = 1;
foreach ( $this->pre_tabs as $section ) {

call_user_func( 'add_submenu_page', $menu_slug, $section['title'],  $section['title'], $menu_capability, $menu_slug .'#tab='. $tab_key, '__return_null' );

if( ! empty( $section['subs'] ) ) {
$tab_key += ( count( $section['subs'] )-1 );
}

$tab_key++;

}

remove_submenu_page( $menu_slug, $menu_slug );

}

if( ! empty( $menu_hidden ) ) {
remove_menu_page( $menu_slug );
}

}

add_action( 'load-'. $menu_page, array( &$this, 'add_page_on_load' ) );

}

public function add_page_on_load() {

if( ! empty( $this->args['contextual_help'] ) ) {

$screen = get_current_screen();

foreach( $this->args['contextual_help'] as $tab ) {
$screen->add_help_tab( $tab );
}

if( ! empty( $this->args['contextual_help_sidebar'] ) ) {
$screen->set_help_sidebar( $this->args['contextual_help_sidebar'] );
}

}


}




public function add_options_html() {

$has_nav       = ( count( $this->pre_tabs ) > 1 ) ? true : false;
$show_all      = ( ! $has_nav ) ? ' jinsom-panel-show-all' : '';
$sticky_class  = ( $this->args['sticky_header'] ) ? ' jinsom-panel-sticky-header' : '';
$wrapper_class = ( $this->args['framework_class'] ) ? ' '. $this->args['framework_class'] : '';
$panel_name=jinsom_get_option('jinsom_panel_name');
$panel_skin=jinsom_get_option('jinsom_panel_skin');
if(!$panel_name){$panel_name='LightSNS';}
if(!$panel_skin){$panel_skin='dark';}

echo '<div class="jinsom-panel jinsom-panel-theme-'.$panel_skin.' jinsom-panel-options'. $wrapper_class .'" data-slug="'. $this->args['menu_slug'] .'" data-unique="'. $this->unique .'">';



echo '<div class="jinsom-panel-container">';

echo '<form method="post" action="" enctype="multipart/form-data" id="jinsom-panel-form" autocomplete="off">';

echo '<div class="jinsom-panel-header'. esc_attr( $sticky_class ) .'">';
echo '<div class="jinsom-panel-header-inner">';

echo '<div class="jinsom-panel-header-left">';
echo '<h1>'. $panel_name .'</h1>';
echo '</div>';
echo '<div class="jinsom-panel-header-menu">';
$jinsom_panel_menu_add=jinsom_get_option('jinsom_panel_menu_add');
if($jinsom_panel_menu_add){
foreach ($jinsom_panel_menu_add as $data) {
echo '<a href="'.$data['link'].'" target="_blank">'.$data['title'].'</a>';
}
}else{
echo '<a href="/" target="_blank">'.__('首页','jinsom').'</a>';
echo '<a href="https://q.jinsom.cn/" target="_blank">LightSNS官网</a>';	
}
echo '</div>';

echo '<div class="jinsom-panel-search"><input type="text" name="jinsom-panel-search" placeholder="搜索设置选项" autocomplete="off"/></div>';


echo '<div class="clear"></div>';
echo '</div>';
echo '</div>';

echo '<div class="jinsom-panel-wrapper'. $show_all .'">';

if( $has_nav ) {
echo '<div class="jinsom-panel-nav jinsom-panel-nav-options">';

echo '<ul>';

echo '<div class="jinsom-show-menu"><i class="fa fa-compress"></i><span>展开菜单</span></div>';
$tab_key = 1;

foreach( $this->pre_tabs as $tab ) {

$tab_icon  = ( ! empty( $tab['icon'] ) ) ? '<i class="'. $tab['icon'] .'"></i>' : '';

if( ! empty( $tab['subs'] ) ) {

echo '<li class="jinsom-panel-tab-depth-0">';

echo '<a href="#tab='. $tab_key .'" class="jinsom-panel-arrow">'. $tab_icon . $tab['title'].'</a>';

echo '<ul>';

foreach ( $tab['subs'] as $sub ) {

$sub_icon  = ( ! empty( $sub['icon'] ) ) ? '<i class="'. $sub['icon'] .'"></i>' : '';

echo '<li class="jinsom-panel-tab-depth-1"><a id="jinsom-panel-tab-link-'. $tab_key .'" href="#tab='. $tab_key .'">'. $sub_icon . $sub['title'] .'</a></li>';

$tab_key++;
}

echo '</ul>';

echo '</li>';

} else {

echo '<li class="jinsom-panel-tab-depth-0"><a id="jinsom-panel-tab-link-'. $tab_key .'" href="#tab='. $tab_key .'">'. $tab_icon . $tab['title'] . $tab_error .'</a></li>';

$tab_key++;
}

}

echo '</ul>';

echo '</div>';

}

echo '<div class="jinsom-panel-content">';
echo '<div class="jinsom-panel-search-tips">'.__('以下设置选项为搜索的数据','jinsom').'</div>';
echo '<div class="jinsom-panel-sections">';

$section_key = 1;

foreach( $this->pre_sections as $section ) {

$onload = ( ! $has_nav ) ? ' jinsom-panel-onload' : '';
$section_icon = ( ! empty( $section['icon'] ) ) ? '<i class="jinsom-panel-icon '. $section['icon'] .'"></i>' : '';

echo '<div id="jinsom-panel-section-'. $section_key .'" class="jinsom-panel-section'. $onload .'">';
echo ( $has_nav ) ? '<div class="jinsom-panel-section-title"><h3>'. $section_icon . $section['title'] .'</h3></div>' : '';
echo ( ! empty( $section['description'] ) ) ? '<div class="jinsom-panel-field jinsom-panel-section-description">'. $section['description'] .'</div>' : '';

if( ! empty( $section['fields'] ) ) {

foreach( $section['fields'] as $field ) {


$value = ( ! empty( $field['id'] ) && isset( $this->options[$field['id']] ) ) ? $this->options[$field['id']] : '';

LightSNS::field( $field, $value, $this->unique, 'options' );

}

} else {

echo '<div class="jinsom-panel-no-option jinsom-panel-text-muted">'.__('没有任何内容','jinsom').'</div>';

}

echo '</div>';

$section_key++;
}

echo '</div>';

echo '<div class="clear"></div>';

echo '</div>';

echo '<div class="jinsom-panel-nav-background"></div>';

echo '</div>';

echo '<div class="jinsom-panel-footer">
<span onclick="jinsom_admin_save_setting(1)" class="button button-primary jinsom-panel-save">'.__('保存设置','jinsom').'</span>
</div>';

echo '</form>';

echo '</div>';

echo '<div class="clear"></div>';

echo '</div>';

}
}
}




//获取设置
function jinsom_get_option( $option = '', $default = null ) {
$options = get_option('jinsom_options');
return ( isset( $options[$option] ) ) ? $options[$option] : $default;
}

//更新设置
function jinsom_update_option($option,$val){
$options = get_option('jinsom_options');
$options[$option]=$val;
update_option('jinsom_options',$options);
}
<?php if ( ! defined( 'ABSPATH' ) ) { die; }

function jinsom_admin_script(){
// global $current_user;
$object=array();
$object['home_url']=home_url(); 
$object['domain']=$_SERVER['SERVER_NAME']; 
$object['update_url']=wp_get_theme()->get('ThemeURI');
$object['bbs_name']=jinsom_get_option('jinsom_bbs_name');   
$object['jinsom_ajax_url']=get_template_directory_uri().'/module';
$object['theme_url']=get_template_directory_uri();
$object['ip']=$_SERVER['REMOTE_ADDR'];
$object_json=json_encode($object);
return $object_json;
}

function jinsom_admin_enqueue_scripts() {
?>
<script type="text/javascript">
var jinsom = <?php echo jinsom_admin_script();?>;
</script>  
<?php
}
add_action( 'admin_enqueue_scripts', 'jinsom_admin_enqueue_scripts' );



if( ! class_exists( 'LightSNS' ) ) {
class LightSNS {


public static $version = '2.0.7';
public static $premium = true;
public static $dir     = null;
public static $url     = null;
public static $inited  = array();
public static $fields  = array();
public static $args    = array(
'options'            => array(),
'customize_options'  => array(),
'metaboxes'          => array(),
'profile_options'    => array(),
'shortcoders'        => array(),
'taxonomy_options'   => array(),
'widgets'            => array(),
);


public static $shortcode_instances = array();


public static function setup() {


$params = array();
if ( ! empty( self::$args['options'] ) ) {
foreach( self::$args['options'] as $key => $value ) {
if( ! empty( self::$args['sections'][$key] ) && ! isset( self::$inited[$key] ) ) {

$params['args']     = $value;
$params['sections'] = self::$args['sections'][$key];
self::$inited[$key] = true;

LightSNS_Options::instance( $key, $params );

if( ! empty( $value['show_in_customizer'] ) ) {
self::$args['customize_options'][$key] = ( is_array( $value['show_in_customizer'] ) ) ? $value['show_in_customizer'] : $value;
}


}
}
}


$params = array();
if ( ! empty( self::$args['customize_options'] ) ) {
foreach( self::$args['customize_options'] as $key => $value ) {
if( ! empty( self::$args['sections'][$key] ) && ! isset( self::$inited[$key] ) ) {

$params['args']     = $value;
$params['sections'] = self::$args['sections'][$key];
self::$inited[$key] = true;

LightSNS_Customize_Options::instance( $key, $params );


}
}
}


$params = array();
if ( ! empty( self::$args['metaboxes'] ) ) {
foreach( self::$args['metaboxes'] as $key => $value ) {
if( ! empty( self::$args['sections'][$key] ) && ! isset( self::$inited[$key] ) ) {

$params['args']     = $value;
$params['sections'] = self::$args['sections'][$key];
self::$inited[$key] = true;

LightSNS_Metabox::instance( $key, $params );

}
}
}


$params = array();
if ( ! empty( self::$args['profile_options'] ) ) {
foreach( self::$args['profile_options'] as $key => $value ) {
if( ! empty( self::$args['sections'][$key] ) && ! isset( self::$inited[$key] ) ) {

$params['args']     = $value;
$params['sections'] = self::$args['sections'][$key];
self::$inited[$key] = true;

LightSNS_Profile_Options::instance( $key, $params );

}
}
}


$params = array();
if ( ! empty( self::$args['shortcoders'] ) ) {

foreach( self::$args['shortcoders'] as $key => $value ) {
if( ! empty( self::$args['sections'][$key] ) && ! isset( self::$inited[$key] ) ) {

$params['args']     = $value;
$params['sections'] = self::$args['sections'][$key];
self::$inited[$key] = true;

LightSNS_Shortcoder::instance( $key, $params );

}
}


if( ! empty( LightSNS::$shortcode_instances ) ) {
LightSNS_Shortcoder::once_editor_setup();
}

}


$params = array();
if ( ! empty( self::$args['taxonomy_options'] ) ) {
foreach( self::$args['taxonomy_options'] as $key => $value ) {
if( ! empty( self::$args['sections'][$key] ) && ! isset( self::$inited[$key] ) ) {

$params['args']     = $value;
$params['sections'] = self::$args['sections'][$key];
self::$inited[$key] = true;

LightSNS_Taxonomy_Options::instance( $key, $params );

}
}
}


if ( ! empty( self::$args['widgets'] ) && class_exists( 'WP_Widget_Factory' ) ) {

$wp_widget_factory = new WP_Widget_Factory();

foreach( self::$args['widgets'] as $key => $value ) {
if( ! isset( self::$inited[$key] ) ) {
self::$inited[$key] = true;
$wp_widget_factory->register( LightSNS_Widget::instance( $key, $value ) );
}
}

}

do_action( 'csf_loaded' );

}



public static function init(){
do_action( 'csf_init' );
self::constants();
self::includes();
add_action( 'after_setup_theme', array( 'LightSNS', 'setup' ) );
add_action( 'init', array( 'LightSNS', 'setup' ) );
add_action( 'switch_theme', array( 'LightSNS', 'setup' ) );
add_action( 'admin_enqueue_scripts', array( 'LightSNS', 'add_admin_enqueue_scripts' ), 20 );
}


public static function createOptions( $id, $args = array() ) {
self::$args['options'][$id] = $args;
}


public static function createCustomizeOptions( $id, $args = array() ) {
self::$args['customize_options'][$id] = $args;
}


public static function createMetabox( $id, $args = array() ) {
self::$args['metaboxes'][$id] = $args;
}


public static function createShortcoder( $id, $args = array() ) {
self::$args['shortcoders'][$id] = $args;
}


public static function createTaxonomyOptions( $id, $args = array() ) {
self::$args['taxonomy_options'][$id] = $args;
}


public static function createProfileOptions( $id, $args = array() ) {
self::$args['profile_options'][$id] = $args;
}


public static function createWidget( $id, $args = array() ) {
self::$args['widgets'][$id] = $args;
self::set_used_fields( $args );
}


public static function createSection( $id, $sections ) {
self::$args['sections'][$id][] = $sections;
self::set_used_fields( $sections );
}


public static function constants() {


$dirname        = wp_normalize_path( dirname( dirname( __FILE__ ) ) );
$theme_dir      = wp_normalize_path( get_parent_theme_file_path() );
$plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
$located_plugin = ( preg_match( '#'. self::sanitize_dirname( $plugin_dir ) .'#', self::sanitize_dirname( $dirname ) ) ) ? true : false;
$directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_parent_theme_file_uri();
$foldername     = str_replace( $directory, '', $dirname );

self::$dir = $dirname;
self::$url = $directory_uri . $foldername;

}

public static function include_plugin_file( $file, $load = true ) {

$path     = '';
$file     = ltrim( $file, '/' );
$override = apply_filters( 'csf_override', 'jinsom-panel-override' );

if( file_exists( get_parent_theme_file_path( $override .'/'. $file ) ) ) {
$path = get_parent_theme_file_path( $override .'/'. $file );
} elseif ( file_exists( get_theme_file_path( $override .'/'. $file ) ) ) {
$path = get_theme_file_path( $override .'/'. $file );
} elseif ( file_exists( self::$dir .'/'. $override .'/'. $file ) ) {
$path = self::$dir .'/'. $override .'/'. $file;
} elseif ( file_exists( self::$dir .'/'. $file ) ) {
$path = self::$dir .'/'. $file;
}

if( ! empty( $path ) && ! empty( $file ) && $load ) {

global $wp_query;

if( is_object( $wp_query ) && function_exists( 'load_template' ) ) {

load_template( $path, true );

} else {

require_once( $path );

}

} else {

return self::$dir .'/'. $file;

}

}

public static function is_active_plugin( $file = '' ) {
return in_array( $file, (array) get_option( 'active_plugins', array() ) );
}


public static function sanitize_dirname( $dirname ) {
return preg_replace( '/[^A-Za-z]/', '', $dirname );
}


public static function include_plugin_url( $file ) {
return self::$url .'/'. ltrim( $file, '/' );
}


public static function includes() {

// includes helpers
self::include_plugin_file( 'classes/helpers.php'    );

// includes free version classes
self::include_plugin_file( 'classes/fields.class.php'   );
self::include_plugin_file( 'classes/options.class.php'  );

// includes premium version classes
if( self::$premium ) {
// self::include_plugin_file( 'classes/customize-options.class.php' );
self::include_plugin_file( 'classes/metabox.class.php'           );
// self::include_plugin_file( 'classes/profile-options.class.php'   );
// self::include_plugin_file( 'classes/shortcoder.class.php'        );
// self::include_plugin_file( 'classes/taxonomy-options.class.php'  );
self::include_plugin_file( 'classes/widgets.class.php'           );
}

}


public static function maybe_include_field( $type = '' ) {
if( ! class_exists( 'LightSNS_Field_'. $type ) && class_exists( 'LightSNS_Fields' ) ) {
self::include_plugin_file( 'templates/'. $type .'/'. $type .'.php' );
}
}


public static function set_used_fields( $sections ) {

if( ! empty( $sections['fields'] ) ) {

foreach( $sections['fields'] as $field ) {

if( ! empty( $field['fields'] ) ) {
self::set_used_fields( $field );
}

if( ! empty( $field['tabs'] ) ) {
self::set_used_fields( array( 'fields' => $field['tabs'] ) );
}

if( ! empty( $field['accordions'] ) ) {
self::set_used_fields( array( 'fields' => $field['accordions'] ) );
}

if( ! empty( $field['type'] ) ) {
self::$fields[$field['type']] = $field;
}

}

}

}



// 引入css、js
public static function add_admin_enqueue_scripts() {
$theme_url=get_template_directory_uri();
global $version;
$jinsom_js_cdn_url=jinsom_get_option('jinsom_js_cdn_url');
$LightSNS_CDN_url=$jinsom_js_cdn_url?$jinsom_js_cdn_url:'https://cdn.jsdelivr.net/gh/jinsom/LightSNS-CDN@'.$version;
$url=$_SERVER['PHP_SELF'];
$url_arr=explode("/",$url);
$end=end($url_arr);
// if((isset($_GET['page'])&&$_GET['page']=='jinsom')||$end=='widgets.php'||isset($_GET['post_type'])||(isset($_GET['action'])&&$_GET['action']=='edit')){

wp_enqueue_script('jquery-ui-button');
wp_enqueue_script('jquery-ui-spinner');
wp_enqueue_script('jquery-ui-accordion');
wp_enqueue_script('jquery-ui-datepicker');

wp_enqueue_media();


wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'wp-color-picker' );


wp_enqueue_style('jinsom-panel-fa',$LightSNS_CDN_url.'/assets/css/font-awesome.min.css',array(),$version,'all');
wp_enqueue_style('iconfont','https://at.alicdn.com/t/font_502180_4blfl8eyll5.css',array(),$version);
wp_enqueue_style('layui-css',$LightSNS_CDN_url.'/extend/layui/css/layui.css');
wp_enqueue_style('admin-css',$LightSNS_CDN_url.'/admin/assets/css/style.css',array(),$version,'all');
wp_enqueue_script('layui-js',$LightSNS_CDN_url.'/extend/layui/layui.js');//layui
wp_enqueue_script('csf',$LightSNS_CDN_url.'/admin/assets/js/framework.js',array('admin-plugins'),$version,true);
wp_enqueue_script('admin-plugins',$LightSNS_CDN_url.'/admin/assets/js/plugins.js',array(),$version,true);

//兼容qqworld-collections插件
if(!is_plugin_active('qqworld-collector/qqworld-collector.php')||$_GET['page']=='jinsom'){
wp_enqueue_script('jquery-min',$LightSNS_CDN_url.'/assets/js/jquery.min.js',false,$version,true);	
}

wp_enqueue_script('base',$LightSNS_CDN_url.'/admin/assets/js/base.js',array('admin-plugins'),$version,true);

wp_localize_script('csf','csf_vars', array(
'color_palette'  => apply_filters( 'csf_color_palette', array() ),
'i18n'           => array(
'confirm'             => '你确定要这样操作？',
'reset_notification'  => '恢复设置选项',
'import_notification' => '导入设置选项',
),
) );


$enqueued = array();

if( ! empty( self::$fields ) ) {
foreach( self::$fields as $field ) {
if( ! empty( $field['type'] ) ) {
$classname = 'LightSNS_Field_' . $field['type'];
self::maybe_include_field( $field['type'] );
if( class_exists( $classname ) && method_exists( $classname, 'enqueue' ) ) {
$instance = new $classname( $field );
if( method_exists( $classname, 'enqueue' ) ) {
$instance->enqueue();
}
unset( $instance );
}
}
}
}

do_action( 'csf_enqueue' );
// }

}

//
// Add a new framework field
public static function field( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {

// Check for unallow fields
if( ! empty( $field['_notice'] ) ) {

$field_type = $field['type'];

$field            = array();
$field['content'] = '无法使用此字段';
$field['type']    = 'notice';
$field['style']   = 'danger';

}

$depend     = '';
$hidden     = '';
$unique     = ( ! empty( $unique ) ) ? $unique : '';
$class      = ( ! empty( $field['class'] ) ) ? ' ' . $field['class'] : '';
$is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' jinsom-panel-pseudo-field' : '';
$field_type = ( ! empty( $field['type'] ) ) ? $field['type'] : '';

if ( ! empty( $field['dependency'] ) ) {

$dependency      = $field['dependency'];
$depend_visible  = '';
$data_controller = '';
$data_condition  = '';
$data_value      = '';
$data_global     = '';

if( is_array( $dependency[0] ) ) {
$data_controller = implode( '|', array_column( $dependency, 0 ) );
$data_condition  = implode( '|', array_column( $dependency, 1 ) );
$data_value      = implode( '|', array_column( $dependency, 2 ) );
$data_global     = implode( '|', array_column( $dependency, 3 ) );
$depend_visible  = implode( '|', array_column( $dependency, 4 ) );
} else {
$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
$data_condition  = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
$data_value      = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
$data_global     = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
$depend_visible  = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
}

$depend .= ' data-controller="'. esc_attr( $data_controller ) .'"';
$depend .= ' data-condition="'. esc_attr( $data_condition ) .'"';
$depend .= ' data-value="'. esc_attr( $data_value ) .'"';
$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';
$visible = ( ! empty( $depend_visible ) ) ? ' jinsom-panel-depend-visible' : ' jinsom-panel-depend-hidden';

}

if( ! empty( $field_type ) ) {

echo '<div class="jinsom-panel-field jinsom-panel-field-'. $field_type . $is_pseudo . $class . $hidden .'"'. $depend .'>';

if( ! empty( $field['title'] ) ) {
$subtitle = ( ! empty( $field['subtitle'] ) ) ? '<p class="jinsom-panel-text-subtitle">'. $field['subtitle'] .'</p>' : '';
echo '<div class="jinsom-panel-title"><h4>' . $field['title'] . '</h4>'. $subtitle .'</div>';
}

echo ( ! empty( $field['title'] ) ) ? '<div class="jinsom-panel-fieldset">' : '';

$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

self::maybe_include_field( $field_type );

$classname = 'LightSNS_Field_'. $field_type;

if( class_exists( $classname ) ) {
$instance = new $classname( $field, $value, $unique, $where, $parent );
$instance->render();
} else {
echo '<p>此字段类不可用</p>';
}

} else {
echo '<p>没有发现这个类型</p>';
}

echo ( ! empty( $field['title'] ) ) ? '</div>' : '';
echo '<div class="clear"></div>';
echo '</div>';

}

}

LightSNS::init();
}

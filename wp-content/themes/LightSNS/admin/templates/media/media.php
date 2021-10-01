<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_media' ) ) {
class LightSNS_Field_media extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$args = wp_parse_args( $this->field, array(
'url'          => true,
'preview'      => true,
'library'      => array(),
'button_title' => '上传',
'remove_title' => '移除',
'preview_size' => 'thumbnail',
) );

$default_values = array(
'url'         => '',
'id'          => '',
'width'       => '',
'height'      => '',
'thumbnail'   => '',
'alt'         => '',
'title'       => '',
'description' => ''
);

$this->value  = wp_parse_args( $this->value, $default_values );

$library     = ( is_array( $args['library'] ) ) ? $args['library'] : array_filter( (array) $args['library'] );
$library     = ( ! empty( $library ) ) ? implode(',', $library ) : '';
$preview_src = ( $args['preview_size'] !== 'thumbnail' ) ? $this->value['url'] : $this->value['thumbnail'];
$hidden_url  = ( empty( $args['url'] ) ) ? ' hidden' : '';
$hidden_auto = ( empty( $this->value['url'] ) ) ? ' hidden' : '';
$placeholder = ( empty( $this->field['placeholder'] ) ) ? ' placeholder="请点击上传插入内容"' : '';

echo $this->field_before();

if( ! empty( $args['preview'] ) ) {
echo '<div class="jinsom-panel--preview'. $hidden_auto .'">';
echo '<div class="jinsom-panel-image-preview"><a href="#" class="jinsom-panel--remove fa fa-times"></a><img src="'. $preview_src .'" class="jinsom-panel--src" /></div>';
echo '</div>';
}

echo '<div class="jinsom-panel--placeholder">';
echo '<input type="text" name="'. $this->field_name('[url]') .'" value="'. $this->value['url'] .'" class="jinsom-panel--url'. $hidden_url .'" readonly="readonly"'. $this->field_attributes() . $placeholder .' />';
echo '<a href="#" class="button button-primary jinsom-panel--button" data-library="'. esc_attr( $library ) .'" data-preview-size="'. esc_attr( $args['preview_size'] ) .'">'. $args['button_title'] .'</a>';
echo ( empty( $args['preview'] ) ) ? '<a href="#" class="button button-secondary jinsom-panel-warning-primary jinsom-panel--remove'. $hidden_auto .'">'. $args['remove_title'] .'</a>' : '';
echo '</div>';

echo '<input type="hidden" name="'. $this->field_name('[id]') .'" value="'. $this->value['id'] .'" class="jinsom-panel--id"/>';
echo '<input type="hidden" name="'. $this->field_name('[width]') .'" value="'. $this->value['width'] .'" class="jinsom-panel--width"/>';
echo '<input type="hidden" name="'. $this->field_name('[height]') .'" value="'. $this->value['height'] .'" class="jinsom-panel--height"/>';
echo '<input type="hidden" name="'. $this->field_name('[thumbnail]') .'" value="'. $this->value['thumbnail'] .'" class="jinsom-panel--thumbnail"/>';
echo '<input type="hidden" name="'. $this->field_name('[alt]') .'" value="'. $this->value['alt'] .'" class="jinsom-panel--alt"/>';
echo '<input type="hidden" name="'. $this->field_name('[title]') .'" value="'. $this->value['title'] .'" class="jinsom-panel--title"/>';
echo '<input type="hidden" name="'. $this->field_name('[description]') .'" value="'. $this->value['description'] .'" class="jinsom-panel--description"/>';

echo $this->field_after();

}

}
}

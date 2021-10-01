<?php if ( ! defined( 'ABSPATH' ) ) { die; } 
if( ! class_exists( 'LightSNS_Field_upload' ) ) {
class LightSNS_Field_upload extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$args = wp_parse_args( $this->field, array(
'library'      => array(),
'button_title' => '上传',
'remove_title' => '移除',
) );

echo $this->field_before();

$library = ( is_array( $args['library'] ) ) ? $args['library'] : array_filter( (array) $args['library'] );
$library = ( ! empty( $library ) ) ? implode(',', $library ) : '';
$hidden  = ( empty( $this->value ) ) ? ' hidden' : '';

echo '<div class="jinsom-panel--wrap">';
echo '<input type="text" name="'. $this->field_name() .'" value="'. $this->value .'"'. $this->field_attributes() .'/>';
echo '<div class="jinsom-panel--buttons">';
echo '<a href="#" class="button button-primary jinsom-panel--button" data-library="'. esc_attr( $library ) .'">'. $args['button_title'] .'</a>';
echo '<a href="#" class="button button-secondary jinsom-panel-warning-primary jinsom-panel--remove'. $hidden .'">'. $args['remove_title'] .'</a>';
echo '</div>';
echo '</div>';

echo $this->field_after();

}
}
}

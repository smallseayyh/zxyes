<?php if ( ! defined( 'ABSPATH' ) ) { die; } 
if( ! class_exists( 'LightSNS_Field_dimensions' ) ) {
class LightSNS_Field_dimensions extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$args = wp_parse_args( $this->field, array(
'width_icon'         => '<i class="fa fa-arrows-h"></i>',
'height_icon'        => '<i class="fa fa-arrows-v"></i>',
'width_placeholder'  => 'width',
'height_placeholder' => 'height',
'width'              => true,
'height'             => true,
'unit'               => true,
'units'              => array( 'px', '%', 'em' )
) );

$default_values = array(
'width'  => '',
'height' => '',
'unit'   => 'px',
);

$value = wp_parse_args( $this->value, $default_values );

echo $this->field_before();

if( ! empty( $args['width'] ) ) {

$placeholder = ( ! empty( $args['width_placeholder'] ) ) ? ' placeholder="'. $args['width_placeholder'] .'"' : '';

echo '<div class="jinsom-panel--input">';
echo ( ! empty( $args['width_icon'] ) ) ? '<span class="jinsom-panel--label jinsom-panel--label-icon">'. $args['width_icon'] .'</span>' : '';
echo '<input type="text" name="'. $this->field_name('[width]') .'" value="'. $value['width'] .'"'. $placeholder .' class="jinsom-panel-number" />';
echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="jinsom-panel--label jinsom-panel--label-unit">'. $args['units'][0] .'</span>' : '';
echo '</div>';

}

if( ! empty( $args['height'] ) ) {

$placeholder = ( ! empty( $args['height_placeholder'] ) ) ? ' placeholder="'. $args['height_placeholder'] .'"' : '';

echo '<div class="jinsom-panel--input">';
echo ( ! empty( $args['height_icon'] ) ) ? '<span class="jinsom-panel--label jinsom-panel--label-icon">'. $args['height_icon'] .'</span>' : '';
echo '<input type="text" name="'. $this->field_name('[height]') .'" value="'. $value['height'] .'"'. $placeholder .' class="jinsom-panel-number" />';
echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="jinsom-panel--label jinsom-panel--label-unit">'. $args['units'][0] .'</span>' : '';
echo '</div>';

}

if( ! empty( $args['unit'] ) && count( $args['units'] ) > 1 ) {
echo '<select name="'. $this->field_name('[unit]') .'">';
foreach( $args['units'] as $unit ) {
$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
echo '<option value="'. $unit .'"'. $selected .'>'. $unit .'</option>';
}
echo '</select>';
}

echo '<div class="clear"></div>';

echo $this->field_after();

}

public function output() {

$output    = '';
$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];
$prefix    = ( ! empty( $this->field['output_prefix'] ) ) ? $this->field['output_prefix'] .'-' : '';
$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
$unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';
$width     = ( isset( $this->value['width'] ) && $this->value['width'] !== '' ) ? $prefix .'width:'. $this->value['width'] . $unit . $important .';' : '';
$height    = ( isset( $this->value['height'] ) && $this->value['width'] !== '' ) ? $prefix .'height:'. $this->value['height'] . $unit . $important .';' : '';

if( $width !== '' || $height !== '' ) {
$output = $element .'{'. $width . $height .'}';
}

$this->parent->output_css .= $output;

return $output;

}

}
}

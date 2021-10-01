<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_color_group' ) ) {
class LightSNS_Field_color_group extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

echo $this->field_before();

if( ! empty( $options ) ) {
foreach( $options as $key => $option ) {

$color_value  = ( ! empty( $this->value[$key] ) ) ? $this->value[$key] : '';
$default_attr = ( ! empty( $this->field['default'][$key] ) ) ? ' data-default-color="'. $this->field['default'][$key] .'"' : '';

echo '<div class="jinsom-panel--left jinsom-panel-field-color">';
echo '<div class="jinsom-panel--title">'. $option .'</div>';
echo '<input type="text" name="'. $this->field_name('['. $key .']') .'" value="'. $color_value .'" class="jinsom-panel-color"'. $default_attr . $this->field_attributes() .'/>';
echo '</div>';

}
}

echo '<div class="clear"></div>';

echo $this->field_after();

}

}
}

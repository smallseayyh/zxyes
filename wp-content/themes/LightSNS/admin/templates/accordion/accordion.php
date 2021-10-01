<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_accordion' ) ) {
class LightSNS_Field_accordion extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$unallows = array( 'accordion' );

echo $this->field_before();

echo '<div class="jinsom-panel-accordion-items">';

foreach ( $this->field['accordions'] as $key => $accordion ) {

echo '<div class="jinsom-panel-accordion-item">';

$icon = ( ! empty( $accordion['icon'] ) ) ? 'jinsom-panel--icon '. $accordion['icon'] : 'jinsom-panel-accordion-icon fa fa-angle-right';

echo '<h4 class="jinsom-panel-accordion-title">';
echo '<i class="'. $icon .'"></i>';
echo $accordion['title'];
echo '</h4>';

echo '<div class="jinsom-panel-accordion-content">';

foreach ( $accordion['fields'] as $field ) {

if( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
$field_value   = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $field_default;
$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']' : $this->field['id'];

LightSNS::field( $field, $field_value, $unique_id, 'field/accordion' );

}

echo '</div>';

echo '</div>';

}

echo '</div>';

echo $this->field_after();

}

}
}

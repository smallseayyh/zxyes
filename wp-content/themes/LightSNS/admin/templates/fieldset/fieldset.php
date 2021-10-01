<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_fieldset' ) ) {
class LightSNS_Field_fieldset extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

echo $this->field_before();

echo '<div class="jinsom-panel-fieldset-content">';

foreach ( $this->field['fields'] as $field ) {

$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
$field_value   = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $field_default;
$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']' : $this->field['id'];

LightSNS::field( $field, $field_value, $unique_id, 'field/fieldset' );

}

echo '</div>';

echo $this->field_after();

}

}
}

<?php if ( ! defined( 'ABSPATH' ) ) { die; } 
if( ! class_exists( 'LightSNS_Field_repeater' ) ) {
class LightSNS_Field_repeater extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$args = wp_parse_args( $this->field, array(
'max'          => 0,
'min'          => 0,
'button_title' => '<i class="fa fa-plus-circle"></i>',
) );

$fields    = $this->field['fields'];
$unique_id = ( ! empty( $this->unique ) ) ? $this->unique : $this->field['id'];

if( $this->parent && preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->parent ) ) {

echo '<div class="jinsom-panel-notice jinsom-panel-notice-danger">字段ID重复！</div>';

} else {

echo $this->field_before();

echo '<div class="jinsom-panel-repeater-item jinsom-panel-repeater-hidden">';
echo '<div class="jinsom-panel-repeater-content">';
foreach ( $fields as $field ) {

$field_parent  = $this->parent .'['. $this->field['id'] .']';
$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

LightSNS::field( $field, $field_default, '_nonce', 'field/repeater', $field_parent );

}
echo '</div>';
echo '<div class="jinsom-panel-repeater-helper">';
echo '<div class="jinsom-panel-repeater-helper-inner">';
echo '<i class="jinsom-panel-repeater-sort fa fa-arrows"></i>';
echo '<i class="jinsom-panel-repeater-clone fa fa-clone"></i>';
echo '<i class="jinsom-panel-repeater-remove jinsom-panel-confirm fa fa-times" data-confirm="你确定要删除这个选项吗？"></i>';
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="jinsom-panel-repeater-wrapper jinsom-panel-data-wrapper" data-unique-id="'. $this->unique .'" data-field-id="['. $this->field['id'] .']" data-max="'. $args['max'] .'" data-min="'. $args['min'] .'">';

if( ! empty( $this->value ) ) {

$num = 0;

foreach ( $this->value as $key => $value ) {

echo '<div class="jinsom-panel-repeater-item">';

echo '<div class="jinsom-panel-repeater-content">';
foreach ( $fields as $field ) {

$field_parent = $this->parent .'['. $this->field['id'] .']';
$field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
$field_value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

LightSNS::field( $field, $field_value, $field_unique, 'field/repeater', $field_parent );

}
echo '</div>';

echo '<div class="jinsom-panel-repeater-helper">';
echo '<div class="jinsom-panel-repeater-helper-inner">';
echo '<i class="jinsom-panel-repeater-sort fa fa-arrows"></i>';
echo '<i class="jinsom-panel-repeater-clone fa fa-clone"></i>';
echo '<i class="jinsom-panel-repeater-remove jinsom-panel-confirm fa fa-times" data-confirm="你确定要删除这个选项吗？"></i>';
echo '</div>';
echo '</div>';

echo '</div>';

$num++;

}

}

echo '</div>';

echo '<div class="jinsom-panel-repeater-alert jinsom-panel-repeater-max">最多只能添加'.$args['max'].'个选项</div>';
echo '<div class="jinsom-panel-repeater-alert jinsom-panel-repeater-min">最少要添加'.$args['min'].'个选项</div>';

echo '<a href="#" class="button button-primary jinsom-panel-repeater-add">'. $args['button_title'] .'</a>';

echo $this->field_after();

}

}

public function enqueue() {

if( ! wp_script_is( 'jquery-ui-sortable' ) ) {
wp_enqueue_script( 'jquery-ui-sortable' );
}

}

}
}

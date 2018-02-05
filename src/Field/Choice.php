<?php
/**
 * The abstract choice field.
 *
 * @package Meta Box
 */

namespace MetaBox\Field;

/**
 * Abstract class for any kind of choice field.
 */
abstract class Choice extends Base {
	/**
	 * Walk options.
	 *
	 * @param array $field     Field parameters.
	 * @param mixed $options   Select options.
	 * @param mixed $db_fields Database fields to use in the output.
	 * @param mixed $meta      Meta value.
	 *
	 * @return string
	 */
	public static function walk( $field, $options, $db_fields, $meta ) {
		return '';
	}

	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function html( $meta, $field ) {
		$meta      = (array) $meta;
		$options   = self::call( 'get_options', $field );
		$options   = self::call( 'filter_options', $field, $options );
		$db_fields = self::call( 'get_db_fields', $field );

		return ! empty( $options ) ? self::call( 'walk', $field, $options, $db_fields, $meta ) : null;
	}

	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 *
	 * @return array
	 */
	public static function normalize( $field ) {
		$field = parent::normalize( $field );
		$field = wp_parse_args( $field, [
			'flatten' => true,
			'options' => [],
		] );

		return $field;
	}

	/**
	 * Get field names of object to be used by walker.
	 *
	 * @return array
	 */
	public static function get_db_fields() {
		return [
			'parent' => 'parent',
			'id'     => 'value',
			'label'  => 'label',
		];
	}

	/**
	 * Get options for walker.
	 *
	 * @param array $field Field parameters.
	 *
	 * @return array
	 */
	public static function get_options( $field ) {
		$options = [];
		foreach ( (array) $field['options'] as $value => $label ) {
			$option = is_array( $label ) ? $label : [
				'label' => (string) $label,
				'value' => (string) $value,
			];
			if ( isset( $option['label'] ) && isset( $option['value'] ) ) {
				$options[ $option['value'] ] = (object) $option;
			}
		}

		return $options;
	}

	/**
	 * Filter options for walker.
	 *
	 * @param array $field   Field parameters.
	 * @param array $options Array of choice options.
	 *
	 * @return array
	 */
	public static function filter_options( $field, $options ) {
		$db_fields = self::call( 'get_db_fields', $field );
		$label     = $db_fields['label'];
		foreach ( $options as &$option ) {
			$option         = apply_filters( 'rwmb_option', $option, $field );
			$option->$label = apply_filters( 'rwmb_option_label', $option->$label, $option, $field );
		}

		return $options;
	}

	/**
	 * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
	 *
	 * @param array    $field   Field parameters.
	 * @param string   $value   The value.
	 * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
	 * @param int|null $post_id Post ID. null for current post. Optional.
	 *
	 * @return string
	 */
	public static function format_single_value( $field, $value, $args, $post_id ) {
		return self::call( 'get_option_label', $field, $value );
	}

	/**
	 * Get option label.
	 *
	 * @param array  $field Field parameters.
	 * @param string $value Option value.
	 *
	 * @return string
	 */
	public static function get_option_label( $field, $value ) {
		$options = self::call( 'get_options', $field );

		return isset( $options[ $value ] ) ? $options[ $value ]->label : '';
	}
}

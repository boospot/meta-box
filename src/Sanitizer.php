<?php
/**
 * Sanitize field value before saving.
 *
 * @package Meta Box
 */

namespace MetaBox;

/**
 * Sanitize class.
 */
class Sanitizer {

	/**
	 * Built-in callbacks for some specific types.
	 *
	 * @var array
	 */
	protected $callbacks = [
		'email'      => 'sanitize_email',
		'file_input' => 'esc_url_raw',
		'oembed'     => 'esc_url_raw',
		'url'        => 'esc_url_raw',
	];

	/**
	 * Register hook to sanitize field value.
	 */
	public function init() {
		// Built-in callback.
		foreach ( $this->callbacks as $type => $callback ) {
			add_filter( "rwmb_{$type}_sanitize", $callback );
		}

		// Custom callback.
		$types = array_diff( get_class_methods( __CLASS__ ), [ 'init' ] );
		foreach ( $types as $type ) {
			add_filter( "rwmb_{$type}_sanitize", [ $this, $type ] );
		}
	}

	/**
	 * Set the value of checkbox to 1 or 0 instead of 'checked' and empty string.
	 * This prevents using default value once the checkbox has been unchecked.
	 *
	 * @link https://github.com/rilwis/meta-box/issues/6
	 *
	 * @param string $value Checkbox value.
	 *
	 * @return int
	 */
	public function checkbox( $value ) {
		return (int) ! empty( $value );
	}
}

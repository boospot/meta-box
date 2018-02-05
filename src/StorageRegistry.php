<?php
/**
 * Storage registry class
 *
 * @package Meta Box
 */

namespace MetaBox;

/**
 * Class RWMB_Storage_Registry
 */
class StorageRegistry {

	/**
	 * List storage instances.
	 *
	 * @var array
	 */
	protected $storages = [];

	/**
	 * Get storage instance.
	 *
	 * @param string $class_name Storage class name.
	 *
	 * @return RWMB_Storage_Interface
	 */
	public function get( $class_name ) {
		if ( empty( $this->storages[ $class_name ] ) ) {
			if ( ! class_exists( $class_name ) ) {
				return null;
			}

			$this->storages[ $class_name ] = new $class_name();
		}

		return $this->storages[ $class_name ];
	}
}

<?php
/**
 * Abstract LP_Post
 *
 * @author Geoffrey K Taylor <geoffrey.taylor@outlook.com>
 * @see https://github.com/wp-graphql/wp-graphql-woocommerce
 * @license GPL-3
 */

namespace WPGraphQL\Extensions\LearnPress\Model;

use GraphQL\Error\UserError;
use WPGraphQL\Model\Post;

abstract class LP_Post extends Post {

	/**
	 * Stores the LP_Abstract_Post_Data object connected to the model.
	 *
	 * @var object $data
	 */
	protected $lp_data;

	/**
	 * LP_Post constructor
	 *
	 * @param object $data  Data object to be used by the model.
	 */
	public function __construct( $data ) {
		// Store CRUD object.
		$this->lp_data = $data;

		// Get WP_Post object.
		$post = get_post( $data->get_id() );

		// Add $allowed_restricted_fields.
		if ( ! has_filter( 'graphql_allowed_fields_on_restricted_type', [ static::class, 'add_allowed_restricted_fields' ] ) ) {
			add_filter( 'graphql_allowed_fields_on_restricted_type', [ static::class, 'add_allowed_restricted_fields' ], 10, 2 );
		}

		// Execute Post Model constructor.
		parent::__construct( $post );
	}

	/**
	 * Injects CRUD object fields into $allowed_restricted_fields
	 *
	 * @param array  $allowed_restricted_fields  The fields to allow when the data is designated as restricted to the current user.
	 * @param string $model_name                 Name of the model the filter is currently being executed in.
	 *
	 * @return string[]
	 */
	public static function add_allowed_restricted_fields( $allowed_restricted_fields, $model_name ) {
		$class_name = static::class;
		if ( "{$class_name}Object" === $model_name ) {
			return static::get_allowed_restricted_fields( $allowed_restricted_fields );
		}

		return $allowed_restricted_fields;
	}

	/**
	 * Return the fields allowed to be displayed even if this entry is restricted.
	 *
	 * @param array $allowed_restricted_fields  The fields to allow when the data is designated as restricted to the current user.
	 *
	 * @return array
	 */
	protected static function get_allowed_restricted_fields( $allowed_restricted_fields = [] ) {
		return [
			'isRestricted',
			'isPrivate',
			'isPublic',
			'id',
			'databaseId',
		];
	}

	/**
	 * Forwards function calls to LP_Abstract_Post_Data sub-class instance.
	 *
	 * @param string $method - function name.
	 * @param array  $args  - function call arguments.
	 *
	 * @return mixed
	 *
	 * @throws BadMethodCallException Method not found on LP data object.
	 */
	public function __call( $method, $args ) {
		if ( \is_callable( [ $this->lp_data, $method ] ) ) {
			return $this->lp_data->$method( ...$args );
		}

		$class = __CLASS__;
		// throw new BadMethodCallException( "Call to undefined method {$method} on the {$class}" );
	}

	/**
	 * Wrapper function for deleting
	 *
	 * @throws UserError Not authorized.
	 *
	 * @param boolean $force_delete Should the data be deleted permanently.
	 * @return boolean
	 */
	public function delete( $force_delete = false ) {
		if ( ! current_user_can( $this->post_type_object->cap->edit_posts ) ) {
			throw new UserError(
				__(
					'User does not have the capabilities necessary to delete this object.',
					'wp-graphql-learnpress'
				)
			);
		}

		return $this->lp_data->delete( $force_delete );
	}

	/**
	 * Returns the source WP_Post instance.
	 *
	 * @return \WP_Post
	 */
	public function as_WP_Post() {
		return $this->data;
	}

	/**
	 * Returns the source LP_Abstract_Post_Data instance
	 *
	 * @return \LP_Abstract_Post_Data
	 */
	public function as_LP_Data() {
		return $this->lp_data;
	}
}

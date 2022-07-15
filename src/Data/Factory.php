<?php
/**
 * Factory
 *
 * @author Geoffrey K Taylor <geoffrey.taylor@outlook.com>
 * @see https://github.com/wp-graphql/wp-graphql-woocommerce
 * @license GPL-3
 */

namespace WPGraphQL\Extensions\LearnPress\Data;

use GraphQL\Deferred;
use WPGraphQL\AppContext;

class Factory {
	/**
	 * Returns the Learnpress CRUD object for the post ID
	 *
	 * @param int        $id      - post ID of the crud object being retrieved.
	 * @param AppContext $context - AppContext object.
	 *
	 * @return Deferred object
	 * @access public
	 */
	public static function resolve_crud_object( $id, AppContext $context ) {
		if ( empty( $id ) || ! absint( $id ) ) {
			return null;
		}

		$context->get_loader( 'lp_post' )->buffer( [ $id ] );
		return new Deferred(
			function () use ( $id, $context ) {
				return $context->get_loader( 'lp_post' )->load( $id );
			}
		);
	}

	/**
	 * Resolves Relay node for some WPGrapQL LearnPress types.
	 *
	 * @param mixed      $node     Node object.
	 * @param string     $id       Object unique ID.
	 * @param string     $type     Node type.
	 * @param AppContext $context  AppContext instance.
	 *
	 * @return mixed
	 */
	public static function resolve_node( $node, $id, $type, $context ) {
		switch ( $type ) {
			case 'lp_course':
				$node = self::resolve_crud_object( $id, $context );
				break;
		}

		return $node;
	}

	/**
	 * Resolves Relay node type for some WPGrapQL LearnPress types.
	 *
	 * @param string|null $type  Node type.
	 * @param mixed       $node  Node object.
	 *
	 * @return string|null
	 */
	public static function resolve_node_type( $type, $node ) {
		switch ( true ) {
			case is_a( $node, LP_Course::class ):
				$type = 'LpCourse';
				break;
		}

		return $type;
	}
}

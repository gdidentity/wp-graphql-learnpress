<?php
/**
 * Registers LearnPress RootQuery Type object.
 *
 * @package WPGraphQL\Extensions\LearnPress\Type\ObjectType
 * @since 0.0.1-alpha
 */

namespace WPGraphQL\Extensions\LearnPress\Type\ObjectType;

use GraphQL\Error\UserError;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;
use WPGraphQL\Extensions\LearnPress\Data\Factory;
use WPGraphQL\Extensions\LearnPress\Data\SSP;
use WPGraphQL\Extensions\LearnPress\Data\SubscriptionHelper;

/**
 * Class - RootQueryType
 */
class RootQueryType {

	/**
	 * RootQueryType registrations.
	 */
	public static function register(): void {
		register_graphql_fields(
			'RootQuery',
			[
				'lpCourse' => [
					'type'        => 'LpCourse',
					'description' => __( 'A course object', 'wp-graphql-learnpress' ),
					'args'        => [
						'id'     => [ 'type' => [ 'non_null' => 'ID' ] ],
						'idType' => [
							'type'        => 'ContentNodeIdTypeEnum',
							'description' => __( 'Type of unique identifier to fetch a content node by. Default is Global ID', 'wp-graphql' ),
						],
					],
					'resolve'     => function ( $source, array $args, AppContext $context ) {
						$id      = isset( $args['id'] ) ? $args['id'] : null;
						$id_type = isset( $args['idType'] ) ? $args['idType'] : 'global_id';

						$course_id = null;
						switch ( $id_type ) {
							case 'database_id':
								$course_id = absint( $id );
								break;
							case 'global_id':
							default:
								$id_components = Relay::fromGlobalId( $args['id'] );
								if ( empty( $id_components['id'] ) || empty( $id_components['type'] ) ) {
									throw new UserError( __( 'The "id" is invalid', 'wp-graphql-learnpress' ) );
								}
								$course_id = absint( $id_components['id'] );
								break;
						}

						if ( empty( $course_id ) ) {
							/* translators: %1$s: ID type, %2$s: ID value */
							throw new UserError( sprintf( __( 'No course ID was found corresponding to the %1$s: %2$s', 'wp-graphql-learnpress' ), $id_type, $id ) );
						} elseif ( get_post( $course_id )->post_type !== 'lp_course' ) {
							/* translators: %1$s: ID type, %2$s: ID value */
							throw new UserError( sprintf( __( 'No course exists with the %1$s: %2$s', 'wp-graphql-learnpress' ), $id_type, $id ) );
						}

						return Factory::resolve_crud_object( $course_id, $context );
					},
				],
			]
		);
	}
}

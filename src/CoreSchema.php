<?php
/**
 * CoreSchema
 *
 * @author Geoffrey K Taylor <geoffrey.taylor@outlook.com>
 * @see https://github.com/wp-graphql/wp-graphql-woocommerce
 * @license GPL-3
 */

namespace WPGraphQL\Extensions\LearnPress;

use WPGraphQL\AppContext;
use WPGraphQL\Extensions\LearnPress\Data\Loader\LP_PostTypeLoader;

class CoreSchema {
	/**
	 * Register filters
	 */
	public static function add_filters() {
		// Registers CPTs.
		add_filter( 'register_post_type_args', [ __CLASS__, 'register_post_types' ], 10, 2 );
		add_filter( 'graphql_post_entities_allowed_post_types', [ __CLASS__, 'skip_type_registry' ], 10 );

		// Add data-loaders to AppContext.
		add_filter( 'graphql_data_loaders', [ __CLASS__, 'graphql_data_loaders' ], 10, 2 );

		// Add node resolvers.
		add_filter(
			'graphql_resolve_node',
			[ 'WPGraphQL\Extensions\LearnPress\Data\Factory', 'resolve_node' ],
			10,
			4
		);
		add_filter(
			'graphql_resolve_node_type',
			[ 'WPGraphQL\Extensions\LearnPress\Data\Factory', 'resolve_node_type' ],
			10,
			2
		);

		add_filter(
			'graphql_dataloader_pre_get_model',
			[ '\WPGraphQL\Extensions\LearnPress\Data\Loader\LP_PostTypeLoader', 'inject_post_loader_models' ],
			10,
			3
		);


		add_filter(
			'graphql_map_input_fields_to_wp_query',
			[ '\WPGraphQL\Extensions\LearnPress\Connection\CoursesConnection', 'map_input_fields_to_wp_query' ],
			10,
			7
		);
	}

	/**
	 * Registers WooCommerce post-types to be used in GraphQL schema
	 *
	 * @param array  $args      - allowed post-types.
	 * @param string $post_type - name of taxonomy being checked.
	 *
	 * @return array
	 */
	public static function register_post_types( $args, $post_type ) {
		if ( 'lp_course' === $post_type ) {
			$args['show_in_graphql']            = true;
			$args['graphql_single_name']        = 'LpCourse';
			$args['graphql_plural_name']        = 'LpCourses';
			$args['skip_graphql_type_registry'] = true;
		}

		return $args;
	}

	/**
	 * Filters "allowed_post_types" and removed Woocommerce CPTs.
	 *
	 * @param array $post_types  Post types registered in GraphQL schema.
	 *
	 * @return array
	 */
	public static function skip_type_registry( $post_types ) {
		return array_diff(
			$post_types,
			get_post_types(
				[
					'show_in_graphql'            => true,
					'skip_graphql_type_registry' => true,
				]
			)
		);
	}

	/**
	 * Registers data-loaders to be used when resolving LearnPress-related GraphQL types
	 *
	 * @param array      $loaders - assigned loaders.
	 * @param AppContext $context - AppContext instance.
	 *
	 * @return array
	 */
	public static function graphql_data_loaders( $loaders, $context ) {
		// Learnpress CPT loader.
		$cpt_loader         = new LP_PostTypeLoader( $context );
		$loaders['lp_post'] = &$cpt_loader;

		return $loaders;
	}

	public static function lp_post_types() {
		return [
			'lp_course',
			// 'lp_lesson',
			// 'lp_question',
			// 'lp_quiz',
			// 'lp_order',
		];
	}
}

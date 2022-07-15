<?php

namespace WPGraphQL\Extensions\LearnPress\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use LP_Course;
use LP_Course_Filter;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;
use WPGraphQL\Extensions\LearnPress\Data\Connection\CourseConnectionResolver;

class CoursesConnection {

	public static function register_connections() {
		// From RootQuery.
		register_graphql_connection( self::get_connection_config() );
	}

	/**
	 * Given an array of $args, this returns the connection config, merging the provided args
	 * with the defaults.
	 *
	 * @param array $args - Connection configuration.
	 * @return array
	 */
	public static function get_connection_config( $args = [] ): array {
		return array_merge(
			[
				'fromType'       => 'RootQuery',
				'toType'         => 'LpCourse',
				'fromFieldName'  => 'lpCourses',
				'connectionArgs' => self::get_connection_args(),
				'resolve'        => function ( $source, $args, $context, $info ) {
					$resolver = new PostObjectConnectionResolver( $source, $args, $context, $info, 'lp_course' );
					$resolver = new CourseConnectionResolver( $source, $args, $context, $info );

					$resolver = self::set_ordering_query_args( $resolver, $args );

					return $resolver->get_connection();
				},
			],
			$args
		);
	}

	public static function set_ordering_query_args( $resolver, $args ) {
		$backward = isset( $args['last'] ) ? true : false;

		if ( ! empty( $args['where']['orderby'] ) ) {
			foreach ( $args['where']['orderby'] as $orderby_input ) {
				switch ( $orderby_input['field'] ) {
					case '_lp_price':
						$order = $orderby_input['order'];

						if ( $backward ) {
							$order = 'ASC' === $order ? 'DESC' : 'ASC';
						}

						$resolver->set_query_arg( 'orderby', [ 'meta_value_num' => $order ] );
						$resolver->set_query_arg( 'meta_key', esc_sql( $orderby_input['field'] ) );
						$resolver->set_query_arg( 'meta_type', 'NUMERIC' );
						break 2;
				}
			}
		}

		return $resolver;
	}

	/**
	 * Confirms the uses has the privileges to query
	 *
	 * @return bool
	 */
	public static function should_execute() {
		$post_type_obj = get_post_type_object( 'lp_course' );
		switch ( true ) {
			case current_user_can( $post_type_obj->cap->edit_posts ):
				return true;
			default:
				return false;
		}
	}

	/**
	 * Returns array of where args.
	 *
	 * @return array
	 */
	public static function get_connection_args(): array {
		return array_merge(
			cpt_connection_args(),
			[
				'onSale'   => [
					'type'        => 'Boolean',
					'description' => __( 'On sale courses', 'wp-graphql-learnpress' ),
				],
				'featured' => [
					'type'        => 'Boolean',
					'description' => __( 'Featured courses', 'wp-graphql-learnpress' ),
				],
				'orderby'  => [
					'type'        => [ 'list_of' => 'CourseOrderbyInput' ],
					'description' => __( 'What paramater to use to order the objects by.', 'wp-graphql-learnpress' ),
				],
			]
		);
	}

	/**
	 * This allows plugins/themes to hook in and alter what $args should be allowed to be passed
	 * from a GraphQL Query to the WP_Query
	 *
	 * @param array              $query_args The mapped query arguments.
	 * @param array              $where_args       Query "where" args.
	 * @param mixed              $source     The query results for a query calling this.
	 * @param array              $args   All of the arguments for the query (not just the "where" args).
	 * @param AppContext         $context    The AppContext object.
	 * @param ResolveInfo        $info       The ResolveInfo object.
	 * @param mixed|string|array $post_type  The post type for the query.
	 *
	 * @return array Query arguments.
	 */
	public static function map_input_fields_to_wp_query( $query_args, $where_args, $source, $args, $context, $info, $post_type ) {
		$not_course_query = is_string( $post_type )
			? 'lp_course' !== $post_type
			: ! in_array( 'lp_course', $post_type, true );
		if ( $not_course_query ) {
			return $query_args;
		}

		$query_args = array_merge(
			$query_args,
			cpt_map_shared_input_fields_to_wp_query( $where_args )
		);

		if ( ! is_null( $where_args['onSale'] ) && is_bool( $where_args['onSale'] ) ) {
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn
			$on_sale_key = $where_args['onSale'] ? 'post__in' : 'post__not_in';

			$filter              = new LP_Course_Filter();
			$filter->only_fields = [ 'ID' ];
			$filter->sort_by     = [ 'on_sale' ];
			$on_sale_ids         = array_map(function ( $item ) {
				return $item->ID;
			}, LP_Course::get_courses( $filter ));

			$on_sale_ids                = empty( $on_sale_ids ) ? [ 0 ] : $on_sale_ids;
			$query_args[ $on_sale_key ] = $on_sale_ids;
		}

		if ( ! is_null( $where_args['featured'] ) && is_bool( $where_args['featured'] ) ) {
			// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn
			$on_feature_key = $where_args['featured'] ? 'post__in' : 'post__not_in';

			$filter              = new LP_Course_Filter();
			$filter->only_fields = [ 'ID' ];
			$filter->sort_by     = [ 'on_feature' ];
			$on_feature_ids      = array_map(function ( $item ) {
				return $item->ID;
			}, LP_Course::get_courses( $filter ));

			$on_feature_ids                = empty( $on_feature_ids ) ? [ 0 ] : $on_feature_ids;
			$query_args[ $on_feature_key ] = $on_feature_ids;
		}

		/**
		 * Filter the input fields
		 * This allows plugins/themes to hook in and alter what $args should be allowed to be passed
		 * from a GraphQL Query to the WP_Query
		 *
		 * @param array       $args       The mapped query arguments
		 * @param array       $where_args Query "where" args
		 * @param mixed       $source     The query results for a query calling this
		 * @param array       $all_args   All of the arguments for the query (not just the "where" args)
		 * @param AppContext  $context    The AppContext object
		 * @param ResolveInfo $info       The ResolveInfo object
		 * @param mixed|string|array      $post_type  The post type for the query
		 */
		$query_args = apply_filters(
			'graphql_map_input_fields_to_course_query',
			$query_args,
			$where_args,
			$source,
			$args,
			$context,
			$info
		);

		return $query_args;
	}
}

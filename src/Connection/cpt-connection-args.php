<?php
/**
 * Define common connection arguments for CPT connections.
 *
 * @author Geoffrey K Taylor <geoffrey.taylor@outlook.com>
 * @see https://github.com/wp-graphql/wp-graphql-woocommerce
 * @license GPL-3
 */

namespace WPGraphQL\Extensions\LearnPress\Connection;

/**
 * Returns argument definitions for argument common on CPT connections.
 *
 * @return array
 */
function cpt_connection_args(): array {
	return [
		'search'      => [
			'type'        => 'String',
			'description' => __( 'Limit results to those matching a string.', 'wp-graphql-learnpress' ),
		],
		// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
		'exclude'     => [
			'type'        => [ 'list_of' => 'Int' ],
			'description' => __( 'Ensure result set excludes specific IDs.', 'wp-graphql-learnpress' ),
		],
		'include'     => [
			'type'        => [ 'list_of' => 'Int' ],
			'description' => __( 'Limit result set to specific ids.', 'wp-graphql-learnpress' ),
		],
		'orderby'     => [
			'type'        => [ 'list_of' => 'CptOrderbyInput' ],
			'description' => __( 'What paramater to use to order the objects by.', 'wp-graphql-learnpress' ),
		],
		'dateQuery'   => [
			'type'        => 'DateQueryInput',
			'description' => __( 'Filter the connection based on dates.', 'wp-graphql-learnpress' ),
		],
		'parent'      => [
			'type'        => 'Int',
			'description' => __( 'Use ID to return only children. Use 0 to return only top-level items.', 'wp-graphql-learnpress' ),
		],
		'parentIn'    => [
			'type'        => [ 'list_of' => 'Int' ],
			'description' => __( 'Specify objects whose parent is in an array.', 'wp-graphql-learnpress' ),
		],
		'parentNotIn' => [
			'type'        => [ 'list_of' => 'Int' ],
			'description' => __( 'Specify objects whose parent is not in an array.', 'wp-graphql-learnpress' ),
		],
	];
}

/**
 * Sanitizes common post-type connection query input.
 *
 * @param array $input          Input to be sanitize.
 * @param array $ordering_meta  Meta types used for ordering results.
 *
 * @return array
 */
function cpt_map_shared_input_fields_to_wp_query( array $input, $ordering_meta = [] ) {
	$args = [];
	if ( ! empty( $input['include'] ) ) {
		$args['post__in'] = $input['include'];
	}

	// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
	if ( ! empty( $input['exclude'] ) ) {
		// phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn
		$args['post__not_in'] = $input['exclude'];
	}

	if ( ! empty( $input['parent'] ) ) {
		$args['post_parent'] = $input['parent'];
	}

	if ( ! empty( $input['parentIn'] ) ) {
		if ( ! isset( $args['post_parent__in'] ) ) {
			$args['post_parent__in'] = [];
		}
		$args['post_parent__in'] = array_merge( $args['post_parent__in'], $input['parentIn'] );
	}

	if ( ! empty( $input['parentNotIn'] ) ) {
		$args['post_parent__not_in'] = $input['parentNotIn'];
	}

	if ( ! empty( $input['search'] ) ) {
		$args['s'] = $input['search'];
	}

	/**
	 * Map the orderby inputArgs to the WP_Query
	 */
	if ( ! empty( $input['orderby'] ) && is_array( $input['orderby'] ) ) {
		$args['orderby'] = [];
		foreach ( $input['orderby'] as $orderby_input ) {
			/**
			 * These orderby options should not include the order parameter.
			 */
			if ( in_array(
				$orderby_input['field'],
				[ 'post__in', 'post_name__in', 'post_parent__in' ],
				true
			) ) {
				$args['orderby'] = esc_sql( $orderby_input['field'] );

				// Handle meta fields.
			} elseif ( in_array( $orderby_input['field'], $ordering_meta, true ) ) {
				$args['orderby']['meta_value_num'] = $orderby_input['order'];
				$args['meta_key']                  = esc_sql( $orderby_input['field'] );
				// WPCS: slow query ok.

				// Handle post object fields.
			} elseif ( ! empty( $orderby_input['field'] ) ) {
				$args['orderby'][ esc_sql( $orderby_input['field'] ) ] = esc_sql( $orderby_input['order'] );
			}
		}//end foreach
	}//end if

	if ( ! empty( $input['dateQuery'] ) ) {
		$args['date_query'] = $input['dateQuery'];
	}

	return $args;
}

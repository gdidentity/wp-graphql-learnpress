<?php

namespace WPGraphQL\Extensions\LearnPress\Type\Input;

class CourseOrderbyInput {

	public static function register() {
		register_graphql_input_type(
			'CourseOrderbyInput',
			array(
				'description' => __( 'Options for ordering the connection', 'wp-graphql-woocommerce' ),
				'fields'      => array(
					'field' => array(
						'type' => array( 'non_null' => 'CoursesOrderbyEnum' ),
					),
					'order' => array(
						'type' => 'OrderEnum',
					),
				),
			)
		);
	}
}

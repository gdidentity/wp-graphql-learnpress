<?php

namespace WPGraphQL\Extensions\LearnPress\Type\Input;

class CptOrderbyInput {

	public static function register() {
		register_graphql_input_type(
			'CptOrderbyInput',
			array(
				'description' => __( 'Options for ordering the connection', 'wp-graphql-woocommerce' ),
				'fields'      => array(
					'field' => array(
						'type' => array( 'non_null' => 'CptOrderbyEnum' ),
					),
					'order' => array(
						'type' => 'OrderEnum',
					),
				),
			)
		);
	}
}

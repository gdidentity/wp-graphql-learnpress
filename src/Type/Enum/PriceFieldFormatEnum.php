<?php

namespace WPGraphQL\Extensions\LearnPress\Type\Enum;


class PriceFieldFormatEnum {

	public static function register() {
		register_graphql_enum_type(
			'LpPriceFieldFormatEnum',
			[
				'description' => __( 'Price field format enumeration', 'wp-graphql-learnpress' ),
				'values'      => [
					'HTML' => [ 'value' => 'html' ],
					'RAW'  => [ 'value' => 'raw' ],
				],
			]
		);
	}
}

<?php

namespace WPGraphQL\Extensions\LearnPress\Type\Enum;

class CoursesOrderbyEnum extends CptOrderbyEnum {
	/**
	 * Holds ordering enumeration base name.
	 *
	 * @var string
	 */
	protected static $name = 'Courses';

	protected static function values() {
		return array_merge(
			self::post_type_values(),
			[
				'PRICE' => [
					'value'       => '_lp_price',
					'description' => __( 'Order by course price', 'wp-graphql-learnpress' ),
				],
			]
		);
	}
}

<?php
/**
 * WPEnum type - Post_Type_Orderby_Enum
 * Defines common post-type ordering fields
 *
 * @author Geoffrey K Taylor <geoffrey.taylor@outlook.com>
 * @see https://github.com/wp-graphql/wp-graphql-woocommerce
 * @license GPL-3
 */

namespace WPGraphQL\Extensions\LearnPress\Type\Enum;

class CptOrderbyEnum {
	/**
	 * Holds ordering enumeration base name.
	 *
	 * @var string
	 */
	protected static $name = 'Cpt';

	/**
	 * Defines enumeration value definitions for common post-type ordering fields
	 *
	 * @return array
	 */
	protected static function post_type_values() {
		return [
			'SLUG'       => [
				'value'       => 'post_name',
				'description' => __( 'Order by slug', 'wp-graphql-learnpress' ),
			],
			'MODIFIED'   => [
				'value'       => 'post_modified',
				'description' => __( 'Order by last modified date', 'wp-graphql-learnpress' ),
			],
			'DATE'       => [
				'value'       => 'post_date',
				'description' => __( 'Order by publish date', 'wp-graphql-learnpress' ),
			],
			'PARENT'     => [
				'value'       => 'post_parent',
				'description' => __( 'Order by parent ID', 'wp-graphql-learnpress' ),
			],
			'IN'         => [
				'value'       => 'post__in',
				'description' => __( 'Preserve the ID order given in the IN array', 'wp-graphql-learnpress' ),
			],
			'NAME_IN'    => [
				'value'       => 'post_name__in',
				'description' => __( 'Preserve slug order given in the NAME_IN array', 'wp-graphql-learnpress' ),
			],
			'MENU_ORDER' => [
				'value'       => 'menu_order',
				'description' => __( 'Order by the menu order value', 'wp-graphql-learnpress' ),
			],
		];
	}

	/**
	 * Return enumeration values.
	 *
	 * @array
	 */
	protected static function values() {
		return self::post_type_values();
	}

	/**
	 * Registers type
	 */
	public static function register() {
		$name = static::$name;
		register_graphql_enum_type(
			$name . 'OrderbyEnum',
			[
				'description' => sprintf(
					/* translators: ordering enumeration description */
					__( 'Fields to order the %s connection by', 'wp-graphql-learnpress' ),
					$name
				),
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
				'values'      => apply_filters( "{$name}_orderby_enum_values", static::values() ),
			]
		);
	}
}

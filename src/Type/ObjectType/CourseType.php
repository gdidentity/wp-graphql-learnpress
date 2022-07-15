<?php

namespace WPGraphQL\Extensions\LearnPress\Type\ObjectType;

class CourseType {


	public static function register(): void {
		register_graphql_object_type(
			'LpCourse',
			[
				'description' => __( 'LpCourse object', 'wp-graphql-learnpress' ),
				'interfaces'  => [
					'ContentNode',
					'NodeWithTitle',
					'NodeWithContentEditor',
					'NodeWithExcerpt',
					'NodeWithAuthor',
					'NodeWithFeaturedImage',
					'NodeWithComments',
					'NodeWithRevisions',
				],
				'fields'      => [
					'isRequiredEnroll'             => [
						'type'        => 'Boolean',
						'description' => __( 'Required course enroll', 'wp-graphql-learnpress' ),
					],
					'isFree'                       => [
						'type'        => 'Boolean',
						'description' => __( 'Course is free', 'wp-graphql-learnpress' ),
					],
					'onSale'                       => [
						'type'        => 'Boolean',
						'description' => __( 'Course on sale', 'wp-graphql-learnpress' ),
					],
					'regularPrice'                 => [
						'type'        => 'String',
						'description' => __( 'Regular Course price', 'wp-graphql-learnpress' ),
						'args'        => [
							'format' => [
								'type'        => 'LpPriceFieldFormatEnum',
								'description' => __( 'Format of the price', 'wp-graphql-learnpress' ),
							],
						],
						'resolve'     => function ( $source, $args ) {
							if ( isset( $args['format'] ) && 'raw' === $args['format'] ) {
								return $source->regularPrice;
							} else {
								return $source->regularPriceHtml;
							}
						},
					],
					'salePrice'                    => [
						'type'        => 'String',
						'description' => __( 'Sale Course price', 'wp-graphql-learnpress' ),
					],
					'price'                        => [
						'type'        => 'String',
						'description' => __( 'Course price', 'wp-graphql-learnpress' ),
						'args'        => [
							'format' => [
								'type'        => 'LpPriceFieldFormatEnum',
								'description' => __( 'Format of the price', 'wp-graphql-learnpress' ),
							],
						],
						'resolve'     => function ( $source, $args ) {
							if ( isset( $args['format'] ) && 'raw' === $args['format'] ) {
								return $source->price;
							} else {
								return $source->priceHtml;
							}
						},
					],
					'isPurchasable'                => [
						'type'        => 'Boolean',
						'description' => __( 'Course is purchasable', 'wp-graphql-learnpress' ),
					],
					'inStock'                      => [
						'type'        => 'Boolean',
						'description' => __( 'Course is in stock', 'wp-graphql-learnpress' ),
					],
					'maxStudents'                  => [
						'type'        => 'Integer',
						'description' => __( 'Max Number of Students in the Course', 'wp-graphql-learnpress' ),
					],
					'enrolledCount'                => [
						'type'        => 'Integer',
						'description' => __( 'Number of users enrolled the Course', 'wp-graphql-learnpress' ),
					],
					'studentsCount'                => [
						'type'        => 'Integer',
						'description' => __( 'Number of students in the Course', 'wp-graphql-learnpress' ),
					],
					'totalEnrolledCount'           => [
						'type'        => 'Integer',
						'description' => __( 'Total Number of users enrolled the Course', 'wp-graphql-learnpress' ),
					],
					'totalEnrolledOrFinishedCount' => [
						'type'        => 'Integer',
						'description' => __( 'Total Number of users enrolled or finished the Course', 'wp-graphql-learnpress' ),
					],
					'passingCondition'             => [
						'type'        => 'String',
						'description' => __( 'Course passing condition', 'wp-graphql-learnpress' ),
					],
					'duration'                     => [
						'type'        => 'String',
						'description' => __( 'Course duration', 'wp-graphql-learnpress' ),
					],
					'isFeatured'                   => [
						'type'        => 'Boolean',
						'description' => __( 'Course is featured', 'wp-graphql-learnpress' ),
					],
					// 'shipping'   		=> [
					// 	'type'        => 'CountryShipping',
					// 	'description' => __( 'Country Shipping object', 'wp-graphql-learnpress' ),
					// ],
				],
			]
		);

		register_graphql_object_type(
			'CountryShipping',
			[
				'description' => __( 'Country Shipping object', 'wp-graphql-learnpress' ),
				'fields'      => [
					'id'          => [
						'type'        => 'Int',
						'description' => __( 'ID', 'wp-graphql-learnpress' ),
					],
					'description' => [
						'type'        => 'String',
						'description' => __( 'Description', 'wp-graphql-learnpress' ),
					],
					'price'       => [
						'type'        => 'Float',
						'description' => __( 'Price', 'wp-graphql-learnpress' ),
					],
				],
			]
		);
	}
}

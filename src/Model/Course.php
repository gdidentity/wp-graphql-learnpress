<?php

namespace WPGraphQL\Extensions\LearnPress\Model;

use GraphQLRelay\Relay;
use LP_Course;
use WPGraphQL\Extensions\LearnPress\Model\LP_Post;


class Course extends LP_Post {

	public function __construct( $id ) {
		$data = LP_Course::get_course( $id );

		parent::__construct( $data );
	}

	/**
	 * Initializes field resolvers
	 */
	protected function init() {
		if ( empty( $this->fields ) ) {
			parent::init();

			$fields = [
				'id'                           => function () {
					return ! empty( $this->lp_data->get_id() ) ? Relay::toGlobalId( 'lp_course', $this->lp_data->get_id() ) : null;
				},
				'isRequiredEnroll'             => function () {
					return ! empty( $this->lp_data->is_required_enroll() ) ? $this->lp_data->is_required_enroll() : null;
				},
				'isFree'                       => function () {
					return ! is_null( $this->lp_data->is_free() ) ? $this->lp_data->is_free() : null;
				},
				'onSale'                       => function () {
					return ! is_null( $this->lp_data->has_sale_price() ) ? $this->lp_data->has_sale_price() : null;
				},
				'regularPrice'                 => function () {
					return ! is_null( $this->lp_data->get_regular_price() ) ? floatval( $this->lp_data->get_regular_price() ) : null;
				},
				'salePrice'                    => function () {
					return ! is_null( $this->lp_data->get_sale_price() ) ? floatval( $this->lp_data->get_sale_price() ) : null;
				},
				'price'                        => function () {
					return ! is_null( $this->lp_data->get_price() ) ? floatval( $this->lp_data->get_price() ) : null;
				},
				'regularPriceHtml'             => function () {
					return ! empty( $this->lp_data->get_regular_price_html() ) ? $this->lp_data->get_regular_price_html() : null;
				},
				'priceHtml'                    => function () {
					return ! empty( $this->lp_data->get_course_price_html() ) ? $this->lp_data->get_course_price_html() : null;
				},
				'isPurchasable'                => function () {
					return ! is_null( $this->lp_data->is_purchasable() ) ? $this->lp_data->is_purchasable() : null;
				},
				'inStock'                      => function () {
					return ! is_null( $this->lp_data->is_in_stock() ) ? $this->lp_data->is_in_stock() : null;
				},
				'maxStudents'                  => function () {
					return ! is_null( $this->lp_data->get_max_students() ) ? $this->lp_data->get_max_students() : null;
				},
				'enrolledCount'                => function () {
					return ! is_null( $this->lp_data->get_users_enrolled() ) ? $this->lp_data->get_users_enrolled() : null;
				},
				'studentsCount'                => function () {
					return ! is_null( $this->lp_data->count_students() ) ? $this->lp_data->count_students() : null;
				},
				'totalEnrolledCount'           => function () {
					return ! is_null( $this->lp_data->get_total_user_enrolled() ) ? $this->lp_data->get_total_user_enrolled() : null;
				},
				'totalEnrolledOrFinishedCount' => function () {
					return ! is_null( $this->lp_data->get_total_user_enrolled_or_purchased() ) ? $this->lp_data->get_total_user_enrolled_or_purchased() : null;
				},
				'passingCondition'             => function () {
					return ! empty( $this->lp_data->get_passing_condition() ) ? $this->lp_data->get_passing_condition() : null;
				},
				'duration'                     => function () {
					return ! empty( $this->lp_data->get_duration() ) ? $this->lp_data->get_duration() : null;
				},
				'isFeatured'                   => function () {
					return ! is_null( $this->lp_data->is_featured() ) ? $this->lp_data->is_featured() : null;
				},
			];

			$this->fields = array_merge( $this->fields, $fields );
		}
	}
}

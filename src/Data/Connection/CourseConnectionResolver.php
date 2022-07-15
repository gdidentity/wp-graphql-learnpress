<?php

namespace WPGraphQL\Extensions\LearnPress\Data\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;
use WPGraphQL\Extensions\LearnPress\Model\Course;

class CourseConnectionResolver extends PostObjectConnectionResolver {
	public function __construct( $source, array $args, AppContext $context, ResolveInfo $info ) {
		parent::__construct( $source, $args, $context, $info, 'lp_course' );
	}

	public function get_node_by_id( $id ) {
		return new Course( $id );
	}
}

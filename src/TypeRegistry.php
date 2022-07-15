<?php

namespace WPGraphQL\Extensions\LearnPress;

use WPGraphQL;
use WPGraphQL\Extensions\LearnPress;

/**
 * Class TypeRegistry
 */
class TypeRegistry {


	/**
	 * Registers LearnPress types, connections, unions, and mutations to GraphQL schema
	 *
	 * @param \WPGraphQL\Registry\TypeRegistry $type_registry  Instance of the WPGraphQL TypeRegistry.
	 */
	public function init( \WPGraphQL\Registry\TypeRegistry $type_registry ) {

		// Enumerations.
		LearnPress\Type\Enum\CptOrderbyEnum::register();
		LearnPress\Type\Enum\PriceFieldFormatEnum::register();
		LearnPress\Type\Enum\CoursesOrderbyEnum::register();

		// Inputs.
		LearnPress\Type\Input\CptOrderbyInput::register();
		LearnPress\Type\Input\CourseOrderbyInput::register();

		// Objects.
		LearnPress\Type\ObjectType\CourseType::register();

		// Object fields.
		LearnPress\Type\ObjectType\RootQueryType::register();

		// Connections.
		LearnPress\Connection\CoursesConnection::register_connections();
	}
}

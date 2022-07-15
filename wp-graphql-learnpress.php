<?php
/**
 * WPGraphQL LearnPress
 *
 * Plugin Name:       WP GraphQL LearnPress
 * Description:       Query LearnPress in your WP GraphQL schema.
 * Version:           0.0.1
 * Author:            GD IDENTITY
 * Author URI:        https://gdidentity.sk
 * Text Domain:       wp-graphql-learnpress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_GraphQL_LearnPress' ) ) :

	/**
	 * This is the one true wp_graphql_learnpress class
	 */
	final class WP_GraphQL_LearnPress {


		/**
		 * Stores the instance of the WP_GraphQL_LearnPress class
		 *
		 * @since 0.0.1
		 *
		 * @var WP_GraphQL_LearnPress The one true WP_GraphQL_LearnPress
		 */
		private static $instance;

		/**
		 * The instance of the wp_graphql_learnpress object
		 *
		 * @since 0.0.1
		 *
		 * @return WP_GraphQL_LearnPress The one true WP_GraphQL_LearnPress
		 */
		public static function instance(): self {
			if ( ! isset( self::$instance ) && ! ( is_a( self::$instance, __CLASS__ ) ) ) {
				self::$instance = new self();
				self::$instance->setup_constants();
				self::$instance->dependencies();
				if ( self::$instance->includes() ) {
					\WPGraphQL\Extensions\LearnPress\CoreSchema::add_filters();
					self::$instance->registerTypes();
				}
			}

			/**
			 * Fire off init action.
			 *
			 * @param WP_GraphQL_LearnPress $instance The instance of the WP_GraphQL_LearnPress class
			 */
			do_action( 'graphql_learnpress_init', self::$instance );

			// Return the WP_GraphQL_LearnPress Instance.
			return self::$instance;
		}

		/**
		 * Throw error on object clone.
		 * The whole idea of the singleton design pattern is that there is a single object
		 * therefore, we don't want the object to be cloned.
		 *
		 * @since 0.0.1
		 */
		public function __clone() {

			// Cloning instances of the class is forbidden.
			_doing_it_wrong(
				__FUNCTION__,
				esc_html__(
					'The WP_GraphQL_LearnPress class should not be cloned.',
					'wp-graphql-learnpress'
				),
				'0.0.1'
			);
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since 0.0.1
		 */
		public function __wakeup() {

			// De-serializing instances of the class is forbidden.
			_doing_it_wrong(
				__FUNCTION__,
				esc_html__(
					'De-serializing instances of the WP_GraphQL_LearnPress class is not allowed.',
					'wp-graphql-learnpress'
				),
				'0.0.1'
			);
		}

		/**
		 * Setup plugin constants.
		 *
		 * @since 0.0.1
		 */
		private function setup_constants(): void {

			// Plugin version.
			if ( ! defined( 'WPGRAPHQL_LEARNPRESS_VERSION' ) ) {
				define( 'WPGRAPHQL_LEARNPRESS_VERSION', '0.0.1' );
			}

			// Plugin Folder Path.
			if ( ! defined( 'WPGRAPHQL_LEARNPRESS_PLUGIN_DIR' ) ) {
				define( 'WPGRAPHQL_LEARNPRESS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'WPGRAPHQL_LEARNPRESS_PLUGIN_URL' ) ) {
				define( 'WPGRAPHQL_LEARNPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'WPGRAPHQL_LEARNPRESS_PLUGIN_FILE' ) ) {
				define( 'WPGRAPHQL_LEARNPRESS_PLUGIN_FILE', __FILE__ );
			}

			// Whether to autoload the files or not.
			if ( ! defined( 'WPGRAPHQL_LEARNPRESS_AUTOLOAD' ) ) {
				define( 'WPGRAPHQL_LEARNPRESS_AUTOLOAD', true );
			}
		}

		/**
		 * Uses composer's autoload to include required files.
		 *
		 * @since 0.0.1
		 *
		 * @return bool
		 */
		private function includes(): bool {

			// Autoload Required Classes.
			if ( defined( 'WPGRAPHQL_LEARNPRESS_AUTOLOAD' ) && false !== WPGRAPHQL_LEARNPRESS_AUTOLOAD ) {
				if ( file_exists( WPGRAPHQL_LEARNPRESS_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
					require_once WPGRAPHQL_LEARNPRESS_PLUGIN_DIR . 'vendor/autoload.php';
				}

				// Bail if installed incorrectly.
				if ( ! class_exists( '\WPGraphQL\Extensions\LearnPress\TypeRegistry' ) ) {
					add_action( 'admin_notices', [ $this, 'vendors_missing_notice' ] );
					return false;
				}
			}

			return true;
		}

		/**
		 * Class dependencies.
		 *
		 * @since 0.0.1
		 */
		private function dependencies(): void {

			// Checks if WPGraphQL is installed.
			if ( ! class_exists( 'WPGraphQL' ) ) {
				add_action( 'admin_notices', [ $this, 'wpgraphql_missing_notice' ] );
				return;
			}

			// Checks if LearnPress is installed.
			if ( ! class_exists( 'LearnPress' ) ) {
				add_action( 'admin_notices', [ $this, 'learnpress_missing_notice' ] );
				return;
			}
		}

		/**
		 * WPGraphQL LearnPress vendors missing notice.
		 *
		 * @since 0.0.1
		 */
		public function vendors_missing_notice(): void {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			} ?>
			<div class="notice notice-error">
				<p>
					<?php esc_html_e( 'WPGraphQL LearnPress appears to have been installed without its dependencies. It will not work properly until dependencies are installed. This likely means you have cloned WPGraphQL LearnPress from Github and need to run the command `composer install`.', 'wp-graphql-learnpress' ); ?>
				</p>
			</div>
			<?php
		}

		/**
		 * WPGraphQL missing notice.
		 *
		 * @since 0.0.1
		 */
		public function wpgraphql_missing_notice(): void {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			?>
			<div class="notice notice-error">
				<p><strong><?php esc_html_e( 'WP GraphQL LearnPress', 'wp-graphql-learnpress' ); ?></strong> <?php esc_html_e( 'depends on the latest version of WPGraphQL to work!', 'wp-graphql-learnpress' ); ?></p>
			</div>
			<?php
		}

		/**
		 * LearnPress missing notice.
		 *
		 * @since 0.0.1
		 */
		public function learnpress_missing_notice(): void {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			?>
			<div class="notice notice-error">
				<p><strong><?php esc_html_e( 'WP GraphQL LearnPress', 'wp-graphql-learnpress' ); ?></strong> <?php esc_html_e( 'depends on the latest version of LearnPress to work!', 'wp-graphql-learnpress' ); ?></p>
			</div>
			<?php
		}


		/**
		 * Register Types.
		 *
		 * @since 0.0.1
		 */
		private function registerTypes(): void {

			// Initialize LearnPress TypeRegistry.
			$registry = new \WPGraphQL\Extensions\LearnPress\TypeRegistry();
			add_action( 'graphql_register_types', [ $registry, 'init' ], 10, 1 );
		}
	}

endif;

/**
 * Function that instantiates the plugin main class.
 *
 * @since 0.0.1
 *
 * @return WP_GraphQL_LearnPress The one true WP_GraphQL_LearnPress
 */
function wp_graphql_learnpress_init(): \WP_GraphQL_LearnPress {

	// Return an instance of the action.
	return \WP_GraphQL_LearnPress::instance();
}


add_action( 'graphql_init', 'wp_graphql_learnpress_init' );


add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	$links[] = '<a href="/wp-admin/admin.php?page=wp-graphql-learnpress">Settings</a>';

	return $links;
});


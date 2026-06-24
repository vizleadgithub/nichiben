<?php
#[AllowDynamicProperties]
class WP_Fatal_Error_Handler {

	/**
	 * Runs the shutdown handler.
	 *
	 * This method is registered via `register_shutdown_function()`.
	 *
	 * @since 5.2.0
	 */
	public function handle() {
		if ( defined( 'WP_SANDBOX_SCRAPING' ) && WP_SANDBOX_SCRAPING ) {
			return;
		}

		// Do not trigger the fatal error handler while updates are being installed.
		if ( wp_is_maintenance_mode() ) {
			return;
		}

		try {
			// Bail if no error found.
			$error = $this->detect_error();
			if ( ! $error ) {
				return;
			}

			if ( ! isset( $GLOBALS['wp_locale'] ) && function_exists( 'load_default_textdomain' ) ) {
				load_default_textdomain();
			}

			$handled = false;

			if ( ! is_multisite() && wp_recovery_mode()->is_initialized() ) {
				$handled = wp_recovery_mode()->handle_error( $error );
			}

			// Display the PHP error template if headers not sent.
			if ( is_admin() || ! headers_sent() ) {
				$this->display_error_template( $error, $handled );
			}
		} catch ( Exception $e ) {
			// Catch exceptions and remain silent.
		}
	}

	/**
	 * Detects the error causing the crash if it should be handled.
	 *
	 * @since 5.2.0
	 *
	 * @return array|null Error information returned by `error_get_last()`, or null
	 *                    if none was recorded or the error should not be handled.
	 */
	protected function detect_error() {
		$error = error_get_last();

		// No error, just skip the error handling code.
		if ( null === $error ) {
			return null;
		}

		// Bail if this error should not be handled.
		if ( ! $this->should_handle_error( $error ) ) {
			return null;
		}

		return $error;
	}
	protected function should_handle_error( $error ) {
		$error_types_to_handle = array(
			E_ERROR,
			E_PARSE,
			E_USER_ERROR,
			E_COMPILE_ERROR,
			E_RECOVERABLE_ERROR,
		);

		if ( isset( $error['type'] ) && in_array( $error['type'], $error_types_to_handle, true ) ) {
			return true;
		}
		return (bool) apply_filters( 'wp_should_handle_php_error', false, $error );
	}

	protected function display_error_template( $error, $handled ) {
		if ( defined( 'WP_CONTENT_DIR' ) ) {
			// Load custom PHP error template, if present.
			$php_error_pluggable = WP_CONTENT_DIR . '/php-error.php';
			if ( is_readable( $php_error_pluggable ) ) {
				require_once $php_error_pluggable;

				return;
			}
		}

		// Otherwise, display the default error template.
		$this->display_default_error_template( $error, $handled );
	}

	/**
	 * Displays the default PHP error template.
	 *
	 * This method is called conditionally if no 'php-error.php' drop-in is available.
	 *
	 * It calls {@see wp_die()} with a message indicating that the site is experiencing technical difficulties and a
	 * login link to the admin backend. The {@see 'wp_php_error_message'} and {@see 'wp_php_error_args'} filters can
	 * be used to modify these parameters.
	 *
	 * @since 5.2.0
	 * @since 5.3.0 The `$handled` parameter was added.
	 *
	 * @param array         $error   Error information retrieved from `error_get_last()`.
	 * @param true|WP_Error $handled Whether Recovery Mode handled the fatal error.
	 */
	protected function display_default_error_template( $error, $handled ) {
		if ( ! function_exists( '__' ) ) {
			wp_load_translations_early();
		}

		if ( ! function_exists( 'wp_die' ) ) {
			require_once ABSPATH . WPINC . '/functions.php';
		}

		if ( ! class_exists( 'WP_Error' ) ) {
			require_once ABSPATH . WPINC . '/class-wp-error.php';
		}

		if ( true === $handled && wp_is_recovery_mode() ) {
			$message = __( 'There has been a critical error on this website, putting it in recovery mode. Please check the Themes and Plugins screens for more details. If you just installed or updated a theme or plugin, check the relevant page for that first.' );
		} elseif ( is_protected_endpoint() && wp_recovery_mode()->is_initialized() ) {
			if ( is_multisite() ) {
				$message = __( 'There has been a critical error on this website. Please reach out to your site administrator, and inform them of this error for further assistance.' );
			} else {
				$message = __( 'There has been a critical error on this website. Please check your site admin email inbox for instructions.' );
			}
		} else {
			$message = __( 'There has been a critical error on this website.' );
		}

		$message = "";

		$args = array(
			'response' => 500,
			'exit'     => false,
		);

		/**
		 * Filters the message that the default PHP error template displays.
		 *
		 * @since 5.2.0
		 *
		 * @param string $message HTML error message to display.
		 * @param array  $error   Error information retrieved from `error_get_last()`.
		 */
		$message = apply_filters( 'wp_php_error_message', $message, $error );

		/**
		 * Filters the arguments passed to {@see wp_die()} for the default PHP error template.
		 *
		 * @since 5.2.0
		 *
		 * @param array $args Associative array of arguments passed to `wp_die()`. By default these contain a
		 *                    'response' key, and optionally 'link_url' and 'link_text' keys.
		 * @param array $error Error information retrieved from `error_get_last()`.
		 */
		$args = apply_filters( 'wp_php_error_args', $args, $error );

		$wp_error = new WP_Error(
			'internal_server_error',
			$message,
			array(
				'error' => $error,
			)
		);
var_dump($wp_error);
var_dump($args);
exit();
		//wp_die( $wp_error, '', $args );
	}
}

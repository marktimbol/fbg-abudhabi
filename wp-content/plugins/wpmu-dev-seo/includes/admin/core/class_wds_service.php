<?php

abstract class WDS_Service {


	/**
	 * Service URL implementation
	 *
	 * @return string Remote service URL
	 */
	abstract public function get_service_base_url ();

	/**
	 * Get the full URL to perform the service request
	 *
	 * @param string $verb Action string
	 *
	 * @return mixed Full URL as string or (bool)false on failure
	 */
	abstract public function get_request_url ($verb);

	/**
	 * Spawn the arguments for WP HTTP API request call
	 *
	 * @param string $verb Action string
	 *
	 * @return mixed Array of WP HTTP API arguments on success, or (bool)false on failure
	 */
	abstract public function get_request_arguments ($verb);

	/**
	 * Returns a flat list of known verbs as strings
	 *
	 * @return array
	 */
	abstract public function get_known_verbs ();

	/**
	 * Determine if the action verb is able to be locally cached
	 *
	 * @param string $verb Action string
	 *
	 * @return bool
	 */
	abstract public function is_cacheable_verb ($verb);

	/**
	 * Handles error response (non-200) from service
	 *
	 * @param object $response WP HTTP API response
	 */
	abstract public function handle_error_response ($response);


	const INTERMEDIATE_CACHE_EXPIRY = 600;
	const ERR_CACHE_EXPIRY = 300;

	const SERVICE_UPTIME = 'uptime';
	const SERVICE_SEO = 'seo';

	private $_errors = array();

	/**
	 * Service factory method
	 *
	 * @param string $type Requested service type
	 *
	 * @return object WDS_Service Service instance
	 */
	public static function get ($type) {
		$type = !empty($type) && in_array($type, array(self::SERVICE_SEO, self::SERVICE_UPTIME))
			? $type
			: self::SERVICE_SEO
		;
		$class_name = $file_name = false;
		if ($type === self::SERVICE_UPTIME) {
			$class_name = 'WDS_Uptime_Service';
			$file_name = 'class_wds_uptime_service';
		} else {
			$class_name = 'WDS_Seo_Service';
			$file_name = 'class_wds_seo_service';
		}
		if (!class_exists($class_name)) require_once (dirname(__FILE__) . "/service/{$file_name}.php");
		return new $class_name;
	}

	/**
	 * Check if we have dashboard installed
	 *
	 * @return bool
	 */
	public function has_dashboard () {
		return (bool)apply_filters(
			$this->get_filter('has_dashboard'),
			$this->is_dahsboard_active() && $this->has_dashboard_key()
		);
	}

	/**
	 * Check if the user can access service functionality
	 *
	 * @return bool
	 */
	public function can_access () {
		$can_access = false;
		if (!$this->has_dashboard()) {
			$can_access = $this->can_install();
		} else if (class_exists('WPMUDEV_Dashboard') && !empty(WPMUDEV_Dashboard::$site) && is_callable(array(WPMUDEV_Dashboard::$site, 'allowed_user'))) {
			$can_access = WPMUDEV_Dashboard::$site->allowed_user();
		}
		return (bool)apply_filters(
			$this->get_filter('can_access'),
			$can_access
		);
	}

	/**
	 * Check if the user can install dashboard
	 *
	 * @return bool
	 */
	public function can_install () {
		$can_install = is_multisite()
			? current_user_can('manage_network_options')
			: current_user_can('manage_options')
		;
		return (bool)apply_filters(
			$this->get_filter('can_install'),
			$can_install
		);
	}

	/**
	 * Check if we have WPMU DEV Dashboard plugin installed and activated
	 *
	 * @return bool
	 */
	public function is_dahsboard_active () {
		return (bool)apply_filters(
			$this->get_filter('is_dahsboard_active'),
			class_exists('WPMUDEV_Dashboard')
		);
	}

	/**
	 * Check if we have our API key
	 *
	 * If we do, this means the user has logged into the dashboard
	 *
	 * @return bool
	 */
	public function has_dashboard_key () {
		$key = $this->get_dashboard_api_key();
		return (bool)apply_filters(
			$this->get_filter('has_dashboard_key'),
			!empty($key)
		);
	}

	/**
	 * Actual dashborad API key getter.
	 *
	 * @return string Dashboard API key
	 */
	public function get_dashboard_api_key () {
		return apply_filters(
			$this->get_filter('api_key'),
			get_site_option('wpmudev_apikey', false)
		);
	}

	/**
	 * Get cache expiry, in seconds
	 *
	 * @param int $expiry Expiry time to approximate
	 *
	 * @return int Cache expiry time, in seconds
	 */
	public function get_cache_expiry ($expiry=false) {
		$expiry = !empty($expiry) && is_numeric($expiry)
			? (int)$expiry
			: self::INTERMEDIATE_CACHE_EXPIRY
		;
		return (int)apply_filters(
			$this->get_filter('cache_expiry'),
			$expiry
		);
	}

	/**
	 * Get the key used for caching
	 *
	 * @param string $key Key suffix
	 *
	 * @return mixed Full cache key as string, or (bool)false on failure
	 */
	public function get_cache_key ($key) {
		if (empty($key)) return false;
		return $this->get_filter($key);
	}

	/**
	 * Get cached value corresponding to internal key
	 *
	 * @param string $key Key to check
	 *
	 * @return mixed Cached value, or (bool)false on failure
	 */
	public function get_cached ($key) {
		$key = $this->get_cache_key($key);
		if (empty($key)) return false;
		return get_transient($key);
	}

	/**
	 * Special case error cache getter
	 *
	 * @param string $verb Verb to check cached errors for
	 *
	 * @return mixed Cached error or (bool) false
	 */
	public function get_cached_error ($verb) {
		if (empty($verb)) return false;

		return $this->get_cached("{$verb}-error");
	}

	/**
	 * Sets cached value to the corresponding key
	 *
	 * @param string $key Key for the value to set
	 * @param mixed $value Value to set
	 * @param int $expiry Optional expiry time, in secs (one of the class expiry constants)
	 *
	 * @return bool
	 */
	public function set_cached ($key, $value, $expiry=false) {
		$key = $this->get_cache_key($key);
		if (empty($key)) return false;
		return set_transient($key, $value, $this->get_cache_expiry($expiry));
	}

	/**
	 * Special case error cache setter
	 *
	 * @param string $verb Verb to set error cache for
	 * @param mixed $error Error to set
	 *
	 * @return bool
	 */
	public function set_cached_error ($verb, $error) {
		if (empty($verb)) return false;

		return $this->set_cached("{$verb}-error", $error, self::ERR_CACHE_EXPIRY);
	}

	/**
	 * Clears the value from cache
	 *
	 * @param string $key Key for the value to clear
	 *
	 * @return bool
	 */
	public function clear_cached ($key) {
		$key = $this->get_cache_key($key);
		if (empty($key)) return false;
		return delete_transient($key);
	}


	/**
	 * Filter/action name getter
	 *
	 * @param string $filter Filter name to convert
	 *
	 * @return string Full filter name
	 */
	public function get_filter ($filter=false) {
		if (empty($filter)) return false;
		if (!is_string($filter)) return false;
		return 'wds-model-service-' . $filter;
	}

	/**
	 * Actually send out remote request
	 *
	 * @param string $verb Service endpoint to call
	 *
	 * @return mixed Service response hash on success, (bool)false on failure
	 */
	protected function _remote_call ($verb) {
		if (empty($verb) || !in_array($verb, $this->get_known_verbs())) return false;

		$cacheable = $this->is_cacheable_verb($verb);

		if ($cacheable) {
			$cached = $this->get_cached($verb);
			if (false !== $cached) {
				WDS_Logger::debug("Fetching [{$verb}] result from cache.");
				return $cached;
			}
		}

		// Check to see if we have a valid error cache still
		$error = $this->get_cached_error($verb);
		if (false !== $error && !empty($error)) {
			WDS_Logger::debug("Error cache still in effect for [{$verb}]");
			$errors = is_array($error) ? $error : array($error);
			foreach ($errors as $err) {
				$this->_set_error($err);
			}
			return false;
		}

		$remote_url = $this->get_request_url($verb);
		if (empty($remote_url)) {
			WDS_Logger::warning("Unable to construct endpoint URL for [{$verb}].");
			return false;
		}

		$request_arguments = $this->get_request_arguments($verb);
		if (empty($request_arguments)) {
			WDS_Logger::warning("Unable to obtain request arguments for [{$verb}].");
			return false;
		}

		WDS_Logger::debug("Sending a remote request to [{$remote_url}] ({$verb})");
		$response = wp_remote_request($remote_url, $request_arguments);
		if (is_wp_error($response)) {
			WDS_Logger::error("We were not able to communicate with [{$remote_url}] ({$verb}).");
			if (is_callable(array($response, 'get_error_messages'))) {
				$msgs = $response->get_error_messages();
				foreach ($msgs as $msg) {
					$this->_set_error($msg);
				}
				$this->set_cached_error($verb, $msgs);
			}
			return false;
		}

		$response_code = (int)wp_remote_retrieve_response_code($response);
		if (200 !== $response_code) {
			WDS_Logger::error("We had an error communicating with [{$remote_url}]:[{$response_code}] ({$verb}).");
			$this->handle_error_response($response);
			return false;
		}

		$body = wp_remote_retrieve_body($response);
		$result = $this->_postprocess_response($body);

		if ($cacheable) {
			WDS_Logger::debug("Setting cache for [{$verb}]");
			$this->set_cached($verb, $result);
		}

		return $result;
	}

	/**
	 * Post-process the response body
	 *
	 * Passthrough as default implementation
	 *
	 * @param string $body Response body
	 *
	 * @return mixed
	 */
	protected function _postprocess_response ($body) { return json_decode($body, true); }


	/**
	 * Actually perform a request on behalf of the implementing service
	 *
	 * @param string $verb Action string
	 *
	 * @return mixed Service response hash on success, (bool)false on failure
	 */
	public function request ($verb) {
		$response = $this->_remote_call($verb);
		return apply_filters(
			$this->get_filter("request-{$verb}"),
			apply_filters(
				$this->get_filter('request'),
				$response, $verb
			)
		);
	}

	/**
	 * Adds error message to the errors queue
	 *
	 * @param string $msg Error message
	 */
	protected function _set_error ($msg) {
		WDS_Logger::error($msg);
		$this->_errors[] = $msg;
	}

	/**
	 * Silently Sets all errors
	 *
	 * @param array $errs Errors to set
	 */
	protected function _set_all_errors ($errs) {
		if (!is_array($errs)) return false;
		$this->_errors = $errs;
	}

	/**
	 * Gets all error message strings
	 *
	 * @return array
	 */
	public function get_errors () {
		return (array)$this->_errors;
	}

	/**
	 * Checks if we have any errors this far
	 *
	 * @return bool
	 */
	public function has_errors () {
		return !empty($this->_errors);
	}

	/**
	 * Check if status code is within radix
	 *
	 * @param int $code Code to check
	 * @param int $base Base to check
	 * @param int $radix Optional increment
	 *
	 * @return bool
	 */
	public static function is_code_within ($code, $base, $radix=10) {
		$code = (int)$code;
		$base = (int)$base;
		$radix = (int)$radix;
		if (!$code || !$base || !$radix) return false;

		$min = $base * $radix;
		$max = (($base + 1) * $radix) - 1;

		return $code >= $min && $code <= $max;
	}

}
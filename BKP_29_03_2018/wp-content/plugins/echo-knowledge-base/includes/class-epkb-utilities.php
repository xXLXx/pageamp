<?php

/**
 * Various utility functions
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_Utilities {


	/**************************************************************************************************************************
	 *
	 *                     POST OPERATIONS
	 *
	 **************************************************************************************************************************/

	/**
	 * Retrieve a KB article with security checks
	 *
	 * @param $post_id
	 * @return null|WP_Post - return null if this is NOT KB post
	 */
	public static function get_kb_post_secure( $post_id ) {

		if ( empty($post_id) ) {
			return null;
		}

		// ensure post_id is valid
		$post_id = EPKB_Utilities::sanitize_int( $post_id );
		if ( empty($post_id) ) {
			return null;
		}

		// retrieve the post and ensure it is one
		$post = get_post( $post_id );
		if ( empty($post) || ! is_object($post) || ! $post instanceof WP_Post ) {
			return null;
		}

		// verify it is a KB article
		if ( ! EPKB_KB_Handler::is_kb_post_type( $post->post_type ) ) {
			return null;
		}

		// allow only public articles
		if ( $post->post_status != 'publish' ) {
			return null;
		}

		return $post;
	}

	public static function adjust_post_title( $post_title, $max_len=45 ) {
		$title = empty( $post_title ) ? '[No Title]' : $post_title;
		$title = strlen( $title ) > $max_len ? substr( $title, 0, $max_len) . ' [...]' : $title;
		return $title;
	}

	public static function get_post_status_text( $post_status ) {

		$post_statuses = array( 'draft' => __( 'Draft', 'echo-knowledge-base' ), 'pending' => __( 'Pending', 'echo-knowledge-base' ),
		                        'publish' => __( 'Published', 'echo-knowledge-base' ), 'future' => __( 'Scheduled', 'echo-knowledge-base' ),
								'private' => __( 'Private', 'echo-knowledge-base' ) );

		if ( empty($post_status) || ! in_array($post_status, array_keys($post_statuses)) ) {
			return $post_status;
		}

		return $post_statuses[$post_status];
	}


	/**************************************************************************************************************************
	 *
	 *                     STRING OPERATIONS
	 *
	 **************************************************************************************************************************/

	/**
	 * PHP substr() function returns FALSE if the input string is empty. This method
	 * returns empty string if input is empty or if error occurs.
	 *
	 * @param $string
	 * @param $start
	 * @param null $length
	 *
	 * @return string
	 */
	public static function substr( $string, $start, $length=null ) {
		$result = substr($string, $start, $length);
		return empty($result) ? '' : $result;
	}

	/**************************************************************************************************************************
	 *
	 *                     NUMBER OPERATIONS
	 *
	 **************************************************************************************************************************/

	/**
	 * Determine if value is positive integer ( > 0 )
	 * @param int $number is check
	 * @return bool
	 */
	public static function is_positive_int( $number ) {

		// no invalid format
		if ( empty($number) || ! is_numeric($number) ) {
			return false;
		}

		// no non-digit characters
		$numbers_only = preg_replace('/\D/', "", $number );
		if ( empty($numbers_only) || $numbers_only != $number ) {
			return false;
		}

		// only positive
		return $numbers_only > 0;
	}

	/**
	 * Determine if value is positive integer
	 * @param int $number is check
	 * @return bool
	 */
	public static function is_positive_or_zero_int( $number ) {

		if ( ! isset($number) || ! is_numeric($number) ) {
			return false;
		}

		if ( ( (int) $number) != ( (float) $number )) {
			return false;
		}

		$number = (int) $number;

		return is_int($number);
	}


	/**************************************************************************************************************************
	 *
	 *                     DATE OPERATIONS
	 *
	 **************************************************************************************************************************/

	/**
	 * Retrieve specific format from given date-time string e.g. '10-16-2003 10:20:01' becomes '10-16-2003'
	 *
	 * @param $datetime_str
	 * @param string $format e.g. 'Y-m-d H:i:s'  or  'M j, Y'
	 *
	 * @return string formatted date or the original string
	 */
	public static function get_formatted_datetime_string( $datetime_str, $format='M j, Y' ) {

		if ( empty($datetime_str) || empty($format) ) {
			return $datetime_str;
		}

		$time = strtotime($datetime_str);
		if ( empty($time) ) {
			return $datetime_str;
		}

		$date_time = date_i18n($format, $time);
		if ( $date_time == $format ) {
			$date_time = $datetime_str;
		}

		return empty($date_time) ? $datetime_str : $date_time;
	}

	/**
	 * Get nof hours passed between two dates.
	 *
	 * @param string $date1
	 * @param string $date2 OR if empty then use current date
	 *
	 * @return int - number of hours between dates [0-x] or null if error
	 */
	public static function get_hours_since( $date1, $date2='' ) {

		try {
			$date1_dt = new DateTime( $date1 );
			$date2_dt = new DateTime( $date2 );
		} catch(Exception $ex) {
			return null;
		}

		if ( empty($date1_dt) || empty($date2_dt) ) {
			return null;
		}

		$hours = date_diff($date1_dt, $date2_dt)->h;

		return $hours === false ? null : $hours;
	}

	/**
	 * Get nof days passed between two dates.
	 *
	 * @param string $date1
	 * @param string $date2 OR if empty then use current date
	 *
	 * @return int - number of days between dates [0-x] or null if error
	 */
	public static function get_days_since( $date1, $date2='' ) {

		try {
			$date1_dt = new DateTime( $date1 );
			$date2_dt = new DateTime( $date2 );
		} catch(Exception $ex) {
			return null;
		}

		if ( empty($date1_dt) || empty($date2_dt) ) {
			return null;
		}

		$days = date_diff($date1_dt, $date2_dt)->days;

		return $days === false ? null : $days;
	}


	/**
	 * How long ago pass date occurred.
	 *
	 * @param string $date1
	 *
	 * @return string x year|month|week|day|hour|minute|second(s) or '[unknown]' on error
	 */
	public static function time_since_today( $date1 ) {
		return self::how_long_ago( $date1 );
	}

	/**
	 * How long ago since now.
	 *
	 * @param string $date1
	 * @param string $date2 or if empty use current time
	 *
	 * @return string x year|month|week|day|hour|minute|second(s) or '[unknown]' on error
	 */
	public static function how_long_ago( $date1, $date2='' ) {

		$time1 = strtotime($date1);
		$time2 = empty($date2) ? time() : strtotime($date2);
		if ( empty($time1) || empty($time2) ) {
			return '[unknown]';
		}

		$time = abs($time2 - $time1);
		$time = ( $time < 1 )? 1 : $time;
		$tokens = array (
			31536000 => __( 'year', 'echo-knowledge-base' ),
			2592000 => __( 'month', 'echo-knowledge-base' ),
			604800 => __( 'week', 'echo-knowledge-base' ),
			86400 => __( 'day', 'echo-knowledge-base' ),
			3600 => __( 'hour', 'echo-knowledge-base' ),
			60 => __( 'min', 'echo-knowledge-base' ),
			1 => __( 'sec', 'echo-knowledge-base' )
		);

		$output = '';
		foreach ($tokens as $unit => $text) {
			if ($time >= $unit) {
				$numberOfUnits = floor($time / $unit);
				$output =  $numberOfUnits . ' ' . $text . ( $numberOfUnits >1 ? 's' : '');
				break;
			}
		}

		return $output;
	}


	/**************************************************************************************************************************
	 *
	 *                     NOTICES
	 *
	 *************************************************************************************************************************/

	/**
	 * Display content (not message).
	 *
	 * @param $message
	 */
	public static function ajax_show_content( $message ) {
		wp_die( json_encode( array( 'message' => $message ) ) );
	}

	/**
	 * AJAX: Used on response back to JS. will call wp_die()
	 *
	 * @param string $message
	 * @param string $title
	 * @param string $type
	 */
	public static function ajax_show_info_die( $message, $title='', $type='success' ) {
		wp_die( json_encode( array( 'message' => self::get_bottom_notice_message_box( $message, $title, $type) ) ) );
	}

	/**
	 * AJAX: Used on response back to JS. will call wp_die()
	 *
	 * @param $message
	 * @param string $title
	 */
	public static function ajax_show_error_die( $message, $title='' ) {
		wp_die( json_encode( array( 'error' => true, 'message' => self::get_bottom_notice_message_box( $message, $title, 'error') ) ) );
	}

	/**
	 * Show info or error message to the user
	 *
	 * @param $message
	 * @param string $title
	 * @param string $type
	 *
	 * @return string
	 */
	public static function get_bottom_notice_message_box($message, $title='', $type='success' ) {
		/* array $EZSQL_ERROR */
		global $EZSQL_ERROR;

		if ( ! empty($EZSQL_ERROR ) ) {
			EPKB_Logging::add_log( 'Database error', $EZSQL_ERROR );
			$message .= '. Database Error.';
		}

		$title = empty($title) ? '' : '<h4>' . $title . '</h4>';
		$message = empty($message) ? '' : $message;
		return
			"<div class='eckb-bottom-notice-message'>
				<div class='contents'>
					<span class='$type'>
						$title
						<p> " . wp_kses_post($message) . "</p>
					</span>
				</div>
			</div>";
	}

	/**
	 * Show admin notice at the top of page and redirect
	 *
	 * @param $msg_code
	 * @param string $redirect
	 * @param string $param
	 */
	public static function show_top_level_admin_msg_and_redirect( $msg_code, $redirect='admin.php', $param='' ) {
		$url = empty( $_REQUEST['epkb_redirect'] ) ? admin_url( $redirect ) : $_REQUEST['epkb_redirect'];

		$query = array();
		$query['epkb_admin_notice'] = urlencode($msg_code);
		if ( ! empty($param) ) {
			$query['epkb_notice_param'] = $param;
		}

		$redirect = add_query_arg( $query, $url );
		wp_safe_redirect( $redirect );  // sanitizes the $redirect
		defined('EPKB_TESTING') ? wp_die($msg_code) : die();
	}

	/**
	 * Redirect
	 *
	 * @param string $redirect
	 */
	public static function admin_redirect( $redirect='admin.php' ) {
		$url = empty( $_REQUEST['epkb_redirect'] ) ? admin_url( $redirect ) : $_REQUEST['epkb_redirect'];
		$query = array();
		$redirect = add_query_arg( $query, $url );
		wp_safe_redirect( $redirect );  // sanitizes the $redirect
		defined('EPKB_TESTING') ? wp_die() : die();
	}

	public static function user_not_logged_in() {
		self::ajax_show_error_die( '<p>' . __( 'You are not logged in. Refresh your page and log in.', 'echo-knowledge-base' ) . '</p>', 'Cannot save your changes' );
	}

	/**
	 * Show on the page error message on page load.
	 *
	 * @param string $error_msg
	 */
	public static function output_inline_error_notice( $error_msg ) {
		echo '<div class="eckb-inline-error-notice">
                <div class="eckb-inline-error-notice-contents">
                ' . $error_msg . '
				</div>
			  </div>';
	}


	/**************************************************************************************************************************
	 *
	 *                     SECURITY
	 *
	 *************************************************************************************************************************/

	/**
	 * Return digits only.
	 *
	 * @param $number
	 * @param int $default
	 * @return int <default>
	 */
	public static function sanitize_int( $number, $default=0 ) {

		if ( $number === null || ! is_numeric($number) ) {
			return $default;
		}

		$number = preg_replace('/\D/', '', $number);

		return empty($number) ? $default : (int) $number;
	}

	/**
	 * Return text, space, "-" and "_" only.
	 *
	 * @param $text
	 * @param String $default
	 * @return String|<default>
	 */
	public static function sanitize_english_text( $text, $default='' ) {

		if ( empty($text) || ! is_string($text) ) {
			return $default;
		}

		$text = preg_replace('/[^A-Za-z0-9 \-_]/', '', $text);

		return empty($text) ? $default : $text;
	}

	/**
	 * Retrieve ID or return error. Used for KB ID and other IDs.
	 *
	 * @param mixed $id is either $id number or array with 'id' index
	 *
	 * @return int|WP_Error
	 */
	public static function sanitize_get_id( $id ) {

		if ( empty( $id) || is_wp_error($id) ) {
			EPKB_Logging::add_log( 'Error occurred (01)' );
			return new WP_Error('E001', "invalid ID");
		}

		if ( is_array( $id) ) {
			if ( ! isset( $id['id']) ) {
				EPKB_Logging::add_log( 'Error occurred (02)' );
				return new WP_Error('E002', "invalid ID");
			}

			$id_value = $id['id'];
			if ( ! self::is_positive_int( $id_value ) ) {
				EPKB_Logging::add_log( 'Error occurred (03)', $id_value );
				return new WP_Error('E003', "invalid ID: " . self::get_variable_string($id_value));
			}

			return (int) $id_value;
		}

		if ( ! self::is_positive_int( $id ) ) {
			EPKB_Logging::add_log( 'Error occurred (04)', $id );
			return new WP_Error('E004', "invalid ID: " . $id);
		}

		return (int) $id;
	}

    /**
     * Sanitize array full of ints.
     *
     * @param $array_values
     * @param string $default
     * @return array|string
     */
	public static function sanitize_int_array( $array_values, $default='' ) {
	    if ( ! is_array($array_values) ) {
	        return $default;
        }

        $sanitized_array = array();
        foreach( $array_values as $value ) {
	        $sanitized_array[] = self::sanitize_int( $value );
        }

        return $sanitized_array;
    }

	/**
	 * Decode and sanitize form fields.
	 *
	 * @param $form
	 * @return array
	 */
	public static function sanitize_form( $form ) {
		if ( empty($form) ) {
			return array();
		}

		// first urldecode()
		parse_str($form, $submitted_fields);

		// now sanitize each field
		$sanitized_fields = array();
		foreach( $submitted_fields as $submitted_key => $submitted_value ) {
			if ( $submitted_key == 'sidebar_main_page_intro_text' ) {   // TODO should be more generic (using E-LAY specs?)
				$sanitized_fields[$submitted_key] = wp_kses_post( $submitted_value );
			} else {
				$sanitized_fields[$submitted_key] = sanitize_text_field( $submitted_value );
			}
		}

		return $sanitized_fields;
	}


	/**************************************************************************************************************************
	 *
	 *                     GET/SAVE/UPDATE AN OPTION
	 *
	 *************************************************************************************************************************/

	/**
	 * Get KB-SPECIFIC option. Function adds KB ID suffix. Prefix represent core or ADD-ON prefix.
	 *
	 * WARN: Use ep.kb_get_instance()->kb_config_obj->get_kb_configs() to get KB specific configuration.
	 *
	 * @param $kb_id - assuming it is a valid ID
	 * @param $option_name - without kb suffix
	 * @param $default - use if KB option not found
	 * @param bool $is_array - ensure returned value is an array, otherwise return default
	 * @return string|array|null or default
	 */
	public static function get_kb_option( $kb_id, $option_name, $default, $is_array=false ) {
		$full_option_name = $option_name . '_' . $kb_id;
		return self::get_wp_option( $full_option_name, $default, $is_array );
	}

	/**
	 * Use to get:
	 *  a) PLUGIN-WIDE option not specific to any KB with e p k b prefix.
	 *  b) ADD-ON-SPECIFIC option with ADD-ON prefix.
	 *  b) KB-SPECIFIC configuration with e p k b prefix and KB ID suffix.
	 *
	 * @param $option_name
	 * @param $default
	 * @param bool|false $is_array
	 * @param bool $return_error
	 *
	 * @return array|string|WP_Error or default or error if $return_error is true
	 */
	public static function get_wp_option( $option_name, $default, $is_array=false, $return_error=false ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// retrieve specific KB option
		$option = $wpdb->get_var( $wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s", $option_name ) );
		if ($option !== null ) {
			$option = maybe_unserialize( $option );
		}

		if ( $return_error && $option === null && ! empty($wpdb->last_error) ) {
			EPKB_Logging::add_log( "DB failure: " . $wpdb->last_error, 'Option Name: ' . $option_name );
			return new WP_Error('DB failure', $wpdb->last_error);
		}

		// if KB option is missing then return defaults
		if ( $option === null || ( $is_array && ! is_array($option) ) ) {
			return $default;
		}

		return $option;
	}

	/**
	 * Save KB-SPECIFIC option. Function adds KB ID suffix. Prefix represent core or ADD-ON prefix.
	 *
	 * @param $kb_id - assuming it is a valid ID
	 * @param $option_name - without kb suffix
	 * @param array $option_value
	 * @param $sanitized - ensures input is sanitized
	 *
	 * @return array|WP_Error if option cannot be serialized or db insert failed
	 */
	public static function save_kb_option( $kb_id, $option_name, array $option_value, $sanitized ) {
		$full_option_name = $option_name . '_' . $kb_id;
		return self::save_wp_option( $full_option_name, $option_value, $sanitized );
	}

	/**
	 * Use to save:
	 *  a) PLUGIN-WIDE option not specific to any KB with e p k b prefix.
	 *  b) ADD-ON-SPECIFIC option with ADD-ON prefix.
	 *  b) KB-SPECIFIC configuration with e p k b prefix and KB ID suffix.
	 *
	 * @param $option_name
	 * @param $option_value
	 * @param $sanitized
	 * @return mixed|WP_Error
	 */
	public static function save_wp_option( $option_name, $option_value, $sanitized ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( $sanitized !== true ) {
			return new WP_Error( '433', 'Option value was not sanitized for option: ' . $option_name );
		}

		// do not store null
		if ( $option_value === null ) {
            $option_value = '';
        }

		// add or update the option
		$serialized_value = $option_value;
		if ( is_array( $option_value ) || is_object( $option_value ) ) {
			$serialized_value = maybe_serialize($option_value);
			if ( empty($serialized_value) ) {
				return new WP_Error( '434', 'Failed to serialize value for option: ' . $option_name );
			}
		}

		$result = $wpdb->query( $wpdb->prepare( "INSERT INTO $wpdb->options (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s)
 												 ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)",
												$option_name, $serialized_value, 'no' ) );
		if ( $result === false ) {
			EPKB_Logging::add_log( 'Failed to update option', $option_name );
			return new WP_Error( '435', 'Failed to update option ' . $option_name );
		}

		return $option_value;
	}


    /**************************************************************************************************************************
     *
     *                     DATABASE
     *
     *************************************************************************************************************************/

	/**
	 * Get given Post Metadata
	 *
	 * @param $post_id
	 * @param $meta_key
	 * @param $default
	 * @param bool|false $is_array
	 * @param bool $return_error
	 *
	 * @return array|string or default or error if $return_error is true
	 */
	public static function get_postmeta( $post_id, $meta_key, $default, $is_array=false, $return_error=false ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( ! self::is_positive_int( $post_id) ) {
			return $return_error ? new WP_Error( 'Invalid Post ID', self::get_variable_string( $post_id ) ) : $default;
		}

		// retrieve specific KB option
		$option = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d and meta_key = '%s'", $post_id, $meta_key ) );
		if ($option !== null ) {
			$option = maybe_unserialize( $option );
		}

		if ( $return_error && $option === null && ! empty($wpdb->last_error) ) {
			EPKB_Logging::add_log( "DB failure: " . $wpdb->last_error, 'Meta Key: ' . $meta_key );
			return new WP_Error('DB failure', $wpdb->last_error);
		}

		// if KB option is missing then return defaults
		if ( $option === null || ( $is_array && ! is_array($option) ) ) {
			return $default;
		}

		return $option;
	}

	/**
	 * Save or Insert Post Metadata
	 *
	 * @param $post_id
	 * @param $meta_key
	 * @param $meta_value
	 * @param $sanitized
	 *
	 * @return mixed|WP_Error
	 */
	public static function save_postmeta( $post_id, $meta_key, $meta_value, $sanitized ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( ! self::is_positive_int( $post_id) ) {
			return new WP_Error( 'Invalid Post ID', self::get_variable_string( $post_id ) );
		}

		if ( $sanitized !== true ) {
			return new WP_Error( '433', 'Option value was not sanitized for meta key: ' . $meta_key );
		}

		// do not store null
		if ( $meta_value === null ) {
			$meta_value = '';
		}

		// add or update the option
		$serialized_value = $meta_value;
		if ( is_array( $meta_value ) || is_object( $meta_value ) ) {
			$serialized_value = maybe_serialize($meta_value);
			if ( empty($serialized_value) ) {
				return new WP_Error( '434', 'Failed to serialize value for meta key: ' . $meta_key );
			}
		}

		// check if the meta field already exists before doing 'upsert'
		$result = $wpdb->get_row( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '%s' AND post_id = %d", $meta_key, $post_id ) );
		if ( $result === null && ! empty($wpdb->last_error) ) {
			EPKB_Logging::add_log( "DB failure: " . $wpdb->last_error );
			return new WP_Error('DB failure', $wpdb->last_error);
		}

		// INSERT or UPDATE the meta field
		if ( empty($result) ) {
			if ( false === $wpdb->query( $wpdb->prepare( "INSERT INTO $wpdb->postmeta (`meta_key`, `meta_value`, `post_id`) VALUES (%s, %s, %d)", $meta_key, $serialized_value, $post_id ) ) ) {
				EPKB_Logging::add_log("Failed to insert meta data. ", $meta_key);
				return new WP_Error( '33', 'Failed to insert meta data' );
			}
		} else {
			if ( false === $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value = %s WHERE meta_key = '%s' AND post_id = %d", $serialized_value, $meta_key, $post_id ) ) ) {
				EPKB_Logging::add_log("Failed to update meta data. ", $meta_key);
				return new WP_Error( '33', 'Failed to update meta data' );
			}
		}

		if ( $result === false ) {
			EPKB_Logging::add_log( 'Failed to update meta key', $meta_key );
			return new WP_Error( '435', 'Failed to update meta key ' . $meta_key );
		}

		return $meta_value;
	}

	/**
	 * Delete given Post Metadata
	 *
	 * @param $post_id
	 * @param $meta_key
	 *
	 * @return bool
	 */
	public static function delete_postmeta( $post_id, $meta_key ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( ! self::is_positive_int( $post_id) ) {
			return false;
		}

		// delete specific KB option
		if ( false === $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE post_id = %d and meta_key = '%s'", $post_id, $meta_key ) ) ) {
			EPKB_Logging::add_log("Could not delete post '" . EPKB_Utilities::get_variable_string($meta_key) . "'' metadata: ", $post_id);
			return false;
		}

		return true;
	}

    /**
     * Get all existing KB categories for given taxonomy.
     *
     * @param $kb_id
     * @param string $order_by
     * @return array|null - return array of KB categories (empty if not found) or null on error
     */
    public static function get_kb_categories( $kb_id, $order_by='name' ) {
        /** @var wpdb $wpdb */
        global $wpdb;

        $order = $order_by == 'name' ? 'ASC' : 'DESC';
        $order_by = $order_by == 'date' ? 'term_id' : $order_by;   // terms don't have date so use id
        $kb_category_taxonomy_name = EPKB_KB_Handler::get_category_taxonomy_name( $kb_id );
        $result = $wpdb->get_results( $wpdb->prepare("SELECT t.*, tt.*
												   FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
												   WHERE tt.taxonomy IN (%s) ORDER BY " . esc_sql('t.' . $order_by) . ' ' . $order, $kb_category_taxonomy_name ) );
        return isset($result) && is_array($result) ? $result : null;
    }

	/**
	 * Get KB Article categories.
	 *
	 * @param $article_id
	 * @return null|array - categories belonging to the given KB Article or null on error
	 */
	public static function get_article_categories( $article_id ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// get article categories
		$post_taxonomy_ids = $wpdb->get_results(
									$wpdb->prepare( "SELECT * FROM " .
									                " $wpdb->term_relationships tr " .
									                " WHERE tr.object_id = %d ", $article_id ) );

		if ( $post_taxonomy_ids === null || ! is_array( $post_taxonomy_ids ) ) {
			return null;
		}

		return $post_taxonomy_ids;
	}

	/**************************************************************************************************************************
	 *
	 *                     OTHER
	 *
	 *************************************************************************************************************************/

	/**
	 * Retrieve KB ID.
	 *
	 * @param WP_Post $post
	 * @return int or null on ERROR
	 */
	public static function get_kb_id( $post=null ) {
		global $eckb_kb_id;

		$post = $post === null ? get_post() : $post;
		if ( ! empty($post) && $post instanceof WP_Post ) {
			$kb_id = EPKB_KB_Handler::get_kb_id_from_post_type( $post->post_type );
		}

		$kb_id = empty($kb_id) || is_wp_error($kb_id) ? ( empty($eckb_kb_id) ? '' : $eckb_kb_id ) : $kb_id;
		if ( empty($kb_id) ) {
			EPKB_Logging::add_log("KB ID not found", $kb_id);
			return null;
		}

		return $kb_id;
	}

	/**
	 * Check if KB is ARCHIVED.
	 *
	 * @param $kb_status
	 * @return bool
	 */
	public static function is_kb_archived( $kb_status ) {
		return $kb_status === 'archived';
	}

	/**
	 * Return string representation of given variable for logging purposes
	 *
	 * @param $var
	 *
	 * @return string
	 */
	public static function get_variable_string( $var ) {

		if ( ! is_array($var) ) {
			return self::get_variable_not_array( $var );
		}

		if ( empty($var) ) {
			return '[empty]';
		}

		$output = 'array';
		$ix = 0;
		foreach ($var as $key => $value) {

            if ( $ix++ > 10 ) {
                $output .= '[.....]';
                break;
            }

			$output .= "[" . $key . " => ";
			if ( ! is_array($value) ) {
				$output .= self::get_variable_not_array( $value ) . "]";
				continue;
			}

			$ix2 = 0;
			$output .= "[";
			$first = true;
			foreach($value as $key2 => $value2) {
                if ( $ix2++ > 10 ) {
                    $output .= '[.....]';
                    break;
                }

				if ( is_array($value2) ) {
                    $output .= '[...]';
                } else {
					$output .= ( $first ? '' : ', ' ) . $key2 . " => " . self::get_variable_not_array( $value2 );
					$first = false;
					continue;
				}
            }
			$output .= "]]";
		}

		return $output;
	}

	private static function get_variable_not_array( $var ) {

		if ( $var === null ) {
			return '<null>';
		}

		if ( ! isset($var) ) {
            /** @noinspection HtmlUnknownAttribute */
            return '<not set>';
		}

		if ( is_array($var) ) {
			return empty($var) ? '[]' : '[...]';
		}

		if ( is_object( $var ) ) {
			return '<' . get_class($var) . '>';
		}

		if ( is_bool( $var ) ) {
			return $var ? 'TRUE' : 'FALSE';
		}

		if ( is_string($var) || is_numeric($var) ) {
			return $var;
		}

		return '<unknown>';
	}

	/**
	 * Retrieve roles that current user has
	 * @return array $roles
	 *
	 * Not tested - simple
	 */
	public static function get_user_roles() {
		global $wp_roles;

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$roles = $wp_roles->get_names();

		return $roles;
	}

	/**
	 * Array1 VALUES NOT IN array2
	 *
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array of values in array1 NOT in array2
	 */
	public static function diff_two_dimentional_arrays( array $array1, array $array2 ) {

		if ( empty($array1) ) {
			return array();
		}

		if ( empty($array2) ) {
			return $array1;
		}

		// flatten first array
		foreach( $array1 as $key => $value ) {
			if ( is_array($value) ) {
				$tmp_value = '';
				foreach( $value as $tmp ) {
					$tmp_value .= ( empty($tmp_value) ? '' : ',' ) . ( empty($tmp) ? '' : $tmp );
				}
				$array1[$key] = $tmp_value;
			}
		}

		// flatten second array
		foreach( $array2 as $key => $value ) {
			if ( is_array($value) ) {
				$tmp_value = '';
				foreach( $value as $tmp ) {
					$tmp_value .= ( empty($tmp_value) ? '' : ',' ) . ( empty($tmp) ? '' : $tmp );
				}
				$array2[$key] = $tmp_value;
			}
		}

		return array_diff_assoc($array1, $array2);
	}


	/**
	 * Output inline CSS style based on configuration.
	 *
	 * @param string $styles A list of Configuration Setting styles
	 * @param $kb_config
	 * @return string
	 */
	public static function get_inline_style( $styles, $kb_config ) {

		if ( empty($styles) || ! is_string($styles) ) {
			return '';
		}

		$style_array = explode(',', $styles);
		if ( empty($style_array) ) {
			return '';
		}

		$output = 'style="';
		foreach( $style_array as $style ) {

			$key_value = array_map( 'trim', explode(':', $style) );
			if ( empty($key_value[0]) ) {
				continue;
			}

			$output .= $key_value[0] . ': ';

			// true if using KB config value
			if ( count($key_value) == 2 && isset($key_value[1]) ) {
				$output .= $key_value[1];
			} else if ( isset($key_value[2]) && isset($kb_config[$key_value[2]]) ) {
				$output .= $kb_config[ $key_value[2] ];

				switch ( $key_value[0] ) {
					case 'border-radius':
					case 'border-width':
					case 'border-bottom-width':
					case 'border-top-left-radius':
					case 'border-top-right-radius':
					case 'border-bottom-left-radius':
					case 'border-bottom-right-radius':
					case 'min-height':
					case 'max-height':
					case 'height':
					case 'padding-left':
					case 'padding-right':
					case 'padding-top':
					case 'padding-bottom':
					case 'margin':
					case 'margin-top':
					case 'margin-right':
					case 'margin-bottom':
					case 'margin-left':
					case 'font-size':
						$output .= 'px';
						break;
				}
			}

			$output .= '; ';
		}

		return trim($output) . '"';
	}

	/**
	 * Output CSS classes based on configuration.
	 *
	 * @param $classes
	 * @param $kb_config
	 * @return string
	 */
	public static function get_css_class( $classes, $kb_config ) {

		if ( empty($classes) || ! is_string($classes) ) {
			return '';
		}

		$output = ' class="';
		foreach( array_map( 'trim', explode(',', $classes) ) as $class ) {
			$class_name = trim(str_replace(':', '', $class));
			$is_kb_config = $class != $class_name;

			if ( $is_kb_config && empty($kb_config[$class_name]) ) {
				continue;
			}

			$output .= ( $is_kb_config ? $kb_config[$class_name] : $class ) . ' ';
		}
		return trim($output) . '"';
	}
}

abstract class EPKB_KB_Access_Level
{
	const KB_ACCESS_PUBLIC = 'kb_access_public';
	const KB_ACCESS_RESTRICTED = 'kb_access_restricted';

	public static function get_values() {
		return array( self::KB_ACCESS_PUBLIC, self::KB_ACCESS_RESTRICTED );
	}
}

<?php
/**
 * Handle theme auto updates.
 *
 *@pckage faster
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( !class_exists('Sp_Software_Autoupdate') )	:

	/**
	 * Sp_Software_Autoupdate class
	 *
	 *
	 * @access public
	 *
	 */
	abstract class Sp_Software_Autoupdate {

		const SUPERPLUGIN_API = "http://shop.planwize.com/superplugin-api/";

		protected $software_id;
		protected $software_path;
		protected $license;
		protected $email;
		protected $remote_version;
		protected $current_version;

		function __construct($software_id, $software_path ){
			$this->software_id = $software_id;
			$this->software_path = $software_path;
			$this->license = get_option( $software_id . "_license" , '' );
			$this->email = get_option( $software_id . "_activation_email" , '' );

			//Handle software autoupdate
			add_action('admin_init', array($this, 'software_autoupdate') );
		}

		function get_remote_package(){
			$args = array(
				'action'		=> 'get_package',
				'software_id'	=> $this->software_id,
				'license_key'	=> $this->license,
				'activation_email' => $this->email
			);

			$response = $this->execute_request($args);
			if ( $response && $response = json_decode($response) ){
				return (isset($response->success)) ? $response->data : false;
			}
			return false;
		}

		/**
		 * Get remote plugin version from superplugin api
		 *
		 * @return array|boolean
		 */
		function get_remote_version(){

			$args = array(
				'action' 		=> 'get_version',
				'software_id' 	=> $this->software_id
			);

			$response = $this->execute_request($args);

			if ( $response && $response = json_decode($response) ){
				return isset($response->data) ? $response->data : false;
			}

			return false;
		}

		/**
		 * Fire away the request
		 *
		 * @param array $args . contain the arguments needed for the request. must contain at least the argument name : "action"
		 * @return array|boolean json response object on success and false on failure
		 */
		protected function execute_request( $args ) {

			$target_url = self::SUPERPLUGIN_API  ;
			$args['controller'] = 'SoftwareRep';

			$request = wp_remote_post( $target_url , array('timeout' => 20, 'body'=>$args, 'sslverify' => false ) );

			if (!is_wp_error($request) && wp_remote_retrieve_response_code($request)==200 && !empty($request['body'])){
				return $request['body'] ;
			}
			return false;
		}


	}


endif;

if ( !class_exists('Sp_Software_Autoupdate_Plugin') )	:

	/**
	 * Sp_Software_Autoupdate_Plugin class
	 *
	 *
	 * @access public
	 *
	 */
	class Sp_Software_Autoupdate_Plugin extends Sp_Software_Autoupdate {

		protected  $plugin_transient_flag;

		function __construct($software_id, $software_path){
			parent::__construct($software_id, $software_path);

			$this->plugin_transient_flag = true;
		}


		function software_autoupdate(){
			add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
			// Check For Plugin Information
			add_filter( 'plugins_api', array( $this, 'plugin_information' ), 10, 3 );
		}


		function plugin_information ( $false, $action, $args ){
			$plugin_slug = plugin_basename($this->software_path);
			$splited = explode('/', $plugin_slug);
			// Check if this plugins API is about this plugin
			if( ! isset( $args->slug ) || ( $args->slug != $splited[0] ) ) {
				return $false;
			}

			$res = $this->get_plugin_info();
			if ( ! is_object( $res ) && ! is_array( $res ) )
				$res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with superplug.in or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://shop.planwize.com/">support forums</a>.' ) );


			return $res;
		}



		function check_update( $transient ){
			/* Check if the transient contains the 'checked' information
			 * If no, just return its value without hacking it
			 */
			if( empty( $transient->checked ) )
				return $transient;

			$remote_version = $this->get_remote_version();

			$plugin_slug = plugin_basename($this->software_path);
			$splited = explode('/', $plugin_slug);
			$current_version = $transient->checked[ $plugin_slug ];

			if ( !empty($remote_version) && version_compare($current_version, $remote_version, '<') ){

				$obj = new stdClass();
				$obj->slug = $splited[0];
				$obj->plugin = $plugin_slug;
				$obj->new_version = $remote_version;
				$obj->url = "http://shop.planwize.com";
				$package = $this->get_remote_package();
				if ( !empty($package) )
					$obj->package = $package;
				$transient->response[$plugin_slug] = $obj;

			}

			return $transient;


		}


		function get_plugin_info(){

			$args = array(
				'action'		=> 'get_plugin_info',
				'software_id'	=> $this->software_id,
				'license_key'	=> $this->license,
				'activation_email' => $this->email
			);

			$response = $this->execute_request($args);
			$response = json_decode($response);
			if ( $response &&  isset($response->success) &&  $response->success ){

				$res = $response->data;
				//fix sections stdClass - should be assoc
				$res->sections = (array)$res->sections;
				return $res;
			}

			return false;

		}

	}

endif;



if ( !class_exists('Sp_Software_Autoupdate_Theme') )	:

	/**
	 * Sp_Software_Autoupdate_Theme class
	 *
	 *
	 * @access public
	 *
	 */
	class Sp_Software_Autoupdate_Theme extends Sp_Software_Autoupdate {



		protected $theme_name;
		protected $transient_flag;

		function __construct($software_id, $software_path){
			parent::__construct($software_id, $software_path);
			$this->theme_name = basename($software_path);

			$this->transient_flag = true;
		}

		function software_autoupdate(){
			add_filter('pre_set_site_transient_update_themes', array($this, 'check_update'));
		}

		function check_update( $transient ){

			/* Check if the transient contains the 'checked' information
			 * If no, just return its value without hacking it
			 */
			if( empty( $transient->checked ) )
				return $transient;

			//update remote version
			$this->remote_version = $this->get_remote_version();
			//update current version
			$this->current_version = $transient->checked[ $this->theme_name ];

			//check if theme needs an update
			if ( version_compare($this->current_version, $this->remote_version, '<') ){
				$transient->response[ $this->theme_name ]['theme'] = $this->theme_name;
				$transient->response[ $this->theme_name ]['new_version'] = $this->remote_version;
				$transient->response[ $this->theme_name ]['url'] = 'http://shop.planwize.com/';
				$remote_package = $this->get_remote_package();
				if ( $remote_package )	$transient->response[ $this->theme_name ]['package'] = $remote_package;
				//$transient->response[ $this->theme_name ]['package'] = "https://superplug.in";
			}

			return $transient;

		}




	}
endif;

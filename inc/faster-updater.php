<?php
/**
 * Handle theme auto updates.
 *
 * @package faster
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists('Faster_Updater') ) :

class Faster_Updater{
	
	const PLANWIZE_API = "http://shop.planwize.com/superplugin-api/";

	/**
	 * @var string
	 */
	private $software_id;

	/**
	 * @var string
	 */
	private $software_path;

	/**
	 * @var string
	 */
	private $remote_version;

	/**
	 * @var string
	 */
	private $current_version;

	/**
	 * @var string
	 */
	private $theme_name;

	/**
	 * @var bool
	 */
	private $transient_flag;

	public function __construct() {
		$this->software_id = 'faster';
		$this->software_path = get_template_directory();
		$this->theme_name = basename($this->software_path);
		$this->transient_flag = true;

		// Handle software autoupdate
		add_action('admin_init', array($this, 'faster_software_autoupdate') );
	}

	public function faster_software_autoupdate(){
		add_filter('pre_set_site_transient_update_themes', array($this, 'faster_check_update'));
	}

	public function faster_check_update($transient){
		/* Check if the transient contains the 'checked' information
		 * If no, just return its value without hacking it
		 */
		if( empty( $transient->checked ) ) {
			return $transient;
		}

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
			if ( $remote_package ) {
				$transient->response[ $this->theme_name ]['package'] = $remote_package;
			}
		}

		return $transient;
	}

	/**
	 * Get remote theme version from shop.planwize api
	 *
	 * @return array|boolean
	 */
	private function get_remote_version(){

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
	 * Get remote package from shop.planwize api
	 *
	 * @return array|boolean
	 */
	private function get_remote_package(){
		$args = array(
			'action'		=> 'get_package',
			'software_id'	=> $this->software_id
		);

		$response = $this->execute_request($args);
		if ( $response && $response = json_decode($response) ){
			return (isset($response->success)) ? $response->data : false;
		}
		return false;
	}

	/**
	 * Fire away the request
	 *
	 * @param array $args . contain the arguments needed for the request. must contain at least the argument name : "action"
	 * @return array|boolean json response object on success and false on failure
	 */
	private function execute_request( $args ) {

		$target_url = self::PLANWIZE_API  ;
		$args['controller'] = 'SoftwareRep';

		$request = wp_remote_post( $target_url , array('timeout' => 20, 'body'=>$args, 'sslverify' => false ) );

		if (!is_wp_error($request) && wp_remote_retrieve_response_code($request)==200 && !empty($request['body'])){
			return $request['body'] ;
		}
		return false;
	}
}

endif;

new Faster_Updater();
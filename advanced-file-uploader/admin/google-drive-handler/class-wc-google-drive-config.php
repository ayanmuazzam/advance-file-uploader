<?php
// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

// Include Composer autoloader with error handling
try {
	require_once EXTENDONS_UF_PLUGIN_DIR . 'vendor/autoload.php';
} catch (Error $e) {
	// Log error but don't break the site
	error_log('Google Drive API autoload error: ' . $e->getMessage());
	return;
} catch (Exception $e) {
	// Log error but don't break the site
	error_log('Google Drive API autoload exception: ' . $e->getMessage());
	return;
}

/**
 * Google Drive Configuration Handler
 * Manages OAuth credentials, authentication, and folder setup
 */
class WC_Google_Drive_Config {

	private static $instance = null;
	private $client;
	private $access_token;
	private $refresh_token;
	private $client_id;
	private $client_secret;
	private $project_id;
	private $folder_id;
	private $folder_name;
	private $credentials_data;
	private $oauth_config = array();

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Don't initialize Google Client here - load config first
		$this->load_saved_config();
	}

	/**
	 * Load saved OAuth configuration from WordPress options
	 */
	private function load_saved_config() {
		$config = get_option('wc_gdrive_oauth_config');
		if ($config && isset($config['client_id'], $config['client_secret'])) {
			$this->client_id = $config['client_id'];
			$this->client_secret = $config['client_secret'];
			$this->project_id = $config['project_id'] ?? '';
			$this->folder_name = $config['folder_name'] ?? '';
			$this->folder_id = $config['folder_id'] ?? '';
			
			// Load OAuth config structure for Google Client
			$this->oauth_config = $config;
			
			// Initialize Google Client with loaded config
			try {
				$this->init_google_client_with_config();
			} catch (Exception $e) {
				error_log('Failed to initialize Google Client with saved config: ' . $e->getMessage());
			}
		}
	}

	/**
	 * Initialize Google Client with loaded OAuth configuration
	 */
	private function init_google_client_with_config() {
		if (!$this->oauth_config) {
			return;
		}
		
		try {
			// Check if Google Client class is available
			if (!class_exists('Google_Client')) {
				$autoload_path = plugin_dir_path(__FILE__) . '../../vendor/autoload.php';
				if (file_exists($autoload_path)) {
					require_once $autoload_path;
				}
				
				if (!class_exists('Google_Client')) {
					throw new Exception('Google Client API not available. Please install via Composer.');
				}
			}
			
			$this->client = new Google_Client();
			$this->client->setApplicationName('WooCommerce File Uploader');
			$this->client->setScopes(array(
				Google_Service_Drive::DRIVE_FILE,
				Google_Service_Drive::DRIVE_METADATA_READONLY,
			));
			$this->client->setAccessType('offline');
			$this->client->setPrompt('select_account consent');
			
			// Set OAuth credentials
			$this->client->setClientId($this->oauth_config['client_id']);
			$this->client->setClientSecret($this->oauth_config['client_secret']);
			
			// Set redirect URI
			if (isset($this->oauth_config['redirect_uris'][0])) {
				$this->client->setRedirectUri($this->oauth_config['redirect_uris'][0]);
			} else {
				$this->client->setRedirectUri(admin_url('admin.php?page=wc-settings&tab=extendons_upload_files'));
			}
			
		} catch (Exception $e) {
			error_log('Google Client initialization error: ' . $e->getMessage());
			$this->client = null;
			throw $e;
		}
	}

	/**
	 * Initialize Google Client with OAuth 2.0
	 */
	private function init_google_client() {
		try {
			// Check if Google Client class is available
			if (!class_exists('Google_Client')) {
				$autoload_path = plugin_dir_path(__FILE__) . '../../vendor/autoload.php';
				if (file_exists($autoload_path)) {
					require_once $autoload_path;
				}
				
				if (!class_exists('Google_Client')) {
					throw new Exception('Google Client API not available. Please install via Composer.');
				}
			}
			
			$this->client = new Google_Client();
			$this->client->setApplicationName('WooCommerce File Uploader');
			$this->client->setScopes(array(
				Google_Service_Drive::DRIVE_FILE,
				Google_Service_Drive::DRIVE_METADATA_READONLY,
			));
			$this->client->setAccessType('offline');
			$this->client->setPrompt('select_account consent');
			
		} catch (Exception $e) {
			error_log('Google Client initialization error: ' . $e->getMessage());
			$this->client = null;
			throw $e; // Re-throw to surface the error
		}
	}

	/**
	 * Configure Google Drive with OAuth credentials
	 *
	 * @param array $credentials_data OAuth credentials from JSON file
	 * @param string $folder_name Name of the Google Drive folder
	 * @throws Exception
	 */
	public function configure( $credentials_data, $folder_name ) {
		$this->credentials_data = $credentials_data;
		$this->folder_name = $folder_name;

		$this->validate_credentials($credentials_data);
		$this->setup_oauth_client($credentials_data);
		
		// Store configuration for later use
		$this->save_oauth_config();
	}

	/**
	 * Validate OAuth credentials structure
	 *
	 * @param array $credentials OAuth credentials
	 * @throws Exception
	 */
	private function validate_credentials( $credentials ) {
		$required_fields = array( 'client_id', 'client_secret', 'project_id', 'auth_uri', 'token_uri' );
		
		foreach ($required_fields as $field) {
			if (!isset($credentials[$field]) || empty($credentials[$field])) {
				/* translators: %s: name of the missing OAuth credential field */
				throw new Exception(sprintf(esc_html__('Missing required OAuth credential: %s', 'unitedsol360-eo_fileuploader'), esc_html($field)));
			}
		}
	}

	/**
	 * Setup OAuth client with credentials
	 *
	 * @param array $credentials OAuth credentials
	 * @throws Exception
	 */
	private function setup_oauth_client( $credentials ) {
		// Ensure we have a client instance
		if (!$this->client) {
			$this->init_google_client();
		}
		
		if (!$this->client) {
			throw new Exception('Failed to initialize Google Client. Please check if Google API libraries are properly installed.');
		}

		$this->client_id = $credentials['client_id'];
		$this->client_secret = $credentials['client_secret'];
		$this->project_id = $credentials['project_id'];

		$this->client->setClientId($this->client_id);
		$this->client->setClientSecret($this->client_secret);
		
		// Set redirect URI from the credentials or use the admin page
		$redirect_uri = isset($credentials['redirect_uris'][0]) 
			? $credentials['redirect_uris'][0] 
			: admin_url('admin.php?page=wc-settings&tab=extendons_upload_files');
		
		$this->client->setRedirectUri($redirect_uri);
	}

	/**
	 * Get OAuth authorization URL for user consent
	 *
	 * @return string Authorization URL
	 */
	public function get_auth_url() {
		// Ensure client is initialized with current config
		if (!$this->client && $this->oauth_config) {
			$this->init_google_client_with_config();
		}
		
		if (!$this->client) {
			throw new Exception('OAuth client not initialized. Please check Google API installation and OAuth configuration.');
		}
		
		try {
			return $this->client->createAuthUrl();
		} catch (Exception $e) {
			throw new Exception('Failed to create authorization URL: ' . esc_html($e->getMessage()));
		}
	}

	/**
	 * Handle OAuth callback and get access token
	 *
	 * @param string $authorization_code Authorization code from OAuth callback
	 * @throws Exception
	 */
	public function handle_oauth_callback( $authorization_code ) {
		if (!$this->client) {
			throw new Exception('OAuth client not initialized');
		}

		$token = $this->client->fetchAccessTokenWithAuthCode($authorization_code);
		
		if (isset($token['error'])) {
			throw new Exception('OAuth error: ' . esc_html($token['error_description']));
		}

		$this->access_token = $token['access_token'];
		$this->refresh_token = isset($token['refresh_token']) ? $token['refresh_token'] : '';
		
		$this->client->setAccessToken($token);
		
		// Setup folder after successful authentication
		$this->setup_folder();
		$this->save_configuration();
	}

	/**
	 * Load existing configuration from WordPress options
	 *
	 * @return bool True if configuration exists and is valid
	 */
	public function load_existing_config() {
		$config = get_option('wc_gdrive_oauth_config');
		
		if (!$config || !isset($config['folder_id'], $config['client_id'])) {
			return false;
		}

		$this->folder_id = $config['folder_id'];
		$this->folder_name = $config['folder_name'];
		$this->client_id = $config['client_id'];
		$this->client_secret = $config['client_secret'];
		$this->project_id = $config['project_id'];
		$this->access_token = $config['access_token'] ?? '';
		$this->refresh_token = $config['refresh_token'] ?? '';

		// Setup OAuth client
		if ($this->client_id && $this->client_secret) {
			$this->client->setClientId($this->client_id);
			$this->client->setClientSecret($this->client_secret);
			
			if ($this->access_token) {
				$token_data = array(
					'access_token' => $this->access_token,
					'refresh_token' => $this->refresh_token,
				);
				$this->client->setAccessToken($token_data);
				
				// Check if token is expired and refresh if needed
				if ($this->client->isAccessTokenExpired() && $this->refresh_token) {
					try {
						$this->refresh_access_token();
						return true;
					} catch (Exception $e) {
						error_log('Failed to refresh OAuth token: ' . esc_html($e->getMessage()));
						return false;
					}
				}
				
				return true;
			}
		}

		return false;
	}

	/**
	 * Refresh access token using refresh token
	 *
	 * @throws Exception
	 */
	public function refresh_access_token() {
		if (!$this->refresh_token) {
			throw new Exception('No refresh token available');
		}

		$this->client->refreshToken($this->refresh_token);
		$token = $this->client->getAccessToken();
		
		$this->access_token = $token['access_token'];
		
		// Update stored configuration
		$this->save_configuration();
	}

	/**
	 * Setup Google Drive folder
	 *
	 * @throws Exception
	 */
	private function setup_folder() {
		$drive_service = new Google_Service_Drive($this->client);
		
		$existing_folder = $this->find_folder($drive_service, $this->folder_name);
		
		if ($existing_folder) {
			$this->folder_id = $existing_folder;
		} else {
			$this->folder_id = $this->create_folder($drive_service, $this->folder_name);
		}
	}

	/**
	 * Find existing folder in Google Drive
	 *
	 * @param Google_Service_Drive $drive_service Drive service instance
	 * @param string $folder_name Folder name to search for
	 * @return string|false Folder ID if found, false otherwise
	 */
	private function find_folder( $drive_service, $folder_name ) {
		try {
			$query = "name='{$folder_name}' and mimeType='application/vnd.google-apps.folder' and trashed=false";
			
			$results = $drive_service->files->listFiles(array(
				'q' => $query,
				'fields' => 'files(id,name)',
			));

			$files = $results->getFiles();
			if (count($files) > 0) {
				return $files[0]->getId();
			}
		} catch (Exception $e) {
			error_log('Google Drive folder search error: ' . $e->getMessage());
		}

		return false;
	}

	/**
	 * Create new folder in Google Drive
	 *
	 * @param Google_Service_Drive $drive_service Drive service instance
	 * @param string $folder_name Folder name to create
	 * @return string Folder ID
	 * @throws Exception
	 */
	private function create_folder( $drive_service, $folder_name ) {
		$folder_metadata = new Google_Service_Drive_DriveFile(array(
			'name' => $folder_name,
			'mimeType' => 'application/vnd.google-apps.folder',
		));

		try {
			$folder = $drive_service->files->create($folder_metadata, array(
				'fields' => 'id',
			));
			
			return $folder->getId();
		} catch (Exception $e) {
			throw new Exception('Failed to create Google Drive folder: ' . esc_html($e->getMessage()));
		}
	}

	/**
	 * Save OAuth configuration to WordPress options
	 */
	private function save_oauth_config() {
		update_option('wc_gdrive_oauth_config', array(
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'project_id' => $this->project_id,
			'folder_name' => $this->folder_name,
			'credentials_uploaded_at' => current_time('mysql'),
			'status' => 'credentials_uploaded',
		));
	}

	/**
	 * Save complete configuration after successful authentication
	 */
	private function save_configuration() {
		update_option('wc_gdrive_oauth_config', array(
			'folder_name' => $this->folder_name,
			'folder_id' => $this->folder_id,
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'project_id' => $this->project_id,
			'access_token' => $this->access_token,
			'refresh_token' => $this->refresh_token,
			'configured_at' => current_time('mysql'),
			'status' => 'active',
		));

		update_option('wc_gdrive_setup_message', array(
			'type' => 'success',
			'message' => 'Google Drive OAuth configured successfully',
			'timestamp' => current_time('mysql'),
		));
	}

	// Getter methods for the service class
	public function get_access_token() {
		return $this->access_token;
	}

	public function get_folder_id() {
		return $this->folder_id;
	}

	public function get_folder_name() {
		return $this->folder_name;
	}

	public function get_client() {
		return $this->client;
	}

	public function is_configured() {
		return !empty($this->access_token) && !empty($this->folder_id);
	}

	public function is_credentials_uploaded() {
		$config = get_option('wc_gdrive_oauth_config');
		return $config && isset($config['client_id'], $config['client_secret']);
	}

	public function needs_authorization() {
		return $this->is_credentials_uploaded() && !$this->is_configured();
	}

	/**
	 * Update access token (for re-authentication)
	 * 
	 * @throws Exception
	 */
	public function refresh_token() {
		if (empty($this->refresh_token)) {
			throw new Exception('No refresh token available for token refresh');
		}
		
		try {
			$this->refresh_access_token();
		} catch (Exception $e) {
			error_log('Token refresh failed: ' . esc_html($e->getMessage()));
			throw new Exception('Token refresh failed: ' . esc_html($e->getMessage()));
		}
	}

	/**
	 * Clear all configuration
	 */
	public static function clear_configuration() {
		delete_option('wc_gdrive_oauth_config');
		delete_option('wc_gdrive_setup_message');
		// Also clear old service account config if it exists
		delete_option('wc_gdrive_config');
		self::$instance = null;
	}

	/**
	 * Get configuration status
	 */
	public function get_config_status() {
		$config = get_option('wc_gdrive_oauth_config');
		return array(
			'is_configured' => $this->is_configured(),
			'is_credentials_uploaded' => $this->is_credentials_uploaded(),
			'needs_authorization' => $this->needs_authorization(),
			'folder_name' => $this->folder_name,
			'folder_id' => $this->folder_id,
			'client_id' => $this->client_id,
			'project_id' => $this->project_id,
			'configured_at' => $config['configured_at'] ?? null,
			'status' => $config['status'] ?? 'inactive',
		);
	}

	/**
	 * Validate configuration data
	 * 
	 * @param array $config Configuration array to validate
	 * @return bool True if valid, false otherwise
	 */
	private function validate_config( $config ) {
		$required_fields = array( 'folder_id', 'client_id', 'client_secret' );
		
		foreach ($required_fields as $field) {
			if (empty($config[$field])) {
				return false;
			}
		}
		
		// Basic validation for folder ID (should be alphanumeric with some special chars)
		if (!preg_match('/^[a-zA-Z0-9_-]+$/', $config['folder_id'])) {
			return false;
		}
		
		return true;
	}
}

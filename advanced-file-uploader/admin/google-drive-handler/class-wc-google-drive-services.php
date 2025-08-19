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
 * Google Drive Services Handler (OAuth Version)
 * Provides file operations: upload, delete, read, and change files using OAuth
 */
class WC_Google_Drive_Services {

	private static $instance = null;
	private $config;
	private $drive_service;

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Only require if the class doesn't exist
		if (!class_exists('WC_Google_Drive_Config')) {
			require_once EXTENDONS_UF_PLUGIN_DIR . 'admin/google-drive-handler/class-wc-google-drive-config.php';
		}
		$this->config = WC_Google_Drive_Config::get_instance();
	}

	/**
	 * Ensure configuration is loaded and valid
	 *
	 * @throws Exception
	 */
	private function ensure_configured() {
		if (!$this->config->is_configured()) {
			// Try to load existing configuration
			if (!$this->config->load_existing_config()) {
				throw new Exception('Google Drive not configured. Please configure OAuth credentials first.');
			}
		}
		
		// Initialize Drive service
		$client = $this->config->get_client();
		if (!$client) {
			throw new Exception('Google Drive client not initialized');
		}
		
		$this->drive_service = new Google_Service_Drive($client);
	} 

	public function isConfigured() {
		if (!$this->config->is_configured()) {
			// Try to load existing configuration
			if (!$this->config->load_existing_config()) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Handle token refresh on authentication errors
	 *
	 * @param callable $callback The function to retry
	 * @return mixed
	 * @throws Exception
	 */
	private function with_token_refresh( $callback ) {
		try {
			return $callback();
		} catch (Exception $e) {
			$error_message = strtolower($e->getMessage());
			
			// Check for various authentication error patterns
			if (strpos($error_message, 'authentication') !== false || 
				strpos($error_message, 'unauthorized') !== false ||
				strpos($error_message, 'invalid credentials') !== false ||
				strpos($error_message, 'token') !== false ||
				strpos($error_message, '401') !== false) {
				
				// Try to refresh the token
				try {
					$this->config->refresh_token();
					$this->drive_service = new Google_Service_Drive($this->config->get_client());
					return $callback();
				} catch (Exception $refresh_error) {
					throw new Exception('Authentication failed and token refresh unsuccessful: ' . esc_html($refresh_error->getMessage()));
				}
			}
			throw $e;
		}
	}

	/**
	 * Upload a file to Google Drive
	 *
	 * @param string $file_path Local file path
	 * @param string $file_name Name for the file in Google Drive
	 * @param string|null $mime_type MIME type of the file
	 * @return array Success/error response
	 */
	public function uploadFile( $file_path, $file_name, $mime_type = null ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $file_path, $file_name, $mime_type ) {
				return $this->perform_upload($file_path, $file_name, $mime_type);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_upload( $file_path, $file_name, $mime_type ) {
		if (!file_exists($file_path)) {
			throw new Exception('File not found: ' . esc_html($file_path));
		}

		if (!$mime_type) {
			$file_type = wp_check_filetype($file_path);
			$mime_type = $file_type['type'] ? $file_type['type'] : 'application/octet-stream';
		}

		// Create file metadata
		$file_metadata = new Google_Service_Drive_DriveFile(array(
			'name' => $file_name,
			'parents' => array( $this->config->get_folder_id() ),
		));

		// Upload file
		$result = $this->drive_service->files->create($file_metadata, array(
			'data' => file_get_contents($file_path),
			'mimeType' => $mime_type,
			'uploadType' => 'multipart',
			'fields' => 'id,name,size,webViewLink,webContentLink',
		));

		return array(
			'success' => true,
			'file_id' => $result->getId(),
			'file_name' => $result->getName(),
			'size' => $result->getSize(),
			'url' => 'https://lh3.googleusercontent.com/d/' . $result->getId() . '?v=' . $result->getModifiedTime(),
			'download_link' => 'https://lh3.googleusercontent.com/d/' . $result->getId() . '?v=' . $result->getModifiedTime(),
			'thumbnail' => 'https://drive.google.com/thumbnail?id=' . $result->getId(),
			'message' => 'File uploaded successfully',
		);
	}

	/**
	 * Delete a file from Google Drive
	 *
	 * @param string $file_id Google Drive file ID
	 * @return array Success/error response
	 */
	public function deleteFile( $file_id ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $file_id ) {
				return $this->perform_delete($file_id);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_delete( $file_id ) {
		$this->drive_service->files->delete($file_id);
		return array( 'success' => true, 'message' => 'File deleted successfully' );
	}

	/**
	 * Read/Get information about a file
	 *
	 * @param string $file_id Google Drive file ID
	 * @return array File information or error
	 */
	public function readFile( $file_id ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $file_id ) {
				return $this->perform_read($file_id);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_read( $file_id ) {
		$file = $this->drive_service->files->get($file_id, array(
			'fields' => 'id,name,size,mimeType,createdTime,modifiedTime,parents,webViewLink,webContentLink',
		));

		return array(
			'success' => true,
			'file_id' => $file->getId(),
			'name' => $file->getName(),
			'size' => $this->format_file_size($file->getSize()),
			'size_bytes' => $file->getSize(),
			'mime_type' => $file->getMimeType(),
			'created_time' => $file->getCreatedTime(),
			'modified_time' => $file->getModifiedTime(),
			'url' => 'https://lh3.googleusercontent.com/d/' . $file->getId() . '?v=' . $file->getModifiedTime(),
			'download_link' => 'https://lh3.googleusercontent.com/d/' . $file->getId() . '?v=' . $file->getModifiedTime(),
		);
	}

	/**
	 * Change/Update a file (rename, move, or replace content)
	 *
	 * @param string $file_id Google Drive file ID
	 * @param array $changes Array of changes to make
	 * @return array Success/error response
	 */
	public function changeFile( $file_id, $changes = array() ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $file_id, $changes ) {
				return $this->perform_change($file_id, $changes);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_change( $file_id, $changes ) {
		$file_metadata = new Google_Service_Drive_DriveFile();
		$update_fields = array();

		// Handle name change
		if (isset($changes['name'])) {
			$file_metadata->setName($changes['name']);
			$update_fields[] = 'name';
		}

		// Handle parent change (move file)
		if (isset($changes['parents'])) {
			$file_metadata->setParents($changes['parents']);
			$update_fields[] = 'parents';
		}

		// Handle file content replacement
		if (isset($changes['file_path'])) {
			return $this->replace_file_content($file_id, $changes['file_path'], $changes['name'] ?? null, $changes['mime_type'] ?? null);
		}

		// If no valid changes, return error
		if (empty($update_fields)) {
			throw new Exception('No valid changes specified');
		}

		// Update metadata
		$result = $this->drive_service->files->update($file_id, $file_metadata, array(
			'fields' => 'id,name,modifiedTime',
		));

		return array(
			'success' => true,
			'file_id' => $result->getId(),
			'name' => $result->getName(),
			'url' => 'https://lh3.googleusercontent.com/d/' . $result->getId() . '?v=' . $result->getModifiedTime(),
			'thumbnail' => 'https://drive.google.com/thumbnail?id=' . $result->getId(),
			'modified_time' => $result->getModifiedTime(),
			'message' => 'File updated successfully',
		);
	}

	/**
	 * Replace file content
	 *
	 * @param string $file_id Google Drive file ID
	 * @param string $file_path Local file path
	 * @param string|null $mime_type MIME type
	 * @return array Success/error response
	 */
	private function replace_file_content( $file_id, $file_path, $file_name, $mime_type = null ) {
		if (!file_exists($file_path)) {
			throw new Exception('File not found: ' . esc_html($file_path));
		}

		if (!$mime_type) {
			$file_type = wp_check_filetype($file_path);
			$mime_type = $file_type['type'] ? $file_type['type'] : 'application/octet-stream';
		}

		$file_metadata = new Google_Service_Drive_DriveFile(array(
			'name' => $file_name,
		));

		// Update file content
		$result = $this->drive_service->files->update($file_id, $file_metadata, array(
			'data' => file_get_contents($file_path),
			'mimeType' => $mime_type,
			'uploadType' => 'media',
			'fields' => 'id,name,size,modifiedTime,webViewLink',
		));

		return array(
			'success' => true,
			'file_id' => $result->getId(),
			'name' => $result->getName(),
			'size' => $this->format_file_size($result->getSize()),
			'url' => 'https://lh3.googleusercontent.com/d/' . $result->getId() . '?v=' . $result->getModifiedTime(),
			'thumbnail' => 'https://drive.google.com/thumbnail?id=' . $result->getId(),
			'modified_time' => $result->getModifiedTime(),
			'message' => 'File content updated successfully',
		);
	}

	/**
	 * Download file content from Google Drive
	 *
	 * @param string $file_id Google Drive file ID
	 * @return array File content or error
	 */
	public function downloadFile( $file_id ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $file_id ) {
				return $this->perform_download($file_id);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_download( $file_id ) {
		$response = $this->drive_service->files->get($file_id, array( 'alt' => 'media' ));
		$content = $response->getBody()->getContents();

		return array(
			'success' => true,
			'content' => $content,
			'size' => strlen($content),
			'message' => 'File downloaded successfully',
		);
	}

	/**
	 * Get file metadata and content together
	 *
	 * @param string $file_id Google Drive file ID
	 * @return array Combined file info and content or error
	 */
	public function getFileWithContent( $file_id ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $file_id ) {
				return $this->perform_get_file_with_content($file_id);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_get_file_with_content( $file_id ) {
		// Get file metadata
		$metadata_result = $this->perform_read($file_id);
		if (!$metadata_result['success']) {
			return $metadata_result;
		}

		// Get file content
		$content_result = $this->perform_download($file_id);
		if (!$content_result['success']) {
			return $content_result;
		}

		return array(
			'success' => true,
			'metadata' => $metadata_result,
			'content' => $content_result['content'],
			'size' => $content_result['size'],
		);
	}

	/**
	 * Create a folder in Google Drive
	 *
	 * @param string $folder_name Name of the folder to create
	 * @param string|null $parent_id Parent folder ID (null for configured folder)
	 * @return array Success/error response
	 */
	public function createFolder( $folder_name, $parent_id = null ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $folder_name, $parent_id ) {
				return $this->perform_create_folder($folder_name, $parent_id);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_create_folder( $folder_name, $parent_id ) {
		$folder_metadata = new Google_Service_Drive_DriveFile(array(
			'name' => $folder_name,
			'mimeType' => 'application/vnd.google-apps.folder',
		));

		if ($parent_id) {
			$folder_metadata->setParents(array( $parent_id ));
		} else {
			$folder_metadata->setParents(array( $this->config->get_folder_id() ));
		}

		$result = $this->drive_service->files->create($folder_metadata, array(
			'fields' => 'id,name,parents',
		));

		return array(
			'success' => true,
			'folder_id' => $result->getId(),
			'name' => $result->getName(),
			'parents' => $result->getParents(),
			'message' => 'Folder created successfully',
		);
	}

	/**
	 * Get file size in human readable format
	 * 
	 * @param int $size File size in bytes
	 * @return string Human readable size
	 */
	private function format_file_size( $size ) {
		if (null === $size || 'Unknown' === $size || !is_numeric($size)) {
			return 'Unknown';
		}
		
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB' );
		$power = 0 < $size ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2) . ' ' . $units[$power];
	}
}

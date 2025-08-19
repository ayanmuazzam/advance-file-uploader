<?php
// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

// Include Composer autoloader
require_once EXTENDONS_UF_PLUGIN_DIR . 'vendor/autoload.php';

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
			'view_link' => $result->getWebViewLink(),
			'download_link' => $result->getWebContentLink(),
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
			'view_link' => $file->getWebViewLink(),
			'download_link' => $file->getWebContentLink(),
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
			return $this->replace_file_content($file_id, $changes['file_path'], $changes['mime_type'] ?? null);
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
	private function replace_file_content( $file_id, $file_path, $mime_type = null ) {
		if (!file_exists($file_path)) {
			throw new Exception('File not found: ' . esc_html($file_path));
		}

		if (!$mime_type) {
			$file_type = wp_check_filetype($file_path);
			$mime_type = $file_type['type'] ? $file_type['type'] : 'application/octet-stream';
		}

		// Update file content
		$result = $this->drive_service->files->update($file_id, new Google_Service_Drive_DriveFile(), array(
			'data' => file_get_contents($file_path),
			'mimeType' => $mime_type,
			'uploadType' => 'media',
			'fields' => 'id,name,size,modifiedTime',
		));

		return array(
			'success' => true,
			'file_id' => $result->getId(),
			'name' => $result->getName(),
			'size' => $this->format_file_size($result->getSize()),
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
	 * Search for files by name
	 *
	 * @param string $search_query Search query
	 * @param int $max_results Maximum number of results
	 * @return array Search results or error
	 */
	public function searchFiles( $search_query, $max_results = 10 ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $search_query, $max_results ) {
				return $this->perform_search($search_query, $max_results);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_search( $search_query, $max_results ) {
		$folder_id = $this->config->get_folder_id();
		$query = "parents in '{$folder_id}' and name contains '{$search_query}' and trashed=false";
		
		$results = $this->drive_service->files->listFiles(array(
			'q' => $query,
			'pageSize' => $max_results,
			'fields' => 'files(id,name,size,mimeType,createdTime,modifiedTime,webViewLink,webContentLink)',
		));

		$files = array();
		foreach ($results->getFiles() as $file) {
			$files[] = array(
				'id' => $file->getId(),
				'name' => $file->getName(),
				'size' => $this->format_file_size($file->getSize()),
				'size_bytes' => $file->getSize(),
				'mime_type' => $file->getMimeType(),
				'created_time' => $file->getCreatedTime(),
				'modified_time' => $file->getModifiedTime(),
				'view_link' => $file->getWebViewLink(),
				'download_link' => $file->getWebContentLink(),
			);
		}

		return array(
			'success' => true,
			'files' => $files,
			'count' => count($files),
			'query' => $search_query,
		);
	}

	/**
	 * List files in the configured folder
	 *
	 * @param int $page_size Number of files to return
	 * @param string|null $page_token Token for pagination
	 * @return array List of files or error
	 */
	public function listFiles( $page_size = 10, $page_token = null ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $page_size, $page_token ) {
				return $this->perform_list($page_size, $page_token);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_list( $page_size, $page_token ) {
		$query = "parents in '{$this->config->get_folder_id()}' and trashed=false";
		
		$params = array(
			'q' => $query,
			'pageSize' => $page_size,
			'fields' => 'nextPageToken,files(id,name,size,mimeType,createdTime,modifiedTime,webViewLink,webContentLink)',
			'orderBy' => 'modifiedTime desc',
		);

		if ($page_token) {
			$params['pageToken'] = $page_token;
		}

		$results = $this->drive_service->files->listFiles($params);

		$files = array();
		foreach ($results->getFiles() as $file) {
			$files[] = array(
				'id' => $file->getId(),
				'name' => $file->getName(),
				'size' => $this->format_file_size($file->getSize()),
				'size_bytes' => $file->getSize(),
				'mime_type' => $file->getMimeType(),
				'created_time' => $file->getCreatedTime(),
				'modified_time' => $file->getModifiedTime(),
				'view_link' => $file->getWebViewLink(),
				'download_link' => $file->getWebContentLink(),
			);
		}

		return array(
			'success' => true,
			'files' => $files,
			'next_page_token' => $results->getNextPageToken(),
			'count' => count($files),
		);
	}

	/**
	 * Get storage quota information
	 *
	 * @return array Storage quota info or error
	 */
	public function getStorageQuota() {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () {
				return $this->perform_get_storage_quota();
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_get_storage_quota() {
		$about = $this->drive_service->about->get(array( 'fields' => 'storageQuota' ));
		$quota = $about->getStorageQuota();
		
		return array(
			'success' => true,
			'quota_bytes_total' => $quota->getLimit(),
			'quota_bytes_used' => $quota->getUsage(),
			'quota_bytes_used_drive' => $quota->getUsageInDrive(),
			'quota_total' => $this->format_file_size($quota->getLimit()),
			'quota_used' => $this->format_file_size($quota->getUsage()),
			'quota_used_drive' => $this->format_file_size($quota->getUsageInDrive()),
		);
	}

	/**
	 * Move file to trash (soft delete)
	 *
	 * @param string $file_id Google Drive file ID
	 * @return array Success/error response
	 */
	public function trashFile( $file_id ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $file_id ) {
				return $this->perform_trash($file_id);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_trash( $file_id ) {
		$file_metadata = new Google_Service_Drive_DriveFile(array( 'trashed' => true ));
		$this->drive_service->files->update($file_id, $file_metadata);
		
		return array( 'success' => true, 'message' => 'File moved to trash successfully' );
	}

	/**
	 * Restore file from trash
	 *
	 * @param string $file_id Google Drive file ID
	 * @return array Success/error response
	 */
	public function restoreFile( $file_id ) {
		try {
			$this->ensure_configured();

			return $this->with_token_refresh(function () use ( $file_id ) {
				return $this->perform_restore($file_id);
			});

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	private function perform_restore( $file_id ) {
		$file_metadata = new Google_Service_Drive_DriveFile(array( 'trashed' => false ));
		$this->drive_service->files->update($file_id, $file_metadata);
		
		return array( 'success' => true, 'message' => 'File restored successfully' );
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

	/**
	 * Validate file before upload
	 * 
	 * @param string $file_path Local file path
	 * @param array $allowed_types Allowed mime types (optional)
	 * @param int $max_size Maximum file size in bytes (optional)
	 * @return array Validation result
	 */
	public function validateFile( $file_path, $allowed_types = array(), $max_size = null ) {
		try {
			if (!file_exists($file_path)) {
				throw new Exception('File does not exist: ' . $file_path);
			}

			$file_size = filesize($file_path);
			$file_type = wp_check_filetype($file_path);

			// Check file size
			if ($max_size && $file_size > $max_size) {
				throw new Exception('File size exceeds maximum allowed size of ' . $this->format_file_size($max_size));
			}

			// Check file type
			if (!empty($allowed_types) && !in_array($file_type['type'], $allowed_types)) {
				throw new Exception('File type not allowed. Allowed types: ' . implode(', ', $allowed_types));
			}

			return array(
				'success' => true,
				'file_size' => $file_size,
				'file_type' => $file_type['type'],
				'file_ext' => $file_type['ext'],
				'formatted_size' => $this->format_file_size($file_size),
			);

		} catch (Exception $e) {
			return array( 'success' => false, 'error' => $e->getMessage() );
		}
	}

	/**
	 * Batch delete multiple files
	 *
	 * @param array $file_ids Array of Google Drive file IDs
	 * @return array Batch operation results
	 */
	public function batchDeleteFiles( $file_ids ) {
		$results = array();
		$success_count = 0;
		$error_count = 0;

		foreach ($file_ids as $file_id) {
			$result = $this->deleteFile($file_id);
			$results[$file_id] = $result;
			
			if ($result['success']) {
				$success_count++;
			} else {
				$error_count++;
			}
		}

		return array(
			'success' => 0 === $error_count,
			'results' => $results,
			'success_count' => $success_count,
			'error_count' => $error_count,
			'total_files' => count($file_ids),
		);
	}
}

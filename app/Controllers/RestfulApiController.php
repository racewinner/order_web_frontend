<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class RestfulApiController extends BaseController
{
    use ResponseTrait;

    /**
     * App activation endpoint
     * 
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function app_activation()
    {
        try {
            
           

            // Get all file contents from public/app_media_files directory
            $filesDirectory = ROOTPATH . 'public/app_media_files';
            $filesData = [];
            
            // Check if directory exists
            if (!is_dir($filesDirectory)) {
                return $this->fail('Directory not found: app_media_files', 404);
            }
            
            // Get all files from the directory
            $files = array_diff(scandir($filesDirectory), ['.', '..']);
            
            if (empty($files)) {
                return $this->respond([
                    'success' => true,
                    'message' => 'No files found in app_media_files directory',
                    'data' => []
                ], 200);
            }
            
            // Read content of each file
            foreach ($files as $file) {
                $filePath = $filesDirectory . DIRECTORY_SEPARATOR . $file;
                
                // Skip if it's a directory
                if (is_dir($filePath)) {
                    continue;
                }
                
                // Get filename without extension for the key
                $fileInfo = pathinfo($file);
                $fileKey = $fileInfo['filename'];
                
                // Read file content
                if (is_readable($filePath)) {
                    $fileContent = file_get_contents($filePath);
                    
                    // Try to parse as JSON first
                    $jsonData = json_decode($fileContent, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $filesData[$fileKey] = $jsonData;
                    } else {
                        // If not JSON, try to parse as XML and convert to JSON
                        $xml = @simplexml_load_string($fileContent);
                        if ($xml !== false) {
                            // Convert XML to array/JSON
                            $jsonData = json_decode(json_encode($xml), true);
                            $filesData[$fileKey] = $jsonData;
                        } else {
                            // If neither JSON nor XML, return as string
                            $filesData[$fileKey] = $fileContent;
                        }
                    }
                } else {
                    $filesData[$fileKey] = null; // File exists but not readable
                }
            }
            
            $response = [
                'success' => true,
                'message' => 'Files retrieved successfully',
                'data' => $filesData
            ];

            return $this->respond($response, 200);
            
        } catch (\Exception $e) {
            return $this->fail('Activation failed: ' . $e->getMessage(), 500);
        }
    }
}


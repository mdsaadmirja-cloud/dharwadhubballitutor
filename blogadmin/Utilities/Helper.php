<?php
class Helper
{
    public static function fileupload($fileToUpload, $directoryToStore, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx'], $maxFileSize = 5242880)
    {
        // Ensure directory ends with slash
        $directoryToStore = rtrim($directoryToStore, '/') . '/';

        // Validate upload array
        if (!isset($fileToUpload['tmp_name']) || $fileToUpload['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Invalid file upload.'];
        }

        // Extract file details
        $originalName = basename($fileToUpload['name']);
        $targetFile   = $directoryToStore . $originalName;
        $fileType     = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $fileSize     = $fileToUpload['size'];

        // Check file extension
        if (!in_array($fileType, $allowedExtensions)) {
            return ['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowedExtensions)];
        }

        // Check file size
        if ($fileSize > $maxFileSize) {
            return ['success' => false, 'message' => 'File size exceeds the limit of ' . ($maxFileSize / 1024 / 1024) . ' MB.'];
        }

        // For images: verify it’s a real image
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $check = getimagesize($fileToUpload['tmp_name']);
            if ($check === false) {
                return ['success' => false, 'message' => 'Uploaded file is not a valid image.'];
            }
        }

        // Generate unique file name if file exists
        if (file_exists($targetFile)) {
            $uniqueName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.' . $fileType;
            $targetFile = $directoryToStore . $uniqueName;
        }

        // Try to move file
        if (move_uploaded_file($fileToUpload['tmp_name'], $targetFile)) {
            return [
                'success' => true,
                'message' => 'File uploaded successfully.',
                'file'    => $targetFile
            ];
        } else {
            return ['success' => false, 'message' => 'Error while uploading file.'];
        }
    }
}

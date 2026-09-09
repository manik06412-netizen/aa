<?php
/**
 * admin/inc/image_helper.php
 * Universal WebP Image Converter & Uploader for Karuda Computers Admin
 */

if (!function_exists('convert_to_webp')) {
    /**
     * Convert any local image file (JPG, PNG, GIF, BMP, WEBP) to WebP format with alpha channel preservation.
     *
     * @param string $source_path Local path to source image
     * @param string $destination_webp_path Destination .webp file path
     * @param int $quality Compression quality (1-100, default 85)
     * @return bool True on success, False on failure
     */
    function convert_to_webp($source_path, $destination_webp_path, $quality = 85) {
        if (!file_exists($source_path) || !is_readable($source_path)) {
            return false;
        }

        $dest_dir = dirname($destination_webp_path);
        if (!is_dir($dest_dir)) {
            @mkdir($dest_dir, 0777, true);
        }

        $image_info = @getimagesize($source_path);
        if (!$image_info) {
            // Try string load
            $data = @file_get_contents($source_path);
            if (!$data) return false;
            $img = @imagecreatefromstring($data);
            if (!$img) return false;
            imagepalettetotruecolor($img);
            imagealphablending($img, false);
            imagesavealpha($img, true);
            $res = imagewebp($img, $destination_webp_path, $quality);
            imagedestroy($img);
            return $res;
        }

        $mime = $image_info['mime'];
        $img = null;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/pjpeg':
                $img = @imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
            case 'image/x-png':
                $img = @imagecreatefrompng($source_path);
                if ($img) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                }
                break;
            case 'image/gif':
                $img = @imagecreatefromgif($source_path);
                if ($img) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                }
                break;
            case 'image/webp':
                $img = @imagecreatefromwebp($source_path);
                if ($img) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                }
                break;
            case 'image/bmp':
            case 'image/x-ms-bmp':
                if (function_exists('imagecreatefrombmp')) {
                    $img = @imagecreatefrombmp($source_path);
                }
                break;
            default:
                $data = @file_get_contents($source_path);
                if ($data) {
                    $img = @imagecreatefromstring($data);
                    if ($img) {
                        imagepalettetotruecolor($img);
                        imagealphablending($img, false);
                        imagesavealpha($img, true);
                    }
                }
                break;
        }

        if (!$img) {
            return false;
        }

        $result = imagewebp($img, $destination_webp_path, $quality);
        imagedestroy($img);

        return $result;
    }
}

if (!function_exists('upload_and_convert_to_webp')) {
    /**
     * Upload an incoming $_FILES item and automatically convert it to .webp format.
     *
     * @param array $file The $_FILES['field_name'] array
     * @param string $target_dir Target directory where the .webp file should be saved
     * @param string $prefix Optional filename prefix (e.g. 'prod_', 'cat_', 'slider_')
     * @param int $quality Compression quality (default 85)
     * @return string|null The generated filename on success (e.g. 'prod_65e123_456.webp'), or null on error
     */
    function upload_and_convert_to_webp($file, $target_dir, $prefix = '', $quality = 85) {
        if (!isset($file) || !is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $target_dir = rtrim($target_dir, '/\\') . '/';
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0777, true);
        }

        $unique_name = $prefix . uniqid() . '_' . rand(100, 999) . '.webp';
        $destination_path = $target_dir . $unique_name;

        if (convert_to_webp($file['tmp_name'], $destination_path, $quality)) {
            return $unique_name;
        }

        return null;
    }
}

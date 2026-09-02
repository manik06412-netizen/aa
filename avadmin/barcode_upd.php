<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $errors = [];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = ["jpeg", "jpg", "png"];

        if (!in_array($file_ext, $allowed_exts)) {
            $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }

        if (empty($errors)) {
            $upload_dir = 'Res_img/barcode/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $upload_path = $upload_dir . basename($file_name);

            if (move_uploaded_file($file_tmp, $upload_path)) {
                echo "File uploaded successfully!";
            } else {
                echo "Failed to move uploaded file.";
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    } else {
        echo "No file uploaded.";
    }
} else {
    echo "Invalid request.";
}
?>

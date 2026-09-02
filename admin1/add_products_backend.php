<?php
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["admin1_user"])) {
    echo json_encode(["status" => 0, "error" => "Unauthenticated"]);
    exit;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}


function handleFileUpload($file, $target_dir, $allowed_formats, $max_size, $file_type) {
    $response = [];
    $file_name = basename($file['name']);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $fileExtension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response[] = "$file_type $file_name encountered an upload error.";
        return null;
    }

    if ($file['size'] > $max_size) {
        $response[] = "$file_type $file_name is too large.";
        $uploadOk = 0;
    }

    if (!in_array($fileExtension, $allowed_formats)) {
        $response[] = "$file_type $file_name is not an allowed format.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $response[] = "$file_type $file_name was not uploaded.";
    } else {
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0777, true);
        }
        $unique_file_name = uniqid() . rand(1000, 9000) . '.' . $fileExtension;
        $target_file = rtrim($target_dir, '/') . '/' . $unique_file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Also sync to avadmin and admin
            @copy($target_file, __DIR__ . '/../avadmin/' . $target_file);
            @copy($target_file, __DIR__ . '/../admin/' . $target_file);
            $response[] = "$file_type $file_name has been uploaded as $unique_file_name.";
            return $target_file; 
        } else {
            $response[] = "Error uploading $file_type $file_name.";
        }
    }

    return null;
}

function processValues($values) {
    return implode(',', $values);
}

if (isset($_POST['product_submit'])) {
    $dishName = mysqli_real_escape_string($con, $_POST['d_name']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $subCategory = mysqli_real_escape_string($con, $_POST['sc']);
    $bname = mysqli_real_escape_string($con, $_POST['brand_name']);
    $p_description = mysqli_real_escape_string($con, $_POST['p_description']);
    $no_of_items = mysqli_real_escape_string($con, $_POST['no_of_items']);
    $barcode = mysqli_real_escape_string($con, $_POST['barcode']);
    $age_range = mysqli_real_escape_string($con, $_POST['age_range']);
    $ratings = mysqli_real_escape_string($con, $_POST['ratings']);
    $best_before = mysqli_real_escape_string($con, $_POST['best_before']);
    $keywords = mysqli_real_escape_string($con, $_POST['keywords']);
    $delivery_info = isset($_POST['delivery_info']) ? $_POST['delivery_info'] : [];
    $refund = mysqli_real_escape_string($con, $_POST['refund']);
    $delivery_mode = isset($_POST['delivery_mode']) ? $_POST['delivery_mode'] : [];
    $delivery_option = mysqli_real_escape_string($con, $_POST['delivery_option']);
    $product_status = mysqli_real_escape_string($con, $_POST['product_status']);
    $barcode_image = $_POST['barcode_image'];
    

    $ran = rand(0, 9999);
    $d = date('d-m-Y');

    $image_response = [];
    $uploaded_images = []; 
    $image_dir = 'Res_img/dishes/'; 

    $check = "SELECT * FROM dishes WHERE dish_name = ?";
    $stmt = $con->prepare($check);
    $stmt->bind_param("s", $dishName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(["status" => 2]);
        exit;
    }

    $verification = $con->prepare("SELECT c_id FROM res_category WHERE c_name = ?");
    $verification->bind_param("s", $category);
    $verification->execute();
    $result = $verification->get_result();
    $roww = $result->fetch_assoc();
    $c_id = $roww['c_id'] ?? null;

    $sta = isset($_POST['deals']) ? '1' : '0';

    $delivery_info_final = processValues($delivery_info);
    $delivery_mode_final = processValues($delivery_mode);

    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        $totalFiles = count($_FILES['files']['name']);
    
        for ($i = 0; $i < $totalFiles; $i++) {
            $file = [
                'name' => $_FILES['files']['name'][$i],
                'tmp_name' => $_FILES['files']['tmp_name'][$i],
                'size' => $_FILES['files']['size'][$i],
                'error' => $_FILES['files']['error'][$i],
                'type' => $_FILES['files']['type'][$i]
            ];
    
            $unique_file_name = handleFileUpload($file, $image_dir, ["jpg", "jpeg", "png", "gif", "jfif", "webp", "svg"], 5000000, 'Image');
            if ($unique_file_name) {
                $uploaded_images[] = $unique_file_name;
            }
        }
    
        if (!empty($uploaded_images)) {
            $image_response[] = "Images uploaded successfully.";
        } else {
            $image_response[] = "No images were uploaded.";
        }
    }

    $image_1 = $uploaded_images[0] ?? '';
    $image_2 = $uploaded_images[1] ?? '';
    $image_3 = $uploaded_images[2] ?? '';
    $image_4 = $uploaded_images[3] ?? '';
    $image_5 = $uploaded_images[4] ?? '';

      
    

$sql = "INSERT INTO dishes (rs_id, dish_name, description, img, img2, img3, img4, img5, category, date_of_adding, 
    subcate, cateid, brand_name, no_items, barcode, age_range, ratings, best_before, 
    keywords, deliv_info, refund, deliv_mode, deliv_opt, status, barcode_img
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$products_reg = $con->prepare($sql);

if (!$products_reg) {
    echo json_encode(["status" => 3, "error" => $con->error]);
    exit;
}


$products_reg->bind_param(
    "isssssssssssssssissssssis",
    $ran, $dishName, $p_description, $image_1, $image_2, $image_3, $image_4, $image_5,
    $category, $d, $subCategory, $c_id, $bname, $no_of_items, $barcode, $age_range, 
    $ratings, $best_before, $keywords, $delivery_info_final, $refund, $delivery_mode_final, 
    $delivery_option, $product_status, $barcode_image);


if ($products_reg->execute()) {
    echo json_encode(["status" => 1, 'prd_id' => $ran]);
} else {
    echo json_encode(["status" => 3, "error" => $products_reg->error]);
}

$products_reg->close();


}
?>
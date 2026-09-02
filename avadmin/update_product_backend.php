<?php
session_start();
error_reporting(0);
include("inc/config.php");

function uploadImage($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null; 
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedExtensions)) {
        return null; 
    }
    $newFileName = uniqid('img_', true) . '.' . $fileExt;
    $uploadDir = "Res_img/dishes/";

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return null;
        }
    }
    $store = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmp, $store)) {
        return $store;
    } else {
        return null; 
    }
}

function processValues($values) {
    return implode(',', $values);
}

if (isset($_POST['product_submit'])) {
    $product_id = $_POST['product_id'];
    // $product_rs_id = trim(mysqli_real_escape_string($con, $_POST['product_rs_id']));
    $dishName =trim(mysqli_real_escape_string($con, $_POST['d_name']));
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
  $ran = rand(0, 9999);
    $d = date('d/m/Y');

    if (empty($p_description)) {
        echo json_encode(["status" => 6]);
        exit;
    }
    $check = "SELECT * FROM dishes";
    $result = $con->query($check);
 
    if ($result === false) {
        die("Query failed: " . $con->error);
    }
    
    while ($row = $result->fetch_assoc()) {
    if ($row['rs_id'] == $product_id) {
        continue;
    }

    if ($row['dish_name'] == $dishName) {
        echo json_encode(["status" => 2]);
        exit;
    }
    }
    $result->free();

    $verification = $con->prepare("SELECT c_id FROM res_category WHERE c_name = ?");
    $verification->bind_param("s", $category);
    $verification->execute();
    $result = $verification->get_result();
    $roww = $result->fetch_assoc();
    $c_id = $roww['c_id'] ?? null;

    $sta = isset($_POST['deals']) ? '1' : '0';

    $delivery_info_final = processValues($delivery_info);
    $delivery_mode_final = processValues($delivery_mode);
    
    
           $imagePaths = [];

            $imageFields = ['image1', 'image2', 'image3', 'image4', 'image5'];

            foreach ($imageFields as $field) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                
                    $imagePaths[$field] = uploadImage($_FILES[$field]);
                } else {
                
                    $imagePaths[$field] = isset($_POST['existing_' . $field]) ? $_POST['existing_' . $field] : null;
                }
            }
            
            $image_1 = $imagePaths['image1'];
            $image_2 = $imagePaths['image2'];
            $image_3 = $imagePaths['image3'];
            $image_4 = $imagePaths['image4'];
            $image_5 = $imagePaths['image5'];


                $update_stmt = $con->prepare("UPDATE dishes 
                SET dish_name = ?, description = ?, img = ?, img2 = ?, img3 = ?, img4 = ?, img5 = ?, category = ?, date_of_adding = ?, subcate = ?, cateid = ?, brand_name = ?, no_items = ?, barcode = ?, age_range = ?, ratings = ?, best_before = ?, keywords = ?, deliv_info = ?, refund = ?, deliv_mode = ?, deliv_opt = ?, status = ? 
                WHERE rs_id = ?");

                if (!$update_stmt) {
                echo json_encode(["status" => 3, "error" => $con->error]);
                exit;
                }

   
                $update_stmt->bind_param( "sssssssssssssssissssssii", 
                $dishName, $p_description, $image_1, $image_2, $image_3, $image_4, $image_5, 
                $category, $d, $subCategory, $c_id, $bname, $no_of_items, $barcode, $age_range, 
                $ratings, $best_before, $keywords, $delivery_info_final, $refund, $delivery_mode_final, 
                $delivery_option, $product_status, $product_id
                );

    
                if ($update_stmt->execute()) {
                // echo "<script>alert('updated successfully);window.location.href='products_list.php';</script>";
                echo json_encode(["status" => 1, 'prd_id' => $ran]);
            } else {
                echo json_encode(["status" => 4, "error" => $update_stmt->error]);
                }

                $update_stmt->close();

}

?>
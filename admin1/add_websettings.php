<?php
include "../dbconnect.php";
session_start();

// Function to handle file uploads
function uploadFile($file, $uploadDir) {
    $uploadPath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $uploadPath;
    }
    return false;
}

// Function to execute a database query and handle errors
function executeQuery($con, $query, $successMessage, $redirect) {
    if (mysqli_query($con, $query)) {
        echo "<script>alert('$successMessage');window.location.href='$redirect';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Logo Update
if (isset($_POST['submit']) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $fileType = mime_content_type($_FILES['image']['tmp_name']);
    if (in_array($fileType, ['image/jpg', 'image/jpeg', 'image/png'])) {
        $imagePath = uploadFile($_FILES['image'], './uploads/logo/');
        if ($imagePath) {
            $updateLogoQuery = "UPDATE logo SET image = '$imagePath'";
            executeQuery($con, $updateLogoQuery, 'Logo updated successfully.', 'web_settings.php?tab=logo-section');
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "<script>alert('Only JPG and PNG images are allowed.');window.location.href='web_settings.php?tab=logo-section';</script>";
    }
}

// Favicon Update
if (isset($_POST['fevicon']) && isset($_FILES['fevicon']) && $_FILES['fevicon']['error'] == 0) {
    $fileType = mime_content_type($_FILES['fevicon']['tmp_name']);
    if (in_array($fileType, ['image/jpg', 'image/jpeg', 'image/png'])) {
        $imagePath = uploadFile($_FILES['fevicon'], './uploads/fevicon/');
        if ($imagePath) {
            $updateFeviconQuery = "UPDATE fevicon SET fevicon = '$imagePath'";
            executeQuery($con, $updateFeviconQuery, 'Fevicon updated successfully.', 'web_settings.php?tab=fevicon-section');
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "<script>alert('Only JPG and PNG images are allowed.');window.location.href='web_settings.php?tab=fevicon-section';</script>";
    }
}

// Contact Details Update
if (isset($_POST['contact'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $copyright = mysqli_real_escape_string($con, $_POST['copyrights']);
    $address = mysqli_real_escape_string($con, $_POST['contact_address']);
    $email = mysqli_real_escape_string($con, $_POST['contact_email']);
    $phone_number = mysqli_real_escape_string($con, $_POST['contact_phone']);
    $alternate = mysqli_real_escape_string($con, $_POST['alternate_number']);
    $whatsapp = mysqli_real_escape_string($con, $_POST['whatsapp']);
    $facebook = mysqli_real_escape_string($con, $_POST['facebook']);
    $instagram = mysqli_real_escape_string($con, $_POST['instagram']);
    $map = mysqli_real_escape_string($con, $_POST['contact_map']);

    $updateContactQuery = "
        UPDATE footer_contact
        SET
            copyrights = '$copyright',
            contact_address = '$address',
            contact_email = '$email',
            contact_phone = '$phone_number',
            alternate_number = '$alternate',
            whatsapp = '$whatsapp',
            facebook = '$facebook',
            instagram = '$instagram',
            contact_map = '$map'
        WHERE id = $id";
    
    executeQuery($con, $updateContactQuery, 'Contact details updated successfully.', 'web_settings.php?tab=contact-section');
}

// About Us Update
if (isset($_POST['about_us'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $about = mysqli_real_escape_string($con, $_POST['about_us']);
    
    $updateAboutQuery = "UPDATE about_us SET about_us='$about' WHERE id = $id";
    executeQuery($con, $updateAboutQuery, 'About details updated successfully.', 'content_setting.php');
}

// Banner Update
if (isset($_POST['banner']) && isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
    $fileType = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
    if (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
        $bannerPath = uploadFile($_FILES['banner'], './uploads/banner/');
        if ($bannerPath) {
            $insertBannerQuery = "INSERT INTO slider (banner_img) VALUES ('$bannerPath')";
            executeQuery($con, $insertBannerQuery, 'Banner inserted successfully.', 'content_banner.php?tab=content-section');
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "<script>alert('Only JPG and PNG images are allowed.');window.location.href='content_banner.php';</script>";
    }
}

if (isset($_POST['topbar']) && isset($_POST['content'])) {
    $content = mysqli_real_escape_string($con, $_POST['content']);
    $query = "INSERT INTO topbar (content) VALUES ('$content')";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Topbar content added successfully.');window.location.href='web_settings.php?tab=content-section';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

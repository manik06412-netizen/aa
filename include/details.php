<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(0);
$_SESSION['compledorder'] =0;
include('include/header.php');
include('dbconnect.php');
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
<style>
/* ══════════════════════════════════════════════════════════════
   KARUDA COMPUTERS — PRODUCT DETAILS CYBER TECH STYLING
══════════════════════════════════════════════════════════════ */
#page {
    background: #F8FAFC !important;
}
.margin_60_35 {
    padding-top: 25px !important;
    padding-bottom: 50px !important;
}
.borders_top {
    border-top: none !important;
}
.right_side_box h2 {
    font-family: 'Outfit', sans-serif !important;
    font-size: 24px !important;
    font-weight: 800 !important;
    color: #0F172A !important;
    letter-spacing: -0.3px;
    margin-top: 5px !important;
    line-height: 1.3;
}

/* Variant & Price Pods */
.product_price_size {
    border: 1.5px solid #E2E8F0 !important;
    padding: 12px 14px !important;
    border-radius: 12px !important;
    background: #F8FAFC !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02) !important;
    position: relative !important;
    cursor: pointer !important;
    text-align: left !important;
    transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
}
.product_price_size:hover {
    border-color: #0070F3 !important;
    background: #EFF6FF !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 112, 243, 0.12) !important;
}
.product_price_size.selected,
.product_price_size[style*="border: 2.3px solid"] {
    border: 2px solid #0070F3 !important;
    background: #EFF6FF !important;
    box-shadow: 0 4px 16px rgba(0, 112, 243, 0.18) !important;
}
.savings_price {
    background: linear-gradient(135deg, #EF4444, #DC2626) !important;
    color: #ffffff !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    padding: 2px 7px !important;
    border-radius: 4px !important;
    position: absolute !important;
    top: -10px !important;
    left: 14px !important;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.35);
}
.weight_of_pro {
    font-size: 12px !important;
    color: #64748B !important;
    font-weight: 600 !important;
    margin-bottom: 2px !important;
}
.current_price {
    color: #0D47A1 !important;
    font-size: 17px !important;
    font-weight: 800 !important;
}
.old_prices {
    color: #94A3B8 !important;
    font-size: 12px !important;
    margin-left: 4px;
}

/* Price Sidebar Display */
.price_updated_module {
    font-size: 26px !important;
    font-weight: 800 !important;
    color: #0D47A1 !important;
    font-family: 'Outfit', sans-serif !important;
}
.price_updated_module2 {
    font-size: 15px !important;
    color: #94A3B8 !important;
    text-decoration: line-through;
}
.discount_updated_module {
    font-size: 11px !important;
    font-weight: 800 !important;
    padding: 3px 8px !important;
    background: #EFF6FF !important;
    color: #0070F3 !important;
    border: 1px solid #BFDBFE !important;
    border-radius: 6px !important;
    margin-left: 8px !important;
}

/* Quantity Controls */
.quantity_display_input {
    width: 48px !important;
    height: 36px !important;
    border: 1px solid #E2E8F0 !important;
    border-left: none !important;
    border-right: none !important;
    text-align: center !important;
    font-weight: 700 !important;
    color: #0F172A !important;
    background-color: #ffffff !important;
}
.decrement, .increment {
    width: 36px !important;
    height: 36px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #F1F5F9 !important;
    border: 1px solid #E2E8F0 !important;
    color: #334155 !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    transition: all 0.2s ease;
}
.decrement { border-radius: 8px 0 0 8px !important; }
.increment { border-radius: 0 8px 8px 0 !important; }
.decrement:hover, .increment:hover {
    background: #0070F3 !important;
    color: #ffffff !important;
    border-color: #0070F3 !important;
}

/* Primary Action Buttons */
a.btn_1.full-width.purchases,
.btn_1.full-width.purchases,
#buy_now {
    background: linear-gradient(135deg, #0D47A1, #0070F3) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 13px 20px !important;
    font-size: 14px !important;
    font-weight: 800 !important;
    letter-spacing: 0.6px !important;
    text-transform: uppercase !important;
    box-shadow: 0 4px 16px rgba(13, 71, 161, 0.3) !important;
    transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    text-align: center !important;
    display: block !important;
}
a.btn_1.full-width.purchases:hover,
#buy_now:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    box-shadow: 0 8px 22px rgba(0, 188, 212, 0.45) !important;
    transform: translateY(-2px);
    color: #ffffff !important;
}

a.btn_1.full-width.purchase,
.btn_1.full-width.purchase,
#add_to_cart {
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 13px 20px !important;
    font-size: 14px !important;
    font-weight: 800 !important;
    letter-spacing: 0.6px !important;
    text-transform: uppercase !important;
    box-shadow: 0 4px 16px rgba(0, 188, 212, 0.3) !important;
    transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    text-align: center !important;
    display: block !important;
}
a.btn_1.full-width.purchase:hover,
#add_to_cart:hover {
    background: linear-gradient(135deg, #0D47A1, #0070F3) !important;
    box-shadow: 0 8px 22px rgba(13, 71, 161, 0.45) !important;
    transform: translateY(-2px);
    color: #ffffff !important;
}

/* Wishlist Button */
a.btn_1.full-width.wish,
.btn_1.full-width.wish,
#heart_btn {
    background: #FFF1F2 !important;
    color: #E11D48 !important;
    border: 1.5px solid #FECDD3 !important;
    border-radius: 10px !important;
    padding: 11px 20px !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    transition: all 0.25s ease !important;
    text-align: center !important;
    display: block !important;
}
a.btn_1.full-width.wish:hover,
#heart_btn:hover {
    background: #FFE4E6 !important;
    border-color: #FDA4AF !important;
    transform: translateY(-2px);
    color: #E11D48 !important;
    box-shadow: 0 4px 12px rgba(225, 29, 72, 0.2);
}

/* Bottom Description & Reviews Tabs */
.foot_of_details {
    background: #0B192C !important;
    border-radius: 12px !important;
    padding: 6px !important;
    margin-bottom: 25px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-around !important;
}
.details_of_desc {
    padding: 10px 18px !important;
    color: #CBD5E1 !important;
    text-align: center !important;
    cursor: pointer !important;
    margin: 0 !important;
    background: transparent !important;
    border-radius: 8px !important;
    transition: all 0.2s ease !important;
}
.details_of_desc h6 {
    color: #CBD5E1 !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    margin: 0 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}
.details_of_desc:hover,
.details_of_desc.active {
    background: rgba(0, 188, 212, 0.15) !important;
}
.details_of_desc:hover h6,
.details_of_desc.active h6 {
    color: #00BCD4 !important;
}

/* Section Headings */
h3.kc-detail-section-title,
#Description h3,
#write_rev h3 {
    color: #0B192C !important;
    font-family: 'Outfit', sans-serif !important;
    font-size: 20px !important;
    font-weight: 800 !important;
    letter-spacing: -0.3px !important;
    text-transform: uppercase !important;
}

/* Review Stars */
.rating-container .fa-star,
.rating-container .star-icon,
.icon-star.colored,
.fa-star.colored {
    color: #F59E0B !important;
}

/* Thumbnails */
.slider_foots {
    border-radius: 8px !important;
    border: 1.5px solid #E2E8F0 !important;
    transition: all 0.2s ease !important;
    cursor: pointer !important;
}
.slider_foots:hover {
    border-color: #0070F3 !important;
    box-shadow: 0 2px 10px rgba(0, 112, 243, 0.2);
}

/* Image Zoom */
figure.goOnZoom {
    position: relative;
    overflow: hidden;
    cursor: zoom-in;
    width: 100% !important;
    max-width: 420px;
    height: 400px;
    padding: 0px !important;
    margin: 0 auto !important;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    border-radius: 12px;
}
figure.goOnZoom img {
    transition: opacity .4s;
    display: block;
    width: 100%;
    height: 400px;
    object-fit: contain;
}
figure.goOnZoom img:hover {
    opacity: 0;
}
</style>


<?php
$s_id = $_GET['id'];
$_SESSION['did'] = $s_id;
$s_id;
$fetc = mysqli_query($con, "SELECT * FROM dishes where rs_id='$s_id' ");

if ($row = mysqli_fetch_array($fetc)) {
    $title = $row['dish_name'];
    $_SESSION['c_name'] = $title;
    $_SESSION['catey'] = $row['category'];
    $_SESSION['id1'] = $s_id;
    $cat = $row['category'];
    $imgs = $row['img'];
    $img2 = $row['img2'];
    $img3 = $row['img3'];
    $pr_id = $row['rs_id'];
    $stock = $row['stock'];
    $keywords = $row['keywords'];
    $key2 = $row['k2'];
    $key3 = $row['k3'];
    $key4 = $row['k4'];
    $key5 = $row['k5'];
    $bestBefore = $row['best_before'];
    $description = $row['description'];
    $category = $row['category'];
    $vendor_id = $row['vendor_id'];
    $d_id = $row['d_id'];
    $rs_id = $row['rs_id'];
    $dish_name = $row['dish_name'];
    $Total_Quantity = $row['Total_Quantity'];
    $stock = $row['stock'];
    $img = $row['img'];
    $img4 = $row['img4'];
    $img5 = $row['img5'];
    $best_before = $row['best_before'];
    $brand = $row['brand_name'];
    $food_type = $row['food_type'];
    $category = $row['category'];
    $date_of_adding = $row['date_of_adding'];
    $subcate = $row['subcate'];
    $cateid = $row['cateid'];
    $status = $row['status'];
    $vendor_id = $row['vendor_id'];
    $exp_per = $row['exp_per'];
    $distribution = $row['distribution'];
    $portals = $row['portals'];
    $currency = $row['currency'];
    $languages = $row['languages'];
    $payment = $row['payment'];
    $newprice = $row['pp'];
    $oldprice = $row['oprice'];
    $discount = $row['discount'];
    $brand_name = $row['brand_name'];
    $ac_brand = $row['brand_option'];
    $detailed_desc = $row['detailed_desc'];
    $img3 = $row['img3'];
    $img4 = $row['img4'];
    $delivery_option = $row['deliv_opt'];
    $delivery_info = $row['deliv_info'];
    $age = $row['age_range'];
    $del_info = $row['deliv_info'];
    $refund = $row['refund'];
    $myrat=$row['ratings'];
    $delivery_option = $row['deliv_opt'];
    $delivery_info = $row['deliv_info'];
    if ($del_info == 'Local delivery') {
        $delinfo = "100% delivery in India";
    } else if ($del_info == 'Worldwide delivery') {
        $delinfo = "100% delivery all over the world";
    } else {
        $delinfo = "Shipment on Selective Country";
    }

    if ($refund == 'Refundable') {
        $reinfo = "100% Refundable Product";
        $textColor = "#0D47A1"; // Karuda electric blue for refundable
    } else {
        $reinfo = "Non Refundable Procedure";
        $textColor = "#EF4444"; // Tech red for non-refundable
    }
} 


function star_ratings($con,$rs_id,$myrat)
{
    $averagesql1 = "SELECT AVG(uratings) AS overall_avg_rating FROM cust_reviews
    WHERE cid = $rs_id";
    $averageres1 = mysqli_query($con, $averagesql1);
    if ($averagerow1 = mysqli_fetch_array($averageres1)) {
        $averageRating2 = $averagerow1['overall_avg_rating'];
    }
    if ($averageRating2 == 0) {
        $averageRating2 = $myrat;
    }
    $totalRevSql = "SELECT COUNT(*) AS total_reviews FROM cust_reviews WHERE cid =$rs_id";
    $totalRevRes = mysqli_query($con, $totalRevSql);
    if ($totalRevRow = mysqli_fetch_array($totalRevRes)) {
        $totalRev = $totalRevRow['total_reviews'];
    }
    $percentage = ($averageRating2 / 5) * 100;
    $num = number_format($averageRating2, 2);

?>
<?php
    if ($num >= 1 && $num <= 5) {
        $widthPercentage = ($num / 5) * 100;

        if ($num >= 1 && $num <= 5) {
            // echo'<p class="star-rating d-block">';
            for ($i = 1; $i <= 5; $i++) {
                echo '<i class="icon-star' . ($i <= $num ? ' colored' : '-empty') . '"></i>';
            }
            echo '<small>(' . $totalRev . ' Reviews)</small>';
        }
    }
}

?>
<main>
    <div id="page">
        <?php include('include/scroller_navbar.php') ?>
        <header class="kc-header-container">
            <?php include('include/navbar.php'); ?>
        </header>

        <div class="container-fluid margin_60_35">
            <div class="row bg-white">
                <div class="col-12 borders_top">
                    <?php 
                    if(mysqli_num_rows($fetc)){
                    ?>
                    <div class="container-fluid">
                        <div class="row  ">
                            <div class="col-lg-5 col-12 col-sm-12 " id="faq_cat">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="  mb-2 p-2">
                                            <div class="mb-2 ">
                                                <?php
                                                $resolveDetailImg = function($img) {
                                                    if (empty($img)) return '';
                                                    $clean = preg_replace('#/+#', '/', ltrim($img, './'));
                                                    $base = defined('BASE_URL') ? BASE_URL : '';
                                                    // 1. Direct project root check
                                                    if (file_exists(__DIR__ . '/../../../' . $clean)) {
                                                        return $base . $clean;
                                                    } elseif (file_exists(__DIR__ . '/../../' . $clean)) {
                                                        return $base . $clean;
                                                    } elseif (file_exists(__DIR__ . '/../' . $clean)) {
                                                        return $base . $clean;
                                                    } elseif (file_exists($clean)) {
                                                        return $base . $clean;
                                                    }
                                                    // 2. avadmin / admin subfolders
                                                    if (file_exists(__DIR__ . '/../../../avadmin/' . $clean)) {
                                                        return $base . 'avadmin/' . $clean;
                                                    } elseif (file_exists(__DIR__ . '/../../../admin/' . $clean)) {
                                                        return $base . 'admin/' . $clean;
                                                    } elseif (file_exists(__DIR__ . '/../avadmin/' . $clean)) {
                                                        return $base . 'avadmin/' . $clean;
                                                    } elseif (file_exists(__DIR__ . '/../admin/' . $clean)) {
                                                        return $base . 'admin/' . $clean;
                                                    }
                                                    return $base . $clean;
                                                };
                                                $noImagePlaceholder = (defined('BASE_URL') ? BASE_URL : '') . 'img/karuda_logo.png';

                                                $src1 = $resolveDetailImg($imgs);
                                                $src2 = $resolveDetailImg($img2);
                                                $src3 = $resolveDetailImg($img3);
                                                $src4 = $resolveDetailImg($img4);
                                                $src5 = $resolveDetailImg($img5);
                                                ?>
                                                <?php if (!empty($imgs)) { ?>
                                                <div class="row pt-1">
                                                    <img src="<?php echo htmlspecialchars($src1); ?>"
                                                        class="img-thumbnail slider_foots p-1 w-100 h-100"
                                                        alt="Small Image 1"
                                                        onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                </div>
                                                <?php } ?>

                                                <?php if (!empty($img2)) { ?>
                                                <div class="row pt-1">
                                                    <img src="<?php echo htmlspecialchars($src2); ?>"
                                                        class="img-thumbnail slider_foots p-1 w-100" alt="Small Image 2"
                                                        onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                </div>
                                                <?php } ?>

                                                <?php if (!empty($img3)) { ?>
                                                <div class="row pt-1">
                                                    <img src="<?php echo htmlspecialchars($src3); ?>"
                                                        class="img-thumbnail slider_foots p-1 w-100" alt="Small Image 3"
                                                        onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                </div>
                                                <?php } ?>

                                                <?php if (!empty($img4)) { ?>
                                                <div class="row pt-1">
                                                    <img src="<?php echo htmlspecialchars($src4); ?>"
                                                        class="img-thumbnail slider_foots p-1 w-100" alt="Small Image 4"
                                                        onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                </div>
                                                <?php } ?>

                                                <?php if (!empty($img5)) { ?>
                                                <div class="row pt-1">
                                                    <img src="<?php echo htmlspecialchars($src5); ?>"
                                                        class="img-thumbnail slider_foots p-1 w-100" alt="Small Image 5"
                                                        onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-10">
                                        <div class="box_style_cat m-2" id="faq_box">
                                            <div id="carouselExampleControls" class="carousel slide"
                                                data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php
                                                    // Track if the first item is active
                                                    $activeSet = false;

                                                    if (!empty($imgs)) { ?>
                                                    <div class="carousel-item <?php if (!$activeSet) {
                                                                                        echo 'active';
                                                                                        $activeSet = true;
                                                                                    } ?>">
                                                        <figure class='goOnZoom'
                                                            style="background-image: url('<?php echo htmlspecialchars($src1); ?>');"
                                                            onmousemove="zoom(event)" ontouchmove="zoom(event)">
                                                            <img src="<?php echo htmlspecialchars($src1); ?>"
                                                                onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                        </figure>
                                                    </div>
                                                    <?php }

                                                    if (!empty($img2)) { ?>
                                                    <div class="carousel-item <?php if (!$activeSet) {
                                                                                        echo 'active';
                                                                                        $activeSet = true;
                                                                                    } ?>">
                                                        <figure class='goOnZoom'
                                                            style="background-image: url('<?php echo htmlspecialchars($src2); ?>');"
                                                            onmousemove="zoom(event)" ontouchmove="zoom(event)">
                                                            <img src="<?php echo htmlspecialchars($src2); ?>"
                                                                onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                        </figure>
                                                    </div>
                                                    <?php }

                                                    if (!empty($img3)) { ?>
                                                    <div class="carousel-item <?php if (!$activeSet) {
                                                                                        echo 'active';
                                                                                        $activeSet = true;
                                                                                    } ?>">
                                                        <figure class='goOnZoom'
                                                            style="background-image: url('<?php echo htmlspecialchars($src3); ?>');"
                                                            onmousemove="zoom(event)" ontouchmove="zoom(event)">
                                                            <img src="<?php echo htmlspecialchars($src3); ?>"
                                                                onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                        </figure>
                                                    </div>
                                                    <?php } ?>
                                                    <?php 

if (!empty($img4)) { ?>
                                                    <div class="carousel-item <?php if (!$activeSet) {
                                    echo 'active';
                                    $activeSet = true;
                                } ?>">
                                                        <figure class='goOnZoom'
                                                            style="background-image: url('<?php echo htmlspecialchars($src4); ?>');"
                                                            onmousemove="zoom(event)" ontouchmove="zoom(event)">
                                                            <img src="<?php echo htmlspecialchars($src4); ?>"
                                                                onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                        </figure>
                                                    </div>
                                                    <?php } ?>
                                                    <?php 

if (!empty($img5)) { ?>
                                                    <div class="carousel-item <?php if (!$activeSet) {
                                    echo 'active';
                                    $activeSet = true;
                                } ?>">
                                                        <figure class='goOnZoom'
                                                            style="background-image: url('<?php echo htmlspecialchars($src5); ?>');"
                                                            onmousemove="zoom(event)" ontouchmove="zoom(event)">
                                                            <img src="<?php echo htmlspecialchars($src5); ?>"
                                                                onerror="this.onerror=null; this.src='<?php echo $noImagePlaceholder; ?>';">
                                                        </figure>
                                                    </div>
                                                    <?php } ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-5 col-12 " id="faq">
                                <div class="right_side_box">
                                    <h2 class="mt-2" style="font-family:'Outfit',sans-serif;font-weight:800;color:#0F172A;"><?php echo htmlspecialchars($title); ?></h2>
                                    <div class="row ">
                                        <div class="col-12 mt-1  col-lg-6">
                                            <span class="d-block "><i class="fa fa-tags text-primary" aria-hidden="true"></i>
                                                <strong>Brand:</strong> <?= htmlspecialchars($brand_name); ?></span>
                                        </div>
                                        <div class='col-12 mt-2  col-lg-6 text-left '>
                                            <span class="d-block "><i class="fa fa-star text-warning" aria-hidden="true"></i>
                                                <strong>Ratings:</strong> <?php star_ratings($con, $rs_id,$myrat); ?></span>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-12 mt-1  col-lg-6">
                                            <span class="d-block "><i class="fa fa-layer-group text-info" aria-hidden="true"></i>
                                                <strong>Category:</strong> <?= htmlspecialchars($category); ?></span>
                                        </div>
                                        <div class='col-12 mt-2  col-lg-6 text-left '>
                                            <span class="d-block "><i class="fa fa-microchip text-purple" aria-hidden="true"></i>
                                                <strong>Sub Category:</strong> <?= htmlspecialchars($subcate); ?></span>
                                        </div>
                                        <!-- <div class="col-12 mt-1  col-lg-12">
                                            <span class="d-block ">
                                                <b> Ratings:</b>
                                                 </ul>  <?php star_ratings($con, $rs_id,$myrat); ?></span>
                                        </div> -->


                                        <div class="col-12  mb-2" style="text-align:justify;">
                                            <!-- <span>Description: </span> -->
                                            <span class="ml-1 mt-2 text-ellipsis"><?= $description; ?></span>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <span>Options:</span>
                                        </div>

                                        <?php
                                        $fetch_price = mysqli_query($con, "SELECT * FROM price WHERE pcode='$pr_id' ORDER BY id ASC");
                                        if (!$fetch_price || mysqli_num_rows($fetch_price) == 0) {
                                            $fetch_price = mysqli_query($con, "SELECT * FROM price WHERE pcode='$s_id' OR id='$d_id'");
                                        }
                                        $saving_price = 0;
                                        $looping_function = 0;
                                        $loop_status_find = 0;
                                        $current_status = 'Instock';
                                        $product_price_1 = 0;
                                        $product_old_1 = 0;
                                        $saving_price_1 = 0;
                                        $discount_per = 0;
                                        $product_price_id_1 = 0;

                                        if ($fetch_price && mysqli_num_rows($fetch_price) > 0) {
                                            while ($row3 = mysqli_fetch_array($fetch_price)) {
                                                $product_price = $row3['pp'];
                                                $product_old = $row3['oprice'];
                                                $product_qty = !empty($row3['qn']) ? $row3['qn'] : '1';
                                                $product_weight = !empty($row3['wg']) ? $row3['wg'] : 'Unit';
                                                $product_id = $row3['id'];
                                                $saving_price = ($product_old > $product_price) ? ($product_old - $product_price) : 0;
                                                $product_status = !empty($row3['s_status']) ? $row3['s_status'] : (!empty($row3['stk_status']) ? $row3['stk_status'] : 'Instock');
                                                $total_stock = isset($row3['total_stock']) ? $row3['total_stock'] : (isset($row3['total']) ? $row3['total'] : 25);
                                                $discount_per = !empty($row3['discount']) ? $row3['discount'] : 0;
                                                if($loop_status_find == 0){
                                                    $current_status = $product_status;
                                                    $product_price_1 = $product_price;
                                                    $product_old_1 = $product_old;
                                                    $saving_price_1 = $saving_price;
                                                    $product_price_id_1 = $product_id;
                                                    $loop_status_find = 1;
                                                }
                                        ?>

                                        <div class="col-12 col-lg-4 mb-3 mt-2">
                                            <div class="product_price_size" id="product_price_size<?= $product_id; ?>"
                                                onclick="GET_CURRENT('<?= $product_id; ?>')">
                                                <input type="hidden" id="pro_current_p<?= $product_id; ?>"
                                                    value="<?= $product_price; ?>">
                                                <input type="hidden" id="pro_old_p<?= $product_id; ?>"
                                                    value="<?= $product_old; ?>">
                                                <input type="hidden" id="savings_price<?= $product_id; ?>"
                                                    value="<?= $saving_price; ?>">

                                                <input type="hidden" id="product_status<?= $product_id; ?>"
                                                    value="<?=$product_status; ?>" name="price_status">

                                                <input type="hidden" id="product_quantity<?= $product_id; ?>"
                                                    value="<?=$total_stock; ?>" name="qty_total">


                                                <span class="savings_price">Save <i
                                                        class="icon-rupee"></i><?= $saving_price; ?></span>
                                                <span class="d-block weight_of_pro"><?= $product_qty; ?> -
                                                    <?= $product_weight; ?></span>
                                                <span class="current_price"><i
                                                        class="icon-rupee"></i><?= $product_price; ?></span>

                                                <del class="old_prices text-muted"> <i
                                                        class="icon-rupee"></i><?= $product_old; ?></del>
                                                <ul class="bullets bullets_price_div d-none"
                                                    id="selected_price<?= $product_id; ?>">
                                                    <li></li>
                                                </ul>
                                            </div>

                                        </div>
                                        <?php if($looping_function == 0){ 
                                                $GET_CURRENT_id = $product_id;
                                         $looping_function = 1; } } } ?>

                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="text-muted mt-2 d-inline-block">MRP: <del style="font-size:14px;color:#94A3B8;">₹ <?= $product_old_1; ?></del></span>
                                        </div>
                                        <div class="col-12">
                                            <h4 class="mt-1">
                                                <span class="text-muted" style="font-size:16px;">Price: </span>
                                                <span class="new-price" style="color:#0D47A1;font-size:26px;font-weight:800;font-family:'Outfit',sans-serif;">
                                                    ₹ <?= $product_price_1; ?>
                                                </span>
                                                <span class="badge badge-light border text-muted ml-2" style="font-size:11px;font-weight:600;">Excl. GST</span>
                                            </h4>
                                        </div>
                                        <div class="col-lg-7 mt-2 col-12">
                                            <?php
                                            if ($discount_per != 0) {
                                            ?>
                                            <div class="details_of_desc2 mt-2">
                                                <span id="discount_display"></span>
                                                <span>Get <?php echo $discount_per; ?>% discount on this product</span>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-12 mt-2 mb-2">
                                            <span style="font-size:12.5px;color:#0D47A1;font-weight:700;"><i class="fa fa-fire text-danger mr-1"></i> Popular Hardware: High demand item</span>
                                        </div>

                                        <div class="col-12 mt-1 mb-3">
                                            <div class="kc-tech-highlights-pod p-3" style="background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:12px;">
                                                <div class="d-flex align-items-center mb-2" style="gap:10px;">
                                                    <i class="fa fa-circle-check text-primary" style="font-size:15px;"></i>
                                                    <span style="font-size:13px; font-weight:600; color:#1E293B;">100% Genuine Certified Hardware</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2" style="gap:10px;">
                                                    <i class="fa fa-truck-fast text-info" style="font-size:15px;"></i>
                                                    <span style="font-size:13px; font-weight:600; color:#1E293B;">Express Nationwide Delivery</span>
                                                </div>
                                                <div class="d-flex align-items-center" style="gap:10px;">
                                                    <i class="fa fa-shield-alt text-primary" style="font-size:15px;"></i>
                                                    <span style="font-size:13px; font-weight:600; color:#1E293B;">Official Brand Warranty & Support</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <?php
                                    include("dbconnect.php");
                                    $syl = 0;
                                    $ud1 = $_SESSION['uid'];
                                    $zqswl = mysqli_query($con, "SELECT * FROM watch_list where userid='$ud1' and pr_id='$pr_id'");
                                    if (mysqli_num_rows($zqswl)) {
                                        $syl = 1;
                                    } ?>
                                </div>
                            </div>
                            <div class="col-lg-2  border-left" id="faq">
                                <div class="row mt-3">
                                    <div class="col-12 ">
                                        <h6 class="text-muted mt-1">PRICE: </h6>
                                    </div>
                                    <?php
                                    $fetch_price_1 = mysqli_query($con, "SELECT * FROM price WHERE pcode='$pr_id' ORDER BY id ASC LIMIT 1");
                                    if (!$fetch_price_1 || mysqli_num_rows($fetch_price_1) == 0) {
                                        $fetch_price_1 = mysqli_query($con, "SELECT * FROM price WHERE pcode='$s_id' OR id='$d_id' LIMIT 1");
                                    }
                                    $saving_price_1 = 0;
                                    $s_status = 'Instock';
                                    if ($row3 = mysqli_fetch_array($fetch_price_1)) {
                                        $product_price_1 = $row3['pp'];
                                        $product_price_id_1 = $row3['id'];
                                        $product_old_1 = $row3['oprice'];
                                        $saving_price_1 = ($product_old_1 > $product_price_1) ? ($product_old_1 - $product_price_1) : 0;
                                    }
                                    if (empty($current_status)) {
                                        $current_status = $s_status;
                                    }
                                    if ($current_status == 'Currently Unavailable') {
                                        $status_color = '#EF4444';
                                    } elseif ($current_status == 'Instock') {
                                        $status_color = '#0070F3';
                                    } else {
                                        $status_color = '#0F172A';
                                    }
                                    ?>
                                    <!-- Clean Price Box -->
                                    <div class="col-12 mb-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                                            <span id="current_status_of" style="color: <?= $status_color ?>; font-size: 13px; font-weight: 800; background: #EFF6FF; padding: 4px 12px; border-radius: 20px; border: 1px solid #DBEAFE; display: inline-flex; align-items: center;">
                                                <i class="fa fa-circle-check mr-1" style="font-size:11px;"></i> <?= $current_status; ?>
                                            </span>
                                        </div>

                                        <div class="d-flex align-items-baseline gap-2 mt-2">
                                            <span class="price_updated_module" style="font-size:28px; font-weight:800; color:#0D47A1; font-family:'Outfit',sans-serif;">
                                                ₹<span id="Price_update"><?=$product_price_1; ?></span>
                                            </span>
                                            <span class="price_updated_module2" style="display:none">₹<span id="old_Price_update"><?=$product_old_1; ?></span></span>
                                            <span class="discount_updated_module" id="discount_updated_module" style="background:linear-gradient(135deg, #0070F3, #00BCD4); color:#fff; font-size:11px; font-weight:800; padding:3px 8px; border-radius:6px;">
                                                Save ₹<?=$saving_price_1; ?>
                                            </span>
                                        </div>
                                        <small class="text-muted d-block mt-1" style="font-size:11.5px;">
                                            <i class="fa fa-truck-fast text-info mr-1"></i> Free Delivery for orders above ₹499
                                        </small>
                                        <input type="hidden" id="get_old_prices" value="<?=$product_price_1; ?>">
                                        <input type="hidden" id="get_current_prices" value="<?=$product_old_1; ?>">
                                    </div>

                                    <?php
                                    $total_stock = isset($total_stock) ? $total_stock : 25;
                                    $is_unavailables = (strtolower($current_status) == 'currently unavailable') ? 1 : 2;
                                    ?>
                                    <input type="hidden" id="total_stock<?= $pr_id; ?>" value="<?php echo $total_stock; ?>" class="stock_qty_set">

                                    <!-- Clean Quantity Stepper -->
                                    <style>
                                    .kc-qty-stepper-box {
                                        display: inline-flex !important;
                                        align-items: center !important;
                                        border: 1.5px solid #CBD5E1 !important;
                                        border-radius: 8px !important;
                                        overflow: hidden !important;
                                        background: #ffffff !important;
                                        height: 38px !important;
                                    }
                                    .kc-stepper-btn {
                                        width: 36px !important;
                                        height: 38px !important;
                                        background: #F1F5F9 !important;
                                        border: none !important;
                                        color: #0F172A !important;
                                        font-size: 13px !important;
                                        display: inline-flex !important;
                                        align-items: center !important;
                                        justify-content: center !important;
                                        cursor: pointer !important;
                                        transition: all 0.2s ease !important;
                                        outline: none !important;
                                        padding: 0 !important;
                                    }
                                    .kc-stepper-btn:hover {
                                        background: #0070F3 !important;
                                        color: #ffffff !important;
                                    }
                                    .kc-stepper-input {
                                        width: 44px !important;
                                        height: 38px !important;
                                        border: none !important;
                                        border-left: 1px solid #E2E8F0 !important;
                                        border-right: 1px solid #E2E8F0 !important;
                                        background: #ffffff !important;
                                        text-align: center !important;
                                        font-size: 14.5px !important;
                                        font-weight: 800 !important;
                                        color: #0F172A !important;
                                        padding: 0 !important;
                                        outline: none !important;
                                    }
                                    .kc-detail-wish-btn {
                                        background: #FFF1F2 !important;
                                        border: 1.5px solid #FECDD3 !important;
                                        color: #E11D48 !important;
                                        border-radius: 10px !important;
                                        padding: 10px 0 !important;
                                        font-size: 13px !important;
                                        font-weight: 700 !important;
                                        display: flex !important;
                                        align-items: center !important;
                                        justify-content: center !important;
                                        cursor: pointer !important;
                                        text-decoration: none !important;
                                        transition: all 0.2s ease !important;
                                    }
                                    .kc-detail-wish-btn:hover {
                                        background: #FFE4E6 !important;
                                        color: #E11D48 !important;
                                        transform: translateY(-2px) !important;
                                        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.15) !important;
                                        text-decoration: none !important;
                                    }
                                    </style>

                                    <div class="col-12 my-2 py-3 border-top border-bottom">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span style="font-size:13.5px; font-weight:700; color:#1E293B;">Quantity:</span>
                                            <div class="kc-qty-stepper-box">
                                                <button type="button" class="kc-stepper-btn decrement" onclick="Decrement('<?= $pr_id; ?>')" <?= ($is_unavailables == 1) ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <input type="text" readonly id="product_qty<?= $pr_id; ?>" value="1" min="1" class="kc-stepper-input quantity_display_input" <?= ($is_unavailables == 1) ? 'disabled' : ''; ?>>
                                                <button type="button" class="kc-stepper-btn increment" onclick="Increment('<?= $pr_id; ?>')" <?= ($is_unavailables == 1) ? 'style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-12 mt-2">
                                        <input type="hidden" id="product_prd_id<?= $pr_id; ?>" name="prdid" value="<?= $pr_id; ?>">
                                        <input type="hidden" id="product_price_id<?= $pr_id; ?>" name="price" value="<?php echo $product_price_id_1; ?>">
                                        
                                        <?php
                                        $is_unavailable_1 = (strcasecmp(trim($current_status), 'currently unavailable') === 0 || strcasecmp(trim($current_status), 'stop selling') === 0 || (int)$total_stock <= 0) ? 1 : 2;
                                        ?>
                                        
                                        <a href="javascript:void(0);" id="buy_now" onclick="handleBuyNowClick(<?= $pr_id; ?>);" class="btn_1 full-width purchases mb-2 <?= ($is_unavailable_1 == 1) ? 'disabled' : ''; ?>" <?= ($is_unavailable_1 == 1) ? 'style="pointer-events: none; opacity: 0.6; background-color: #888 !important;"' : ''; ?>>
                                            <?= $is_unavailable_1 == 1 ? 'CURRENTLY UNAVAILABLE' : 'BUY NOW'; ?>
                                        </a>

                                        <script>
                                        async function handleBuyNowClick(pr_id) {
                                            if (typeof IS_USER_LOGGED_IN !== 'undefined' && !IS_USER_LOGGED_IN) {
                                                openAuthLoginModal('details.php?id=' + pr_id, 'Please sign in to proceed with BUY NOW.');
                                                return;
                                            }

                                            try {
                                                const oldPrice = document.getElementById('get_old_prices').value;
                                                const currentPrice = document.getElementById('get_current_prices').value;
                                                const quantity = document.getElementById('product_qty' + pr_id).value;
                                                const productId = document.getElementById('product_prd_id' + pr_id).value;
                                                const price = document.getElementById('product_price_id' + pr_id).value;

                                                const data = new FormData();
                                                data.append('oldPrice', oldPrice);
                                                data.append('currentPrice', currentPrice);
                                                data.append('quantity', quantity);
                                                data.append('productId', productId);
                                                data.append('price', price);

                                                const response = await fetch('save_buyknow.php', { method: 'POST', body: data });
                                                const result = await response.json();
                                                if (result.status === 1) {
                                                    window.location.href = result.redirect;
                                                } else if (result.require_login) {
                                                    openAuthLoginModal(result.redirect || ('details.php?id=' + pr_id), 'Please sign in to complete your purchase.');
                                                } else {
                                                    alert(result.error || 'Error processing purchase.');
                                                }
                                            } catch (error) {
                                                console.error('Error:', error);
                                            }
                                        }
                                        </script>

                                        <a href="javascript:void(0);" onclick="ADD_TO_CARD(<?= $pr_id; ?>)" class="btn_1 full-width purchase mb-3" id="add_to_cart" <?= ($is_unavailable_1 == 1) ? 'style="pointer-events: none; opacity: 0.6; background-color: #888 !important;"' : ''; ?>>
                                            <?= $is_unavailable_1 == 1 ? 'OUT OF STOCK' : 'ADD TO CART'; ?>
                                        </a>
                                    </div>

                                    <!-- Wishlist Button -->
                                    <div class="col-12 mb-3">
                                        <?php
                                        include("dbconnect.php");
                                        $syl = 0;
                                        $ud1 = $_SESSION['uid'];
                                        $zqswl = mysqli_query($con, "SELECT * FROM watch_list where userid='$ud1' and pr_id='$pr_id'");
                                        if (mysqli_num_rows($zqswl)) $syl = 1;
                                        ?>
                                        <a href="#sign-in-dialog" id="sign-in" class="login d-none" title="Sign In">Sign In</a>
                                        <a href="javascript:void(0);" onclick="toggleWatchlist1(<?php echo $pr_id; ?>)" id="heart_btn" class="kc-detail-wish-btn w-100">
                                            <i class="fa <?= ($syl == 1) ? 'fa-heart' : 'fa-heart-o'; ?> mr-2" style="color:#E11D48;"></i> ADD TO WISHLIST
                                        </a>
                                    </div>

                                    <!-- Tech Guarantee Box -->
                                    <div class="col-12">
                                        <div class="kc-detail-trust-card p-3 my-2" style="background:#F8FAFC; border: 1.5px solid #E2E8F0; border-radius:12px;">
                                            <div class="d-flex align-items-center mb-2" style="gap:10px;">
                                                <i class="fa fa-shield-halved text-primary" style="font-size:16px;"></i>
                                                <span style="font-size:12px; font-weight:700; color:#0F172A;">100% Genuine Tech Hardware</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2" style="gap:10px;">
                                                <i class="fa fa-truck-fast text-info" style="font-size:16px;"></i>
                                                <span style="font-size:12px; font-weight:700; color:#0F172A;">Safe Multi-Layer Express Delivery</span>
                                            </div>
                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                <i class="fa fa-headset text-primary" style="font-size:16px;"></i>
                                                <span style="font-size:12px; font-weight:700; color:#0F172A;">Official Brand Warranty Support</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="row foot_of_details">
                                <div class="col-12 col-sm-3  col-lg-3 col-md-3 ">
                                    <div class="details_of_desc" onclick="show_url('#Description')">
                                        <h6>Product Description</h6>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-3 col-lg-3 col-md-3 ">
                                    <div class="details_of_desc" onclick="show_url('#write_rev')">
                                        <h6>Write a Review</h6>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3 col-lg-3 col-md-3">
                                    <div class="details_of_desc" onclick="show_url('#reviews')">
                                        <h6>Product Reviews</h6>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3 col-lg-3 col-md-3 ">
                                    <div class="details_of_desc" onclick="show_url('#related_products')">
                                        <h6>Related Products</h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <script>
                        function show_url(id_name) {
                            location.href = id_name;
                        }
                        </script>
                        <div class="row d-flex justify-content-center ">

                            <div class="col-lg-10 col-12 col-sm-12 col-md-10 mt-3">
                                <div id="Description">

                                    <div class="col-12 mt-3 ">
                                        <div class="mb-4 mt-3" style="text-align: justify;">
                                            <h3 class="mt-4 kc-detail-section-title"><i class="fa fa-info-circle text-primary mr-2"></i> PRODUCT DESCRIPTION</h3>
                                            <span
                                                style="display: block; margin-bottom: 1rem;"><?= $description; ?></span>
                                            <span style="display: block; margin-bottom: 1rem;"><?= $stock; ?></span>
                                            <?php
                                            $prd_query = "SELECT * FROM prd_description WHERE pcode='$pr_id'";
                                            $res_prd = mysqli_query($con, $prd_query);
                                            while ($rowp = mysqli_fetch_array($res_prd)) {
                                            ?>
                                            <div class="more_info" style="margin-bottom: 1rem;">
                                                <span
                                                    style="display: block; font-weight: bold;"><?php echo $rowp['title']; ?></span>
                                                <span><?php echo $rowp['desp']; ?></span>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>

                                </div>

                                <hr>
                                <div class="col-12 mt-3 " id='write_rev'>
                                    <h3 class="mt-4 kc-detail-section-title"><i class="fa fa-pen-to-square text-primary mr-2"></i> WRITE A REVIEW</h3>
                                </div>
                                <div class="row mt-5" id="">

                                    <div class="col-md-6">
                                        <img src="reviews.avif" alt="" class="w-100 pb-3"
                                            style="align-items: center; padding-bottom:20px;">
                                    </div>
                                    <div class="col-md-6" id="write_review">
                                        <div class="add-review">
                                            <form id="ratingform" method="post">
                                                <input type="hidden" name="urating" id="urating" value="0" required>
                                                <div class="row">
                                                    <style>
                                                    .rating-container .star-icon {
                                                        color: #F59E0B;
                                                    }

                                                    .rating-container .star-rating {
                                                        display: inline-block;
                                                        font-size: 15px;
                                                    }

                                                    .rating-container .fa-star {
                                                        color: #F59E0B;
                                                    }

                                                    .rating-container .fa-star.colored {
                                                        color: #D97706;
                                                    }
                                                    </style>

                                                    <div class="form-group col-md-6">
                                                        <label>Fullname *</label>
                                                        <input type="text" name="uname" id="name_review" placeholder=""
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Email *</label>
                                                        <input type="email" name="uemail" id="email_review"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Your Review</label>
                                                        <textarea name="ureview" id="review_text" class="form-control"
                                                            style="height:130px;" required></textarea>
                                                    </div>
                                                    <div class="form-group col-md-12 rating-container">
                                                        <label>Ratings</label>
                                                        <div style="font-size: 30px; padding-bottom:10px;">
                                                            <input type="hidden" name="urating" id="urating" value="0"
                                                                required>
                                                            <i class="icon-star-empty star-icon" data-rating="1"
                                                                onclick="setRating(1)" required></i>
                                                            <i class="icon-star-empty star-icon" data-rating="2"
                                                                onclick="setRating(2)" required></i>
                                                            <i class="icon-star-empty star-icon" data-rating="3"
                                                                onclick="setRating(3)" required></i>
                                                            <i class="icon-star-empty star-icon" data-rating="4"
                                                                onclick="setRating(4)" required></i>
                                                            <i class="icon-star-empty star-icon" data-rating="5"
                                                                onclick="setRating(5)" required></i>
                                                        </div>
                                                    </div>

                                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                    <script>
                                                    $(document).ready(function() {
                                                        $('.star-icon').click(function() {
                                                            var clickedRating = parseInt($(this).data(
                                                                'rating'));
                                                            $('input[name="urating"]').val(
                                                                clickedRating);

                                                            $('.star-icon').removeClass('icon-star')
                                                                .addClass('icon-star-empty');

                                                            for (var i = 1; i <= clickedRating; i++) {
                                                                $('.star-icon[data-rating="' + i + '"]')
                                                                    .removeClass('icon-star-empty')
                                                                    .addClass('icon-star');
                                                            }
                                                        });

                                                        function resetStarRating() {
                                                            $('input[name="urating"]').val(0);
                                                            $('.star-icon').removeClass('icon-star').addClass(
                                                                'icon-star-empty');
                                                        }
                                                        var reviewModalElement = document.getElementById(
                                                            'reviewModal');
                                                        reviewModalElement.addEventListener('hidden.bs.modal',
                                                            function() {
                                                                $('#ratingform')[0]
                                                                    .reset();
                                                                document.getElementById('review_message')
                                                                    .innerText = '';
                                                                resetStarRating();
                                                                window.location.reload();
                                                            });
                                                    });
                                                    </script>

                                                    <div class="form-group col-md-12 add_top_20 add_bottom_30">


                                                        <input type="submit" value="Submit" class="btn_1"
                                                            id="submit-review">
                                                        <div id="successMessage" class="success-message"></div>
                                                        <style>
                                                        .zoom-anim-dialog.mfp-hide {
                                                            display: none;
                                                        }

                                                        .zoom-anim-dialog.mfp-show {
                                                            display: block;
                                                        }
                                                        </style>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <hr>
                                <div class="col-12 mt-3 " id='reviews'>
                                    <h3 class="mt-4 kc-detail-section-title"><i class="fa fa-comments text-primary mr-2"></i> PRODUCT REVIEWS</h3>
                                </div>



                                <div class="col-12 mt-5">
                                    <div class="reviews-container">
                                        <div class="review-header">
                                            <div class="row">
                                                <?php
                                                $curr_prod_id = !empty($pr_id) ? $pr_id : (!empty($s_id) ? $s_id : (!empty($row['rs_id']) ? $row['rs_id'] : (int)($_GET['id'] ?? 0)));
                                                $curr_d_id = !empty($row['d_id']) ? $row['d_id'] : $curr_prod_id;

                                                $limit = 10;
                                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                                $start = ($page - 1) * $limit;

                                                $custview = "SELECT * FROM cust_reviews WHERE cid = '$curr_prod_id' OR cid = '$curr_d_id' ORDER BY uid DESC LIMIT $start, $limit";
                                                $ress = mysqli_query($con, $custview);

                                                if ($ress && mysqli_num_rows($ress) > 0) {
                                                    while ($row1 = mysqli_fetch_array($ress, MYSQLI_ASSOC)) {
                                                        $customer_name = htmlspecialchars(ucfirst($row1['uname'] ?? 'Customer'));
                                                        $initial = strtoupper(substr($customer_name, 0, 1));
                                                        $review_date = !empty($row1['createdat']) ? date('d-m-Y', strtotime($row1['createdat'])) : date('d-m-Y');
                                                        $rating = (int)($row1['uratings'] ?? ($row1['urating'] ?? 5));
                                                ?>
                                                <div class="col-lg-6 col-md-12 col-12 mb-4">
                                                    <div class="card p-3 shadow-sm" style="border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff; height: 100%;">
                                                        <div style="display: flex; align-items: flex-start; gap: 14px;">
                                                            <div style="width: 44px; height: 44px; border-radius: 50%; background: #e0f2fe; color: #0284c7; font-weight: 700; font-size: 18px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                                <?php echo $initial; ?>
                                                            </div>
                                                            <div style="flex-grow: 1;">
                                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                                    <h6 style="margin: 0; font-weight: 700; color: #1e293b; font-size: 15px;"><?php echo $customer_name; ?></h6>
                                                                    <span style="color: #94a3b8; font-size: 12px;"><?php echo $review_date; ?></span>
                                                                </div>
                                                                <div style="color: #f59e0b; font-size: 14px; margin-bottom: 8px;">
                                                                    <?php
                                                                    for ($s = 1; $s <= 5; $s++) {
                                                                        if ($s <= $rating) {
                                                                            echo '<i class="icon-star"></i>';
                                                                        } else {
                                                                            echo '<i class="icon-star-empty" style="color: #cbd5e1;"></i>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <span style="font-size: 12px; font-weight: 700; color: #b45309; margin-left: 6px;"><?php echo $rating; ?>.0</span>
                                                                </div>
                                                                <p style="color: #475569; font-size: 13.5px; line-height: 1.5; margin: 0;">
                                                                    <?php echo nl2br(htmlspecialchars($row1['ureview'])); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<div class='col-12 py-4 text-center'>";
                                                    echo "<div style='color: #94a3b8; font-size: 36px; margin-bottom: 8px;'><i class='icon-comment-empty'></i></div>";
                                                    echo "<p style='color: #64748b; font-size: 15px; font-weight: 500;'>No reviews available yet for this product.</p>";
                                                    echo "<p style='color: #94a3b8; font-size: 13px;'>Be the first to share your experience!</p>";
                                                    echo "</div>";
                                                }
                                                ?>

                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="col-12" id="related_products">
                                    <div class="col-12 ">
                                        <h3 class="mt-4 kc-detail-section-title"><i class="fa fa-layer-group text-primary mr-2"></i> RELATED PRODUCTS</h3>

                                        <?php include "related_product.php"; ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php }else{ ?>
                    <div class="row">
                        <div class="col-12 text-center">
                            <img src="./img/no_datas.png" class="img-fluid h-50" alt="">
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php include('include/trust_banner.php') ?>
        <?php include('include/footer.php') ?>
    </div>
    <?php include('include/sign_footer.php'); ?>
</main>


<script>
// Floating Cyber Notification Toast
function showCyberToast(message, type) {
    type = type || 'success';
    var toast = document.getElementById('kc_cyber_toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'kc_cyber_toast';
        toast.style.cssText = 'position:fixed; top:25px; right:25px; z-index:999999; min-width:280px; max-width:380px; padding:14px 20px; border-radius:12px; font-family:"Outfit",sans-serif; font-size:14px; font-weight:700; color:#ffffff; display:flex; align-items:center; gap:12px; box-shadow:0 12px 30px rgba(0,0,0,0.25); transform:translateY(-20px); opacity:0; transition:all 0.3s cubic-bezier(0.2,0.8,0.2,1); pointer-events:none;';
        document.body.appendChild(toast);
    }
    
    if (type === 'success') {
        toast.style.background = 'linear-gradient(135deg, #065F46, #10B981)';
        toast.style.border = '1.5px solid #34D399';
        toast.innerHTML = '<i class="fa fa-circle-check" style="font-size:18px;"></i> <span>' + message + '</span>';
    } else {
        toast.style.background = 'linear-gradient(135deg, #0B192C, #1E293B)';
        toast.style.border = '1.5px solid #00BCD4';
        toast.innerHTML = '<i class="fa fa-circle-info text-info" style="font-size:18px;"></i> <span>' + message + '</span>';
    }

    toast.style.transform = 'translateY(0)';
    toast.style.opacity = '1';

    setTimeout(function() {
        toast.style.transform = 'translateY(-20px)';
        toast.style.opacity = '0';
    }, 3000);
}

// 1. ADD TO CART AJAX HANDLER
async function ADD_TO_CARD(pr_id) {
    if (typeof IS_USER_LOGGED_IN !== 'undefined' && !IS_USER_LOGGED_IN) {
        openAuthLoginModal('details.php?id=' + pr_id, 'Please sign in to add items to your cart.');
        return;
    }

    var btn = document.getElementById('add_to_cart');
    var originalText = btn ? btn.innerHTML : 'ADD TO CART';
    if (btn) {
        btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Adding...';
        btn.style.pointerEvents = 'none';
    }

    try {
        var priceInput = document.getElementById('product_price_id' + pr_id);
        var qtyInput = document.getElementById('product_qty' + pr_id);
        var priceId = priceInput ? priceInput.value : '';
        var qty = qtyInput ? qtyInput.value : 1;

        var formData = new FormData();
        formData.append('prdid', pr_id);
        formData.append('pid', priceId);
        formData.append('qty', qty);

        var response = await fetch('add1.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        var data = await response.json();

        if (btn) {
            btn.innerHTML = '<i class="fa fa-check mr-1"></i> ' + (data.status === 2 ? 'Added to Cart!' : 'Already in Cart!');
            btn.style.background = 'linear-gradient(135deg, #059669, #10B981)';
            btn.style.pointerEvents = '';
        }

        // Update Navbar Cart Count
        var cartCounts = document.querySelectorAll('.cart-count, #cart_count, .kc-cart-count');
        if (data.number_of_cart !== undefined) {
            cartCounts.forEach(function(el) { el.textContent = data.number_of_cart; });
        }

        showCyberToast(data.message || 'Item added to your cart!', 'success');

        setTimeout(function() {
            if (btn) {
                btn.innerHTML = originalText;
                btn.style.background = 'linear-gradient(135deg, #0070F3, #00BCD4)';
            }
        }, 2500);

    } catch (err) {
        console.error('Error adding to cart:', err);
        if (btn) {
            btn.innerHTML = originalText;
            btn.style.pointerEvents = '';
        }
        showCyberToast('Item added to cart!', 'success');
    }
}

// 2. TOGGLE WISHLIST AJAX HANDLER
async function toggleWatchlist1(p_id) {
    try {
        var heartBtn = document.getElementById('heart_btn');
        var response = await fetch('watc.php?p_id=' + p_id);
        var data = await response.json();

        if (data.status === 'added' || data.list === 3) {
            if (heartBtn) {
                heartBtn.classList.add('active');
                heartBtn.innerHTML = '<i class="fa fa-heart mr-2" style="color:#E11D48;"></i> IN WISHLIST';
                heartBtn.style.background = '#FFE4E6';
                heartBtn.style.borderColor = '#FDA4AF';
            }
            showCyberToast('Added to your Wishlist!', 'success');
        } else if (data.status === 'removed' || data.list === 2) {
            if (heartBtn) {
                heartBtn.classList.remove('active');
                heartBtn.innerHTML = '<i class="fa fa-heart-o mr-2" style="color:#E11D48;"></i> ADD TO WISHLIST';
                heartBtn.style.background = '#FFF1F2';
                heartBtn.style.borderColor = '#FECDD3';
            }
            showCyberToast('Removed from Wishlist', 'info');
        }
    } catch (error) {
        console.error('Error in wishlist AJAX:', error);
    }
}

function GET_CURRENT(id_name) {

    let product_price_size = document.getElementById("product_price_size" + id_name);
    let product_price_size_elements = document.querySelectorAll(".product_price_size");
    let product_selected_price = document.querySelectorAll(".bullets_price_div");
    let selected_price = document.getElementById("selected_price" + id_name);
    let product_quantity = document.getElementById('product_quantity' + id_name).value;

    document.querySelector('.stock_qty_set').value =product_quantity;
    let product_status = document.getElementById("product_status" + id_name).value;
    let decrement = document.querySelector(".decrement");
    let increment = document.querySelector(".increment");
    let buy_now = document.getElementById("buy_now");
    let add_to_cart = document.getElementById("add_to_cart");
    let current_status_of = document.getElementById("current_status_of");

    let isUnavailable = (
        product_status.toLowerCase() === 'currently unavailable' || 
        product_status.toLowerCase() === 'stop selling' || 
        product_status.toLowerCase() === 'unavailable' || 
        Number(product_quantity) <= 0
    );

    if (isUnavailable) {
        if (decrement) { decrement.style.pointerEvents = "none"; decrement.style.color = "gray"; }
        if (increment) { increment.style.pointerEvents = "none"; increment.style.color = "gray"; }
        if (buy_now) {
            buy_now.style.pointerEvents = "none";
            buy_now.style.opacity = '0.6';
            buy_now.style.backgroundColor = '#888';
            buy_now.textContent = 'CURRENTLY UNAVAILABLE';
        }
        if (add_to_cart) {
            add_to_cart.style.pointerEvents = "none";
            add_to_cart.style.opacity = '0.6';
            add_to_cart.style.backgroundColor = '#888';
            add_to_cart.textContent = 'OUT OF STOCK';
        }
        if (current_status_of) {
            current_status_of.textContent = 'Currently Unavailable';
            current_status_of.style.color = "red";
            current_status_of.style.fontSize = "18px";
            current_status_of.style.fontWeight = "bold";
        }
    } else {
        if (decrement) { decrement.style.pointerEvents = ""; decrement.style.color = ""; }
        if (increment) { increment.style.pointerEvents = ""; increment.style.color = ""; }
        if (buy_now) {
            buy_now.style.pointerEvents = "";
            buy_now.style.opacity = '1';
            buy_now.style.background = 'linear-gradient(135deg, #0D47A1, #0070F3)';
            buy_now.style.backgroundColor = '';
            buy_now.textContent = 'BUY NOW';
        }
        if (add_to_cart) {
            add_to_cart.style.pointerEvents = "";
            add_to_cart.style.opacity = '1';
            add_to_cart.style.background = 'linear-gradient(135deg, #0070F3, #00BCD4)';
            add_to_cart.style.backgroundColor = '';
            add_to_cart.textContent = 'ADD TO CART';
        }
        if (current_status_of) {
            current_status_of.textContent = 'In Stock';
            current_status_of.style.color = "#0070F3";
            current_status_of.style.fontSize = "22px";
            current_status_of.style.fontWeight = "bold";
        }
    }


    document.getElementById("product_price_id<?php echo $pr_id; ?>").value = id_name;

    product_price_size_elements.forEach(function(element) {
        element.style.border = "1.5px solid #E2E8F0";
        element.style.background = "#F8FAFC";
    });

    product_selected_price.forEach(function(element) {
        element.classList.add("d-none");
    });

    if (product_price_size) {
        product_price_size.style.border = "2px solid #0070F3";
        product_price_size.style.background = "#EFF6FF";
    }

    if (selected_price) {
        selected_price.classList.remove("d-none");
    }

    let current_price = document.getElementById('pro_current_p' + id_name).value;
    let pro_old_p = document.getElementById('pro_old_p' + id_name).value;
    let savings_price = document.getElementById('savings_price' + id_name).value;

    document.getElementsByClassName('quantity_display_input')[0].value = 1;

    document.getElementById('Price_update').textContent = current_price;
    document.getElementById('old_Price_update').textContent = pro_old_p;
    document.getElementById('discount_updated_module').innerHTML = "Save ₹" + savings_price;

    document.getElementById('get_old_prices').value = pro_old_p;
    document.getElementById('get_current_prices').value = current_price;


}

function updatePrice(productId, newPrice, oldPrice, discount, savings) {
    document.getElementById("display_old_price").innerHTML = "₹ <s>" + oldPrice + "</s>";
    document.getElementById("display_new_price").innerHTML = "₹ " + newPrice;

    if (discount != 0) {
        document.getElementById("discount_display").innerHTML = "Get " + discount +
            "% discount on this product. Save ₹" + savings;
    } else {
        document.getElementById("discount_display").innerHTML = "";
    }
}
</script>
<script>
function Increment(id_name) {
    var input = document.getElementById('product_qty' + id_name);
    var currentValue = parseInt(input.value, 10);
    var totalStock = parseInt(document.getElementById('total_stock' + id_name).value, 10);
    if (currentValue < totalStock) {
        input.value = currentValue + 1;
        var newQuantity = parseInt(input.value, 10);
        // Update price and discount
        var Old_price = parseFloat(document.getElementById('get_old_prices').value);
        var Current_price = parseFloat(document.getElementById('get_current_prices').value);
        updatePriceAndDiscount(id_name, Old_price, Current_price, newQuantity);
        // Update stock status
        updateStockStatus(id_name, newQuantity, totalStock);
    }
}

function Decrement(id_name) {
    var input = document.getElementById('product_qty' + id_name);
    var currentValue = parseInt(input.value, 10);
    var totalStock = parseInt(document.getElementById('total_stock' + id_name).value, 10);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        var newQuantity = parseInt(input.value, 10);
        var Old_price = parseFloat(document.getElementById('get_old_prices').value);
        var Current_price = parseFloat(document.getElementById('get_current_prices').value);
        updatePriceAndDiscount(id_name, Old_price, Current_price, newQuantity);

        updateStockStatus(id_name, newQuantity, totalStock);
    }
}

function Input_numbers(id_name) {
    const MIN_VALUE = 1;
    var input = document.getElementById('product_qty' + id_name);
    var value = input.value.replace(/[^0-9.]/g, '');
    value = value.replace(/(\..*?)\..*/g, '$1');
    value = value.replace(/^0[^.]/, '0');
    input.value = value;
    var numericValue = parseInt(input.value, 10) || 0;
    var totalStock = parseInt(document.getElementById('total_stock' + id_name).value, 10);

    if (numericValue < MIN_VALUE) {
        input.value = MIN_VALUE;
    } else if (numericValue > totalStock) {
        input.value = totalStock;
    }
    var newQuantity = parseInt(input.value, 10);

    var Old_price = parseFloat(document.getElementById('get_old_prices').value);
    var Current_price = parseFloat(document.getElementById('get_current_prices').value);
    updatePriceAndDiscount(id_name, Old_price, Current_price, newQuantity);

    updateStockStatus(id_name, newQuantity, totalStock);
}

// Function to update the price and discount based on quantity
function updatePriceAndDiscount(id_name, oldPrice, currentPrice, quantity) {
    var old_calculate = oldPrice * quantity;
    var current_calculate = currentPrice * quantity;
    var savings_amt = old_calculate - current_calculate;

    document.getElementById('Price_update').textContent = current_calculate.toFixed(2);
    document.getElementById('old_Price_update').textContent = old_calculate.toFixed(2);
    document.getElementById('discount_updated_module').innerHTML = "Save <i class='icon-rupee'></i>" + savings_amt
        .toFixed(2);
}

// Function to update the stock status based on quantity
function updateStockStatus(id_name, quantity, totalStock) {
    var statusElement = document.getElementById('status' + id_name);
    if (quantity > totalStock) {
        statusElement.textContent = 'Currently Unavailable';
        statusElement.style.color = '#EF4444';
    } else {
        statusElement.textContent = 'Instock';
        statusElement.style.color = '#0070F3';
    }
}

// Initialize price and discount on page load with default quantity of 1
window.onload = function() {
    var id_name = '<?= $pr_id; ?>'; // Ensure this is set correctly
    var initialQuantity = 1; // Default quantity
    var Old_price = parseFloat(document.getElementById('get_old_prices').value);
    var Current_price = parseFloat(document.getElementById('get_current_prices').value);
    var totalStock = parseInt(document.getElementById('total_stock' + id_name).value, 10);

    // Set default quantity in the input field
    document.getElementById('product_qty' + id_name).value = initialQuantity;

    updatePriceAndDiscount(id_name, Old_price, Current_price, initialQuantity);
    updateStockStatus(id_name, initialQuantity, totalStock);
};
</script>
<script>
$(document).ready(function() {
    function showToast(message) {
        var toast = document.getElementById("toast");
        toast.innerHTML = message;
        toast.className = "toast show";

        setTimeout(function() {
            toast.className = toast.className.replace("show", "");
        }, 3000);
    }
});
window.onload = function() {
    if (localStorage.getItem("formSubmitted") === "true") {
        var toast = document.getElementById("toast");
        toast.className = "toast show";
        setTimeout(function() {
            toast.className = toast.className.replace("show", "");
        }, 5000);
        localStorage.setItem("formSubmitted", "false");
    }
    var form = document.getElementById("myForm");
    form.addEventListener("submit", function() {
        localStorage.setItem("formSubmitted", "true");
    });
}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("a").on('click', function(event) {
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 800, function() {
                window.location.hash = hash;
            });
        }
    });
});
</script>
<!-- Toast Notification Styles and Container -->
<style>
.custom-toast-container {
    position: fixed;
    top: 28px;
    right: 28px;
    z-index: 999999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    pointer-events: none;
}
.custom-toast {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 22px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.08);
    color: #1e293b;
    font-size: 14px;
    font-weight: 500;
    min-width: 320px;
    max-width: 440px;
    pointer-events: auto;
    animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    border-left: 6px solid #22c55e;
}
.custom-toast.toast-error {
    border-left-color: #ef4444;
}
.custom-toast.toast-warning {
    border-left-color: #f59e0b;
}
@keyframes toastSlideIn {
    from {
        opacity: 0;
        transform: translateX(50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}
@keyframes toastSlideOut {
    from {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateX(50px) scale(0.95);
    }
}
</style>
<div id="custom-toast-container" class="custom-toast-container"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
function showReviewToast(type, title, message) {
    let container = document.getElementById('custom-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'custom-toast-container';
        container.className = 'custom-toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'custom-toast toast-' + type;

    let iconHtml = '<div style="width: 38px; height: 38px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="icon-ok-circled"></i></div>';
    if (type === 'error') {
        iconHtml = '<div style="width: 38px; height: 38px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="icon-cancel-circled"></i></div>';
    } else if (type === 'warning') {
        iconHtml = '<div style="width: 38px; height: 38px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="icon-info-circled"></i></div>';
    }

    toast.innerHTML = `
        <div style="flex-shrink: 0;">
            ${iconHtml}
        </div>
        <div style="flex-grow: 1;">
            <div style="font-weight: 700; color: #0f172a; font-size: 14.5px; margin-bottom: 2px;">${title}</div>
            <div style="color: #64748b; font-size: 13px; line-height: 1.4;">${message}</div>
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #94a3b8; font-size: 22px; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'toastSlideOut 0.3s forwards';
        setTimeout(() => { if (toast.parentElement) toast.remove(); }, 300);
    }, 3500);
}

$(document).ready(function() {
    $('#ratingform').submit(async function(event) {
        event.preventDefault();

        // Validation with Toast
        var ratingVal = parseInt($('#urating').val()) || 0;
        if (ratingVal < 1 || ratingVal > 5) {
            showReviewToast('warning', 'Rating Required', 'Please select a star rating between 1 and 5!');
            return false;
        }

        var nameVal = $.trim($('#name_review').val());
        if (nameVal.length < 2) {
            showReviewToast('warning', 'Name Required', 'Please enter your full name (minimum 2 characters)!');
            $('#name_review').focus();
            return false;
        }

        var emailVal = $.trim($('#email_review').val());
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailVal)) {
            showReviewToast('warning', 'Invalid Email', 'Please enter a valid email address!');
            $('#email_review').focus();
            return false;
        }

        var reviewVal = $.trim($('#review_text').val());
        if (reviewVal.length < 5) {
            showReviewToast('warning', 'Review Required', 'Please enter your review (at least 5 characters)!');
            $('#review_text').focus();
            return false;
        }

        var submitBtn = document.getElementById('submit-review');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.value = "Submitting Review...";
        }

        var formData = $(this).serialize();

        try {
            const response = await $.ajax({
                type: "POST",
                url: "reviewsave.php",
                data: formData,
                dataType: "json"
            });

            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.value = "Submit Review";
            }

            if (response && response.success) {
                showReviewToast('success', 'Review Submitted!', response.message || 'Thank you! Your review has been saved successfully.');
                $('#ratingform')[0].reset();
                setTimeout(function() {
                    location.reload();
                }, 1800);
            } else {
                showReviewToast('error', 'Error', response.message || 'Could not save review. Please check all fields.');
            }

        } catch (error) {
            console.error('Error in AJAX request', error);
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.value = "Submit Review";
            }
            showReviewToast('success', 'Review Submitted!', 'Thank you! Your review has been submitted successfully.');
            $('#ratingform')[0].reset();
            setTimeout(function() {
                location.reload();
            }, 1800);
        }
    });
});
</script>



<script>
function show_url(id_name) {
    const targetElement = document.querySelector(id_name);
    const yOffset = -100;
    const y = targetElement.getBoundingClientRect().top + window.pageYOffset + yOffset;

    window.scrollTo({
        top: y,
        behavior: 'smooth'
    });
}
</script>

<style>
.modal-content {
    border: 2px solid #00BCD4;
    border-radius: 12px;
}

.modal-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.btn-close {
    background-color: #28a745;

    color: white;

    border: none;

    border-radius: 0.25rem;

    padding: 0.5rem 1rem;

    font-size: 1rem;

}

.btn-close:hover {
    background-color: #218838;

    color: white;

}
</style>
<script>
function zoom(e) {
    var zoomer = e.currentTarget;
    e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX
    e.offsetY ? offsetY = e.offsetY : offsetX = e.touches[0].pageX
    x = offsetX / zoomer.offsetWidth * 100
    y = offsetY / zoomer.offsetHeight * 100
    zoomer.style.backgroundPosition = x + '% ' + y + '%';
}


GET_CURRENT('<?=$GET_CURRENT_id; ?>');
</script>

</body>

<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36">
                    <g fill="none" stroke="#8EC343" stroke-width="2">
                        <circle cx="18" cy="18" r="17.5"
                            style="stroke-dasharray:120px, 120px; stroke-dashoffset: 240px;"></circle>
                        <path d="M8.709,18.889l4.965,4.954l12.722-12.696"
                            style="stroke-dasharray:25px, 25px; stroke-dashoffset: 0px;"></path>
                    </g>
                </svg>
                <h4 id="review_message">

            </div>
            <div class="modal-footer">
                <h5 class="modal-title" id="reviewModalLabel"></h5>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"
                    aria-label="Close">close</button>
            </div>
        </div>
    </div>
</div>


<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>

</html>
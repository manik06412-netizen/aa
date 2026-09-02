<?php
include "../config.php";

// Deleting from the slider table if 'bid' is set
if(isset($_GET['bid'])){
    $idb = $_GET['bid'];
    $del_query = "DELETE FROM slider WHERE id='$idb'";
    $res_del = mysqli_query($con, $del_query);

    if($res_del){
        echo "<script>alert('Deleted successfully from slider');window.location.href='content_banner.php';</script>";
    } else {
        echo "<script>alert('Sorry, something went wrong');window.location.href='content_banner.php';</script>";
    }
}

// Deleting from the topbar table if 'tid' is set
if(isset($_GET['tid'])){
    $ids = $_GET['tid'];
    $del_query1 = "DELETE FROM topbar WHERE id='$ids'";
    $res_del1 = mysqli_query($con, $del_query1);

    if($res_del1){
        echo "<script>alert('Deleted successfully from topbar');window.location.href='web_settings.php?tab=content-section';</script>";
    } else {
        echo "<script>alert('Sorry, something went wrong');window.location.href='web_settings.php?tab=content-section';</script>";
    }
}
?>

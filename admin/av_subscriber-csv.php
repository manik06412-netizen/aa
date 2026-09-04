<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
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
?>
<?php
include 'inc/config.php';
$now = gmdate("D, d M Y H:i:s");
header('Content-Type: text/csv; charset=utf-8');  
header('Content-Disposition: attachment; filename=subscriber_list.csv');  
$output = fopen("php://output", "w");  
fputcsv($output, array('SL', 'Subscriber Email'));  
$statement = $pdo->prepare("SELECT * FROM tbl_subscriber ");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	fputcsv($output, array($row['subs_id'],$row['subs_email']));
} 
fclose($output);
?>
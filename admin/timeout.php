<?php
include('../config.php');
$sqlee = "SELECT * FROM promo ORDER BY id DESC";
$queryee = mysqli_query($con, $sqlee);

if (!mysqli_num_rows($queryee) > 0) {
    echo '<td colspan="7"><center>No Categories-Data!</center></td>';
} else {
    while ($rowsee = mysqli_fetch_array($queryee)) {
        $current = strtotime($rowsee['sdat']);
        $end = strtotime($rowsee['edat']);

        // Calculate the timestamp for the day after the end date
        $nextDayAfterEnd = strtotime('+1 day', $end);
        
        if (time() >= $nextDayAfterEnd) {
            // The current date is equal to or exceeds the day after the end date

            // Update the status to '0'
            $mqlee = "UPDATE promo SET status = '1' WHERE edat = '$rowsee[edat]'";
            mysqli_query($con, $mqlee);
        }
    }
}
?>

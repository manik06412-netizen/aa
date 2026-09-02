<?php require_once('header.php'); ?>

<section class="content-header">
    <h1>Dashboard</h1>
</section>

<?php
error_reporting(0);
$statement = $pdo->prepare("SELECT * FROM res_category");
$statement->execute();
$total_top_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM user where fname!='Guest'");
$statement->execute();
$total_user = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_subscriber");
$statement->execute();
$total_subs= $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM dishes");
$statement->execute();
$total_product = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_user WHERE status='1'");
// $statement->execute();
// $total_customers = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_active='1'");
// $statement->execute();
// $total_subscriber = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost");
// $statement->execute();
// $available_shipping = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
// $statement->execute(array('Completed'));
// $total_order_completed = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?");
// $statement->execute(array('Completed'));
// $total_shipping_completed = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
// $statement->execute(array('Pending'));
// $total_order_pending = $statement->rowCount();

// $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=? AND shipping_status=?");
// $statement->execute(array('Completed','Pending'));
// $total_order_complete_shipping_pending = $statement->rowCount();
?>
<!-- chart calculation start -->
<?php 
// try {
// 	$query = "SELECT tbl_payment.payment_date, tbl_payment.paid_amount FROM tbl_order 
// 	INNER JOIN tbl_payment ON tbl_order.payment_id = tbl_payment.payment_id 
// 	WHERE tbl_payment.payment_status = 'Completed' 
// 	AND tbl_payment.shipping_status = 'Completed'";

// $stmt = $pdo->prepare($query);
// $stmt->execute();

// $monthly_totals = array_fill(0, 12, 0);
// $earliest_date = new DateTime('2999-12-31');

// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
// $date = new DateTime($row['payment_date']);
// if ($date < $earliest_date) {
//   $earliest_date = $date;
// }
// $month = (int)$date->format('m') - 1;
// $monthly_totals[$month] += $row['paid_amount'];
// }

// $start_month = (int)$earliest_date->format('m') - 1;

// $labels = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
// $data = $monthly_totals;

// $adjusted_labels = array_merge(array_slice($labels, $start_month), array_slice($labels, 0, $start_month));
// $adjusted_data = array_merge(array_slice($data, $start_month), array_slice($data, 0, $start_month));

    
// } catch (PDOException $e) {
//     echo "Error: " . $e->getMessage();
// }
    ?>

<section class="content">
    <div class="row">
        <a href="add_category.php">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?php echo $total_top_category; ?></h3>
                        <p>Category</p>
                    </div>
                    <div class="icon">
                        <i class="ionicons ion-android-cart"></i>
                    </div>

                </div>
            </div>
        </a>
        <!-- ./col -->
        <a href="add_category.php">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-maroon">
                    <div class="inner">
                        <h3><?php echo $total_user; ?></h3>

                        <p>Customers</p>
                    </div>
                    <div class="icon">
                        <i class="ionicons ion-clipboard"></i>
                    </div>

                </div>
            </div>
        </a>
        <!-- ./col -->
        <a href="subscriber.php">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo $total_subs; ?></h3>

                        <p>Subscribers</p>
                    </div>
                    <div class="icon">
                        <i class="ionicons ion-android-checkbox-outline"></i>
                    </div>

                </div>
            </div>
        </a>
        <a href="products_list.php">
            <div class="col-lg-3 col-xs-6">

                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo $total_product; ?></h3>

                        <p>Products</p>
                    </div>
                    <div class="icon">
                        <i class="ionicons ion-checkmark-circled"></i>
                    </div>

                </div>
            </div>
            </a>
    </div>
    <hr>
    <div class="row">
        <!-- ./col -->



        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-orange">
                <div class="inner">
                    <h3><?php echo $total_order_complete_shipping_pending; ?></h3>

                    <p>Pending Shippings</p>
                </div>
                <div class="icon">
                    <i class="ionicons ion-load-a"></i>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?php echo $total_customers; ?></h3>

                    <p>Active Customers</p>
                </div>
                <div class="icon">
                    <i class="ionicons ion-person-stalker"></i>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php echo $total_subscriber; ?></h3>

                    <p>Subscriber</p>
                </div>
                <div class="icon">
                    <i class="ionicons ion-person-add"></i>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-teal">
                <div class="inner">
                    <h3><?php echo $available_shipping; ?></h3>

                    <p>Available Shippings</p>
                </div>
                <div class="icon">
                    <i class="ionicons ion-location"></i>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-olive">
                <div class="inner">
                    <h3><?php echo $total_top_category; ?></h3>

                    <p>Top Categories</p>
                </div>
                <div class="icon">
                    <i class="ionicons ion-arrow-up-b"></i>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?php echo $total_mid_category; ?></h3>

                    <p>Mid Categories</p>
                </div>
                <div class="icon">
                    <i class="ionicons ion-android-menu"></i>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3><?php echo $total_end_category; ?></h3>

                    <p>End Categories</p>
                </div>
                <div class="icon">
                    <i class="ionicons ion-arrow-down-b"></i>
                </div>

            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-12 bg-info text-center">
            <h4>Monthly Income Report</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="myAreaChart" width="100%" height="30"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="vendor/chart.js/Chart.min.js"></script>

<script>
let data_list = <?=json_encode($adjusted_data); ?>;
let label_list = <?=json_encode($adjusted_labels); ?>;

Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: label_list,
        datasets: [{
            label: "amount",
            lineTension: 0.3,
            backgroundColor: "rgba(2,117,216,0.2)",
            borderColor: "rgba(2,117,216,1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(2,117,216,1)",
            pointBorderColor: "rgba(255,255,255,0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(2,117,216,1)",
            pointHitRadius: 20,
            pointBorderWidth: 2,
            data: data_list,
        }],
    },
    options: {
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: false
                },
                ticks: {
                    maxTicksLimit: 7
                }
            }],
            yAxes: [{

                gridLines: {
                    color: "rgba(0, 0, 0, .125)",
                }
            }],
        },
        legend: {
            display: false
        }
    }
});
</script>

<?php require_once('footer.php'); ?>
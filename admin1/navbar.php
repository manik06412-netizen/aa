<?php
include("../dbconnect.php");
error_reporting(0);
session_start();
if(empty($_SESSION["adm_id"]))
{
	header('location:index.php');
}
else
{
?>
<div class="header">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto mt-md-0">
                <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  "
                        href="javascript:void(0)"><i class="mdi mdi-menu"></i></a></li>
                <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  "
                        href="javascript:void(0)"><i class="ti-menu"></i></a>
                </li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">
                        <span class="admin_panals"> Admin Panel</span>
                    </a></li>
            </ul>

            <li class="nav-item dropdown"><span>Welcome Admin</span></li>
            <ul class="navbar-nav my-lg-0">
                <li class="nav-item dropdown">

                    <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                        <ul>
                            <li>
                                <div class="drop-title">Notifications</div>
                            </li>

                            <li>
                                <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all
                                        notifications</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><img src="images/ad1.png" alt="user"
                            class="profile-pic" /></a>
                    <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                        <ul class="dropdown-user">
                            <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
<?php
}
?>
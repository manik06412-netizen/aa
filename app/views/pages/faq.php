<?php 
include('include/header.php');
?>

<body>

    <div id="page">
    <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>
        <div class="sub_header_in">
            <div class="container text-center">
                <h1>Frequently Asked Questions</h1>
                <p>Find quick answers to common questions about hardware warranties, custom PC building, shipping, and payment options.</p>
            </div>
        </div>
	<main>
		<div class="container margin_60_35">
        
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background:#F8F8F8 ! important;">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                    </ol>
                </nav>
			<div class="row">
				<aside class="col-lg-1" id="faq_cat">
						
					
				</aside>
				<!--/aside -->
				
				<div class="col-lg-10" id="faq">
					
					<div role="tablist" class="add_bottom_45 accordion_2" id="payment">
                    <?php
												$sql="SELECT * FROM tbl_faq ";
												$query=mysqli_query($con,$sql);
												
													if(!mysqli_num_rows($query) > 0 )
														{
															echo '<td colspan="7"><center>No-Questions available!</center></td>';
														}
													else
														{				
																	while($rows=mysqli_fetch_array($query))
																		{
																		?>			
																			
						<div class="card">
							<div class="card-header" role="tab">
								<h5 class="mb-0">
									<a data-toggle="collapse" href="#collapseOne_payment<?php echo $rows['faq_id']; ?>" aria-expanded="true"><i class="indicator ti-plus"></i><?php echo $rows['faq_title']; ?></a>
								</h5>
							</div>

							<div id="collapseOne_payment<?php echo $rows['faq_id']; ?>" class="collapse " role="tabpanel" data-parent="#payment">
								<div class="card-body">
									<p>
                                    <?php echo $rows['faq_content']; ?> 
                                    
                                  </p>
								</div>
							</div>
						</div>
                        <?php
                                                                             }     }
                        ?>
						
					</div>
				
				</div>
				<!-- /col -->
                <aside class="col-lg-1" id="faq_cat">
						
					
                        </aside>
			</div>
			<!-- /row -->
		</div>
		<!--/container-->
	</main>
	<!--/main-->
	
    <?php include('include/footer.php') ?>
    </div>
    <!-- page -->
    <?php include('include/sign_footer.php'); ?>


</body>

</html>
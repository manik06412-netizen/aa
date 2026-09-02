<!DOCTYPE html>
<html lang="en">
   <?php
      include("../dbconnect.php");
      // error_reporting(0);
      session_start();
      
      ?>
   <?php include "head.php"; ?>
   <body class="fix-header fix-sidebar">
      <div id="main-wrapper">
         <!-- header header  -->
         <?php include "navbar.php"; ?>
         <?php include "sidebar1.php"; ?>
         <!-- Page wrapper  -->
         <div class="page-wrapper">
            <!-- Bread crumb -->
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
               <!-- Start Page Content -->
               <div class="row">
                  <div class="col-12">
                     <div class="card">
                        <div class="card-body">
                           <h4 class="card-title">All Products</h4>
                           <div class="table-responsive m-t-40">
                              <table id="example23"
                                 class="display nowrap table table-hover table-striped table-bordered"
                                 cellspacing="0" width="100%">
                                 <thead>
                                    <tr>
                                       <th>Product Name</th>
                                       <th>Product Code</th>
                                       <th>Category</th>
                                       <th>Expired day's</th>
                                       <th>Image</th>
                                       <th>Action</th>
                                       <th>status</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       $query=mysqli_query($con,"SELECT * FROM dishes inner join deals_prot on dishes.rs_id=deals_prot.d_id ");
                                       
                                       	if(!mysqli_num_rows($query) > 0 )
                                       		{
                                       			echo '<td colspan="11"><center>No product-Data!</center></td>';
                                       		}
                                       	else
                                       		{				
                                       					while($rows=mysqli_fetch_array($query))
                                       						{
                                       																											
                                       								
                                       									echo '<tr><td>'.$rows['dish_name'].'</td>
                                       												<td>'.$rows['d_id'].'</td>
                                                                                     <td>'.$rows['category'].'</td>		
                                                                                     <td>'.$rows['exp_date'].'</td>																							
                                       				
                                       												<td><div class="col-md-3 col-lg-8 m-b-10">
                                       												<center><img src="'.$rows['img'].'" class="img-responsive  radius" style="max-height:100px;max-width:150px;" /></center>
                                       												</div></td><td>	
                                                                                 <a href="#" onclick="confirmDelete(' . $rows['d_id'] . ')"
                                                                               class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                                <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                                                </a>
                                       										<a href="update_deals.php?menu_upd='.$rows['d_id'].'" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5"><i class="ti-settings"></i></a>
                                       													</td>
                                                                              <td>';	
                                                                              
                                                                           if($rows['status']==1){
                                                                            echo'  <a href="update_sta.php?menu_upd='.$rows['d_id'].'&id=2" class="btn btn-success btn-flat btn-addon btn-sm m-b-10 m-l-5">Active</i></a>';

                                                                           }else{
                                                                            echo'<a href="update_sta.php?menu_upd='.$rows['d_id'].'&id=1" class="btn btn-danger btn-flat btn-addon btn-sm m-b-10 m-l-5">Inactive</i></a>';

                                                                           }
                                       									
                                       													echo '</td>
                                                                              </tr>';
                                       												}	
                                       		}
  
                                       ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <script>
                        function confirmDelete(categoryId) {
                            var confirmDelete = confirm("Are you sure you want to delete this category?");
                            if (confirmDelete) {
                                window.location.href = 'delete_deal.php?cat_del=' + categoryId;
                            } else {
                                // Do nothing or handle cancellation
                            }
                        }
                     </script>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
      <footer class="footer"> All rights reserved. </footer>
      <!-- End footer -->
      </div>
      <!-- End Page wrapper  -->
      </div>
      <!-- All Jquery -->
      <script src="js/lib/jquery/jquery.min.js"></script>
      <!-- Bootstrap tether Core JavaScript -->
      <script src="js/lib/bootstrap/js/popper.min.js"></script>
      <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
      <!-- slimscrollbar scrollbar JavaScript -->
      <script src="js/jquery.slimscroll.js"></script>
      <!--Menu sidebar -->
      <script src="js/sidebarmenu.js"></script>
      <!--stickey kit -->
      <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
      <!--Custom JavaScript -->
      <script src="js/custom.min.js"></script>
      <script src="js/lib/datatables/datatables.min.js"></script>
      <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
      <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
      <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
      <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
      <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
      <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
      <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
      <script src="js/lib/datatables/datatables-init.js"></script>
   </body>
</html>
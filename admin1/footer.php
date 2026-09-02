        </div><!-- /.content-wrapper -->
    </div><!-- /.wrapper -->

    <!-- Base JavaScript Plugins -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/select2.full.min.js"></script>
    <script src="js/jquery.inputmask.js"></script>
    <script src="js/jquery.inputmask.date.extensions.js"></script>
    <script src="js/jquery.inputmask.extensions.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/icheck.min.js"></script>
    <script src="js/fastclick.js"></script>
    <script src="js/jquery.slimscroll.min.js"></script>
    <script src="js/jquery.fancybox.pack.js"></script>
    <script src="js/app.min.js"></script>
    <script src="js/summernote.js"></script>
    <script src="js/on-off-switch.js"></script>
    <script src="js/on-off-switch-onload.js"></script>


    <script>
    $(function() {
        // Initialize Select2 Elements
        if (typeof $.fn.select2 !== 'undefined' && $(".select2").length) {
            $(".select2").select2();
        }

        // Initialize Datepicker
        if (typeof $.fn.datepicker !== 'undefined') {
            $('#datepicker, #datepicker1').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                todayBtn: 'linked'
            });
        }

        // Initialize DataTables
        if (typeof $.fn.DataTable !== 'undefined') {
            // Set global default to not sort initially (respects SQL ORDER BY DESC)
            $.extend(true, $.fn.dataTable.defaults, {
                "order": []
            });

            if ($("#example1").length && !$.fn.DataTable.isDataTable('#example1')) {
                $("#example1").DataTable({
                    "order": [],
                    "language": {
                        "emptyTable": "No records found!",
                        "zeroRecords": "No records found!"
                    }
                });
            }
            if ($("#myTable").length && !$.fn.DataTable.isDataTable('#myTable')) {
                $("#myTable").DataTable({
                    "order": [],
                    "language": {
                        "emptyTable": "No records found!",
                        "zeroRecords": "No records found!"
                    }
                });
            }
        }

        // Delete confirmation modal handler
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

        // Initialize Summernote
        if (typeof $.fn.summernote !== 'undefined') {
            ['#editor1','#editor2','#editor3','#editor4','#editor5'].forEach(function(sel) {
                if ($(sel).length) {
                    $(sel).summernote({ height: 280 });
                }
            });
        }
    });

    // Helper confirmation functions
    function confirmDelete() { return confirm("Are you sure you want to delete this record?"); }
    function confirmActive()  { return confirm("Are you sure you want to activate this record?"); }
    function confirmInactive(){ return confirm("Are you sure you want to deactivate this record?"); }

    // Stock update trigger
    async function status_Change() {
        try {
            let r = await fetch('stock_cron.php');
            if (!r.ok) throw new Error('Network error');
            let result = await r.json();
            if (result.status === 1) {
                alert('Stock balance successfully updated!');
                location.reload();
            }
        } catch(e) {
            alert('Stock Update: ' + e.message);
        }
    }
    </script>
</body>
</html>

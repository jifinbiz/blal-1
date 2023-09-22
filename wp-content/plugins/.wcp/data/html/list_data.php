  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"/>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>



 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
 
 
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
 



<style type="text/css">
  .f-r{
    float: right;
  }
  .f-l{
    float: left;
  }
  .t-c{
    text-align: center;
  }
 
input[type=number],.wp-admin select
{
  height:34px;
}
.odd img ,.even img{
      width: 30px;
}
.submit-btn{
        width: 1px;
    padding: 0;
    height: 0;
    background: #fff;
    border: 0;
}
</style>
 
<div class="flex_container" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <h3>Downloaded Brochure Stats</h3>
        </div>
      

        <hr style="background-color:#000000; height:2px; width: 100%;">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='service-table' style="width:99.87% !important;" class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th class="all">Client Name</th> -->
                        <th class="all">ID</th>
                        <th class="all">PRODUCT NAME</th>
                        <th class="all">COMPANY NAME</th>
						 <th class="all">NAME</th>
                        <th class="all">EMAIL ADDRESS</th>
                        <th class="all">MOBILE NUMBER</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


 
<script>
    
    $(document).ready(function () {
      
        $ = jQuery;
        reload_table();
        var error_count=0;
         setInterval(function() {
                  window.location.reload();
                }, 300000); 

        

     

    });
    
    function reload_table() {
       
        $('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10, 
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "get_downpdf"}

                    },
                    "aoColumns": [
                        {mData: 'ID'},
                        {mData: 'product_name'},
                        {mData: 'company_name'},
						{mData: 'name'},
                        {mData: 'email'},
                        {mData: 'phone_no'}
                    ],
                    "order": [[ 0, "asc" ]],        
					"dom": 'Bfrtip',
					"buttons": [
						 { extend: 'csvHtml5', text: 'Export CSV' }
					],
                    
                    "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    
                  }
            });	 
    }

 
 
 
 
</script>
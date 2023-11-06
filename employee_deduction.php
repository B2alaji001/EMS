<?php

require_once ('process/dbh.php');
$sql = "SELECT employee.id,employee.firstName,employee.lastName,salary.base,salary.bonus,salary.total from employee,`salary` where employee.id=salary.id";

//echo "$sql";
$result = mysqli_query($conn, $sql);

?>



<html>
<head>
	<title>Salary Table | OGI Technologies</title>
	<link rel="stylesheet" type="text/css" href="styleview.css">
</head>
<body>
	
	<header>
		<nav>
			<h1>OGI</h1>
			<ul id="navli">
				<li><a class="homeblack" href="aloginwel.php">HOME</a></li>
				
				<li><a class="homeblack" href="addemp.php">Add Employee</a></li>
				<li><a class="homeblack" href="viewemp.php">View Employee</a></li>
				<li><a class="homeblack" href="salaryemp.php">Salary Table</a></li>
			    <li><a class="homered" href="employee_deduction.php">Deductions</a></li>
				<li><a class="homeblack" href="empleave.php">Employee Leave</a></li>
				<li><a class="homeblack" href="alogin.html">Log Out</a></li>
			</ul>
		</nav>
	</header>
	 
	<div class="divider"></div>

<?php
include("controller.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HR | Dashboard</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="dist/js/1.js"></script>
  <script src="dist/js/2.js"></script>
  <script src="dist/js/3.js"></script>

</head>
<div class="wrapper">

    <section class="content">
      <?php
      ini_set('display_errors', 0);
      ini_set('display_errors', false);
      $dd = date("H:i:s");
      if(isset($_SESSION['success'])) {
        echo "
          <div class='alert alert-success alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-check'></i> Success!</h4>
            ".$_SESSION['success']."
          </div>
        ";
      }
      if(isset($_SESSION['error'])) {
        echo "
          <div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-times'></i> Failed !</h4>
            ".$_SESSION['error']."
          </div>
        ";
      }
      if($dd == $_SESSION['expire'])
      {
        session_unset();
      }
      //session_unset();
      ?>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div align="right">
                <button class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modal-default"><i class="fas fa-plus"></i> New</button>
              </div><br>
              <table id="example1" class="table table-bordered dataTable no-footer" role="grid" aria-describedby="example1_info">
                <thead>
                <tr>
                  <th>Description</th>
                  <th>Amount</th>
                  <th>Tools</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM salary_deduct";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($result))
                {
                ?>
                <tr>
                  <td><?php echo $row['deduct_desc']; ?></td>
                  <td><?php echo $row['deduct_amount']; ?></td>
                  <td>
                    <button class="btn btn-success btn-flat deduct_edit" id="<?php echo $row['deduct_id']; ?>"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-flat delete_deduct" id="<?php echo $row['deduct_id']; ?>"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>



<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Deduction</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">

          <div class="form-group row">
            <label class="col-sm-1 col-form-label"></label>
            <label class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="add_desc" placeholder="" required>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-1 col-form-label"></label>
            <label class="col-sm-3 col-form-label">Amount</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="add_amount" placeholder="" required>
            </div>
          </div>

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary btn-flat" name="add_deduct"><i class="fas fa-save"></i> Save</button>
      </form>
      </div>
    </div>
  </div>
</div>

<div id="deduct_modal" class="modal fade">
     <div class="modal-dialog" role="document">
          <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Updating...</h5>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
                 </button>
               </div>
               <form method="POST">
               <div class="modal-body" id="deduct_details">
               </div>
               <div class="modal-body"></div>
               <div class="modal-footer justify-content-between">
                 <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                 <button type="submit" class="btn btn-primary btn-flat" name="deduct_update"><i class="fas fa-edit"></i> Save</button>
               </form>
               </div>
          </div>
     </div>
</div>
<script>
$(document).ready(function(){
     $('.deduct_edit').click(function(){
          var deduct_id = $(this).attr("id");
          $.ajax({
               url:"controller.php",
               method:"post",
               data:{deduct_id:deduct_id},
               success:function(data){
                    $('#deduct_details').html(data);
                    $('#deduct_modal').modal("show");
               }
          });
     });
});
</script>

<div id="deduct_del_modal" class="modal fade">
     <div class="modal-dialog" role="document">
          <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Deleting...</h5>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
                 </button>
               </div>
               <form method="POST">
               <div class="modal-body" id="deduct_del_details">
               </div>
               <div class="modal-body"></div>
               <div class="modal-footer justify-content-between">
                 <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                 <button type="submit" class="btn btn-danger btn-flat" name="del_deduct"><i class="fas fa-trash"></i> Delete</button>
               </form>
               </div>
          </div>
     </div>
</div>
<script>
$(document).ready(function(){
     $('.delete_deduct').click(function(){
          var del_id = $(this).attr("id");
          $.ajax({
               url:"controller.php",
               method:"post",
               data:{del_id:del_id},
               success:function(data){
                    $('#deduct_del_details').html(data);
                    $('#deduct_del_modal').modal("show");
               }
          });
     });
});
</script>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<script>
  $(function () {

    $('.select2').select2()

    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    $('#secondpicker').datetimepicker({
      format: 'LT'
    })

    $('#thirdpicker').datetimepicker({
      format: 'LT'
    })

    $('#fourthpicker').datetimepicker({
      format: 'LT'
    })

  })
</script>

<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
    });
  });
</script>
</body>
</html>

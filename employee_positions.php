<?php
include("controller.php");
?>



<html>
<head>
	<title>Employee position |  HR Panel | OGI Technologies</title>
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
                <li><a class="homered" href="emp_positions.php">Employee position</a></li>
				<li><a class="homeblack" href="salaryemp.php">Salary Table</a></li>
				<li><a class="homeblack" href="empleave.php">Employee Leave</a></li>
				<li><a class="homeblack" href="alogin.html">Log Out</a></li>
			</ul>
		</nav>
	</header>
	
	<div class="divider"></div>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">


  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
        
          
            <div class="card-body">
              <div align="right">
                <button class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modal-default"><i class="fas fa-plus"></i> New</button>
              </div><br>
              <table id="example1" class="table table-bordered dataTable no-footer" role="grid" aria-describedby="example1_info">
                <thead>
                <tr>
                  <th>Position Title</th>
                  <th>Rate / Hour</th>
                  <th>Tools</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM emp_position";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($result))
                {
                ?>
                <tr>
                  <td><?php echo $row['position_title']; ?></td>
                  <td><?php echo number_format($row['position_rate'], 2); ?></td>
                  <td>
                    <button class="btn btn-success btn-flat pos_edit" id="<?php echo $row['pos_id']; ?>"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-flat pos_delete" id="<?php echo $row['pos_id']; ?>"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
            </div>
      </div>
    </section>

<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Employee</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">

          <div class="form-group row">
            <label class="col-sm-1 col-form-label"></label>
            <label class="col-sm-3 col-form-label">Position Title</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="position_title" placeholder="" required>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-1 col-form-label"></label>
            <label class="col-sm-3 col-form-label">Rate / Hour</label>
            <div class="col-sm-7">
              <input type="number" class="form-control" name="position_rate" placeholder="" required>
            </div>
          </div>

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary btn-flat" name="add_position"><i class="fas fa-save"></i> Save</button>
      </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="pos_edit_modal" class="modal fade">
     <div class="modal-dialog" role="document">
          <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Update Position</h5>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
                 </button>
               </div>
               <form method="POST">
               <div class="modal-body" id="pos_edit_details">
               </div>
               <div class="modal-body"></div>
               <div class="modal-footer justify-content-between">
                 <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                 <button type="submit" class="btn btn-primary btn-flat" name="pos_update"><i class="fas fa-check"></i> Update</button>
               </form>
               </div>
          </div>
     </div>
</div>
<script>
$(document).ready(function(){
     $('.pos_edit').click(function(){
          var pos_id = $(this).attr("id");
          $.ajax({
               url:"controller.php",
               method:"post",
               data:{pos_id:pos_id},
               success:function(data){
                    $('#pos_edit_details').html(data);
                    $('#pos_edit_modal').modal("show");
               }
          });
     });
});
</script>

<div id="pos_delete_modal" class="modal fade">
     <div class="modal-dialog" role="document">
          <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Deleting...</h5>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
                 </button>
               </div>
               <form method="POST">
               <div class="modal-body" id="pos_delete_details">
               </div>
               <div class="modal-body"></div>
               <div class="modal-footer justify-content-between">
                 <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                 <button type="submit" class="btn btn-danger btn-flat" name="pos_delete"><i class="fas fa-trash"></i> Delete</button>
               </form>
               </div>
          </div>
     </div>
</div>
<script>
$(document).ready(function(){
     $('.pos_delete').click(function(){
          var pos_del_id = $(this).attr("id");
          $.ajax({
               url:"controller.php",
               method:"post",
               data:{pos_del_id:pos_del_id},
               success:function(data){
                    $('#pos_delete_details').html(data);
                    $('#pos_delete_modal').modal("show");
               }
          });
     });
});
</script>

<script src="plugins/toastr/toastr.min.js"></script>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
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

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
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


  

  <div class="content-wrapper">

    

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
                <iframe src="print_schedule.php" style="display:none;" name="printsched"></iframe>
                <button class="btn btn-info btn-flat" onclick="frames['printsched'].print()"><i class="fas fa-print"></i> Print</button>
              </div><br>
              <table id="example1" class="table table-bordered dataTable no-footer" role="grid" aria-describedby="example1_info">
                <thead>
                <tr>
                  <th>Empoyee ID</th>
                  <th>Name</th>
                  <th>Schedule</th>
                  <th>Tools</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM employee, emp_sched WHERE employee.sched_id = emp_sched.sched_id ";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($result))
                {
                ?>
                <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><?php echo $row['emp_fname']; ?> <?php echo $row['emp_lname']; ?></td>
                  <td><?php echo $row['sched_in']; ?> AM - <?php echo $row['sched_out']; ?> PM</td>
                  <td>
                    <button class="btn btn-success btn-flat sched_change" id="<?php echo $row['emp_id']; ?>"><i class="fas fa-edit"></i></button>
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

</div>

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

<div id="change_sched_modal" class="modal fade">
     <div class="modal-dialog" role="document">
          <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Changing...</h5>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
                 </button>
               </div>
               <form method="POST">
               <div class="modal-body" id="change_sched_details">
               </div>
               <div class="modal-body"></div>
               <div class="modal-footer justify-content-between">
                 <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                 <button type="submit" class="btn btn-primary btn-flat" name="change"><i class="fas fa-check"></i> Update</button>
               </form>
               </div>
          </div>
     </div>
</div>
<script>
$(document).ready(function(){
     $('.sched_change').click(function(){
          var change_id = $(this).attr("id");
          $.ajax({
               url:"controller.php",
               method:"post",
               data:{change_id:change_id},
               success:function(data){
                    $('#change_sched_details').html(data);
                    $('#change_sched_modal').modal("show");
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

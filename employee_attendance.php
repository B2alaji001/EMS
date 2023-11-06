<?php
include("controller.php");
?>

<html>
<head>
	<title>Employee attendance|  HR Panel | OGI Technologies</title>
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
                <li><a class="homeblack" href="emp_positions.php">Employee position</a></li>
				<li><a class="homeblack" href="salaryemp.php">Salary Table</a></li>
                <li><a class="homered" href="employee_attendance.php">attendance</a></li>
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

</head>


  
    
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered dataTable no-footer" role="grid" aria-describedby="example1_info">
                <thead>
                <tr>
                  <th>Date</th>
                  <th>Employee ID</th>
                  <th>Name</th>
                  <th>Time In</th>
                  <th>Time Out</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM emp_attendance, employee , emp_sched WHERE emp_attendance.employee_id = employee.id AND employee.sched_id = emp_sched.sched_id";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($result))
                {
                  if($row['attendance_timein'] <= $row['sched_in']) {
                ?>
                <tr>
                  <td><?php echo $row['attendance_date']; ?></td>
                  <td><?php echo $row['employee_id']; ?></td>
                  <td><?php echo $row['employee_name']; ?></td>
                  <td><?php echo $row['attendance_timein']; ?> <span class="float-right badge bg-success">On Time</span></td>
                  <td><?php echo $row['attendance_timeout']; ?></td>
                </tr>
                <?php
                  }
                  else {
                ?>
                <tr>
                  <td><?php echo $row['attendance_date']; ?></td>
                  <td><?php echo $row['employee_id']; ?></td>
                  <td><?php echo $row['employee_name']; ?></td>
                  <td><?php echo $row['attendance_timein']; ?> <span class="float-right badge bg-warning">Late</span></td>
                  <td><?php echo $row['attendance_timeout']; ?></td>
                </tr>
                <?php
                  }
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    

  



<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
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

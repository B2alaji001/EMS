<?php 
require_once ('process/dbh.php');
?>


<html>
<head>
	<title>HR Panel | OGI Technologies</title>
	<link rel="stylesheet" type="text/css" href="styleemplogin.css">
</head>
<body>
	
	<header>
		<nav>
			<h1>OGI</h1>
			<ul id="navli">
				<li><a class="homered" href="aloginwel.php">HOME</a></li>
				<li><a class="homeblack" href="addemp.php">Add Employee</a></li>
				<li><a class="homeblack" href="viewemp.php">View Employee</a></li>
				<li><a class="homeblack" href="salaryemp.php">Salary Table</a></li>
				<li><a class="homeblack" href="empleave.php">Employee Leave</a></li>
				<li><a class="homeblack" href="alogin.html">Log Out</a></li>
			</ul>
		</nav>
	</header>
<div class="divider"></div>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  include("header.php");
  ?>
  


  

    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-lg-3 col-6">

            <div class="small-box bg-info">
              <div class="inner">
                <?php
                $sql0 = "SELECT count(id) As 'Total' FROM employee";
                $result0 = mysqli_query($conn, $sql0);
                $row0 = mysqli_fetch_array($result0);
                $num0 = $row0['Total'];
                ?>
                <h3><?php echo $num0; ?></h3>

                <p>Employee / Staff</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="viewemp.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">

            <div class="small-box bg-success">
              <div class="inner">
                <?php
                $sql1 = "SELECT count(pos_id) As 'Pos' FROM emp_position";
                $result1 = mysqli_query($conn, $sql1);
                $row1 = mysqli_fetch_array($result1);
                $num1 = $row1['Pos'];
                ?>
                <h3><?php echo $num1; ?></h3>

                <p>Total Positions</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="employee_positions.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">

            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                $sql2 = "SELECT count(*) As 'Ontime' FROM emp_attendance, employee, emp_sched WHERE emp_attendance.attendance_timein <= emp_sched.sched_in AND emp_attendance.employee_id = employee.id AND emp_sched.sched_id = employee.sched_id AND emp_attendance.attendance_date = CURDATE(); ";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_array($result2);
                ?>
                <h3><?php echo $row2['Ontime']; ?></h3>

                <p>On Time Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="employee_attendance.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <?php
                $sql3 = "SELECT count(*) As 'Late' FROM emp_attendance, employee, emp_sched WHERE emp_attendance.attendance_timein > emp_sched.sched_in AND emp_attendance.employee_id = employee.id AND emp_sched.sched_id = employee.sched_id AND emp_attendance.attendance_date = CURDATE(); ";
                $result3 = mysqli_query($conn, $sql3);
                $row3 = mysqli_fetch_array($result3);
                ?>
                <h3><?php echo $row3['Late']; ?></h3>
                <p>Late Today</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="employee_attendance.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>


<?php
include("footer.php");
?>
<?php 
require_once ('process/dbh.php');
$sql = "SELECT id, firstName, lastName,  points FROM employee, rank WHERE rank.eid = employee.id order by rank.points desc";
$result = mysqli_query($conn, $sql);
?>


	<div id="divimg">
		<h2 style="font-family: 'Montserrat', sans-serif; font-size: 25px; text-align: center;">Empolyee Leaderboard </h2>
    	<table>

			<tr bgcolor="#000">
				<th align = "center">Seq.</th>
				<th align = "center">Emp. ID</th>
				<th align = "center">Name</th>
				<th align = "center">Points</th>
				

			</tr>

			<?php
				$seq = 1;
				while ($employee = mysqli_fetch_assoc($result)) {
					echo "<tr>";
					echo "<td>".$seq."</td>";
					echo "<td>".$employee['id']."</td>";
					
					echo "<td>".$employee['firstName']." ".$employee['lastName']."</td>";

					echo "<td>".$employee['points']."</td>";
					
					$seq+=1;
				}
			?>
		</table>

		<div class="p-t-20">
			<button class="btn btn--radius btn--green" type="submit" style="float: right; margin-right: 60px"><a href="reset.php" style="text-decoration: none; color: black"> Reset Points</button>
		</div>

		
	</div>

</body>
</html>

<?php

include("process/dbh.php");

date_default_timezone_set('Asia/Manila');

if(isset($_POST['Sign_in']))
{
  $username = mysqli_real_escape_string($conn,$_POST['log_username']);
  $password = mysqli_real_escape_string($conn,$_POST['log_password']);

  $sql = "SELECT * FROM accounts WHERE username='$username' AND password='$password'";
  $result = mysqli_query($conn, $sql);

  if (!$row = $result->fetch_assoc()){
    echo '<script>
             setTimeout(function() {
                 Swal.fire({
                     title: "Failed !",
                     text: "Username or Password is incorrect !",
                     type: "error"
                   }).then(function() {
                       window.location = "index.php";
                   });
             }, 30);
         </script>';
  }
  else {
      $_SESSION['name'] = $row['name'];
      $_SESSION['username'] = $row['username'];
      $sql = "SELECT * FROM accounts WHERE username = '$username' and password = '$password' ";
      $result = mysqli_query($conn, $sql);
      $row = $result->fetch_assoc();
      $_SESSION['username'] = $username;
      if ($row['type'] === 'Admin')
            header("Location: home.php");
  }
}

if(isset($_POST['logout'])) {
  session_start();
  if(session_destroy())
  {
    header("Location: index.php");
  }
}

if(isset($_POST['add_position']))
{

  $title = $_POST['position_title'];
  $rate = $_POST['position_rate'];

  $chkquery = "SELECT * FROM emp_position WHERE position_title = '$title' AND position_rate = '$rate'";
  $chkresult = mysqli_query($conn, $chkquery);

  if(!$row = $chkresult->fetch_assoc()) {
    $sql = "INSERT INTO emp_position (position_title, position_rate) VALUES ('$title', '$rate')";
    $result = mysqli_query($conn, $sql);

    $_SESSION['success'] = "New position has been added ! ";
    $_SESSION['expire'] =  date("H:i:s", time() + 1);

  }
  else {
    $_SESSION['error'] = "Failed to add new position ! ";
    $_SESSION['expire'] =  date("H:i:s", time() + 1);
  }
  header('location: employee_positions.php');
}

if(isset($_POST['add_employee']))
{
  $tag = $_POST['id'];
  $fname = $_POST['firstName'];
  $lname = $_POST['lastName'];
  $address = $_POST['address'];
  $contact = $_POST['contact'];
  $gender = $_POST['gender'];
  $position = $_POST['EMP_POSITION'];
  $sched = $_POST['emp_schedule'];
  $regdate = date("Y-m-d");

  $sql = "SELECT sched_in, sched_out FROM emp_sched WHERE sched_id = '$sched'";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_array($result))
  {
    $in = $row['sched_in'];
    $out = $row['sched_out'];
  }

  $target_dir = "img/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

  $filename = $_FILES['fileToUpload']['name'];
  if(!empty($filename)){
    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], 'img/'.$filename);
  }

  $query = "INSERT INTO employee (emp_card, firstName, lastName, dept, address, contact, gender, emp_timein, emp_timeout, sched_id, pic)
                          VALUES ('$tag', '$fname', '$lname', '$position', '$address', '$contact', '$gender', '$in', '$out', '$sched', '$target_file')";
  $resquery = mysqli_query($conn, $query);

  echo '<script>
           setTimeout(function() {
               Swal.fire({
                   title: "Success !",
                   text: "New Employee has been ADded!",
                   type: "success"
                 }).then(function() {
                     window.location = "employee_list.php";
                 });
           }, 30);
       </script>';

}

if(isset($_POST['add_sched']))
{
  $in = $_POST['sched_timein'];
  $out = $_POST['sched_timeout'];

  $in_24  = date("H:i", strtotime($in));
  $out_24 = date("H:i", strtotime($out));

  $chkquery = "SELECT * FROM emp_sched WHERE sched_in = '$in_24' AND sched_out = '$out_24'";
  $chkresult = mysqli_query($conn, $chkquery);

  if(!$row = $chkresult->fetch_assoc()) {
    $sql = "INSERT INTO emp_sched (sched_in, sched_out) VALUES ('$in_24', '$out_24')";
    $result = mysqli_query($conn, $sql);

    $_SESSION['success'] = "New Schedule has been added ! ";
    $_SESSION['expire'] =  date("H:i:s", time() + 1);

  }
  else {
    $_SESSION['error'] = "Failed to add new schedule ! ";
    $_SESSION['expire'] =  date("H:i:s", time() + 1);
  }
  header('location: employee_sched.php');

}

if(isset($_POST["pos_id"]))
{
    $output = '';
    $sql = "SELECT * FROM emp_position WHERE pos_id = '".$_POST["pos_id"]."'";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">';
    while($row = mysqli_fetch_array($result))
    {
          $id = $row["pos_id"];
          $title = $row['position_title'];
          $rate = $row['position_rate'];

         $output .= '
              <input type="text" name="update_id" class="form-control" value="'.$id.'" hidden>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Position Title</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="update_title" value="'.$title.'" placeholder="" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Rate / Hour</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="update_rate" value="'.number_format($rate,2).'" placeholder="" required>
                </div>
              </div>
              ';
    }
    $output .= "</form>";
    echo $output;

}

if(isset($_POST["pos_update"]))
 {
   $id = $_POST['update_id'];
   $title = $_POST['update_title'];
   $rate = $_POST['update_rate'];

   $sql = "UPDATE emp_position SET position_title = '".$title."', position_rate = '".$rate."' WHERE pos_id = '".$id."'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Position Information has been updated!",
                    type: "success"
                  }).then(function() {
                      window.location = "employee_positions.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST["pos_del_id"]))
{
    $output = '';
    $sql = "SELECT * FROM emp_position WHERE pos_id = '".$_POST["pos_del_id"]."'";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">';
    while($row = mysqli_fetch_array($result))
    {
          $id = $row["pos_id"];
          $title = $row['position_title'];
          $rate = $row['position_rate'];

         $output .= '
              <input type="text" name="update_id" class="form-control" value="'.$id.'" hidden>
              <div class="text-center">
	                	<p>DELETE POSITION</p>
	                	<h2>'.$title.'</h2>
	            </div>
              ';
    }
    $output .= "</form>";
    echo $output;

}

if(isset($_POST["pos_delete"]))
 {
   $id = $_POST['update_id'];

   $sql = "DELETE FROM emp_position WHERE pos_id = '$id'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Position has been Deleted !",
                    type: "success"
                  }).then(function() {
                      window.location = "employee_positions.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST["emp_edit_id"]))
{
    $output = '';
    $sql = "SELECT * FROM employee WHERE id = '".$_POST["emp_edit_id"]."'";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">';
    while($row = mysqli_fetch_array($result))
    {
          $card = $row["id"];
          $emp = $row['emp_card'];
          $fname = $row['firstName'];
          $lname = $row['lastName'];
          $address = $row['address'];
          $contact = $row['contact'];
          $gender = $row['gender'];

         $output .= '
              <input type="text" name="id" class="form-control" value="'.$card.'" hidden>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Employee ID</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="emp_card" value="'.$emp.'" placeholder="" disabled>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Firstname</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="update_fname" value="'.$fname.'" placeholder="">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Lastname</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="update_lname" value="'.$lname.'" placeholder="">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="update_address" value="'.$address.'" placeholder="">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Contact</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="update_contact" value="'.$contact.'" placeholder="">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Gender</label>
                <div class="col-sm-7">
                  <select name="update_gender" class="form-control" value="'.$gender.'">
                    <option hidden> - Select -</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Prefer Not">Prefer Not to say</option>
                  </select>
                </div>
              </div>
              ';
    }
    $output .= "</form>";
    echo $output;

}

if(isset($_POST["emp_update"]))
 {
   $card = $_POST["emp_id"];
   $fname = $_POST['update_fname'];
   $lname = $_POST['update_lname'];
   $address = $_POST['update_address'];
   $contact = $_POST['update_contact'];
   $gender = $_POST['update_gender'];

   $sql = "UPDATE employee SET emp_fname = '".$fname."', emp_lname = '".$lname."', emp_address = '".$address."', emp_contact = '".$contact."', emp_gender = '".$gender."'
   WHERE emp_id = '".$card."'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Employee Information has been updated !",
                    type: "success"
                  }).then(function() {
                      window.location = "employee_list.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST["emp_del_id"]))
{
    $output = '';
    $sql = "SELECT * FROM employee WHERE emp_id = '".$_POST["emp_del_id"]."'";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">';
    while($row = mysqli_fetch_array($result))
    {
          $id = $row["emp_id"];

         $output .= '
              <input type="text" name="del_id" class="form-control" value="'.$id.'" hidden>
              <div class="text-center">
	                	<p>DELETE EMPLOYEE</p>
	                	<h2>'.$row['emp_fname'] .' ' . ' '. $row['emp_lname'].'</h2>
	            </div>
              ';
    }
    $output .= "</form>";
    echo $output;

}

if(isset($_POST["emp_delete"]))
 {
   $id = $_POST['del_id'];

   $sql = "DELETE FROM employee WHERE emp_id = '$id'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Employee has been Deleted !",
                    type: "success"
                  }).then(function() {
                      window.location = "employee_list.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST["sched_id"]))
{
    $output = '';
    $sql = "SELECT * FROM emp_sched WHERE sched_id = '".$_POST["sched_id"]."'";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">';
    while($row = mysqli_fetch_array($result))
    {
          $id = $row["sched_id"];

         $output .= '
              <input type="text" name="del_id" class="form-control" value="'.$id.'" hidden>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Time in</label>
                <div class="col-sm-7">
                  <div class="bootstrap-timepicker">
                    <div class="input-group date" id="thirdpicker" data-target-input="nearest">
                      <input type="time" value="'.$row['sched_in'].'" name="sched_update_in" class="form-control datetimepicker-input" data-target="#timepicker" data-toggle="datetimepicker" placeholder="">
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Time out</label>
                <div class="col-sm-7">
                  <div class="bootstrap-timepicker">
                    <div class="input-group date" id="fourthpicker" data-target-input="nearest">
                      <input type="time" value="'.$row['sched_out'].'" name="sched_update_out" class="form-control datetimepicker-input" data-target="#secondpicker" data-toggle="datetimepicker" placeholder="">
                    </div>
                  </div>
                </div>
              </div>
              ';
    }
    $output .= "</form>";
    echo $output;

}

if(isset($_POST["edit_sched"]))
 {
   $id = $_POST['del_id'];
   $in = $_POST['sched_update_in'];
   $out = $_POST['sched_update_out'];

   $sql = "UPDATE emp_sched SET sched_in = '$in', sched_out = '$out' WHERE sched_id = '$id'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Schedule has been updated !",
                    type: "success"
                  }).then(function() {
                      window.location = "employee_sched.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST["delsched_id"]))
{
    $output = '';
    $sql = "SELECT * FROM emp_sched WHERE sched_id = '".$_POST["delsched_id"]."'";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">';
    while($row = mysqli_fetch_array($result))
    {
          $id = $row["sched_id"];

         $output .= '
              <input type="text" name="del_id" class="form-control" value="'.$id.'" hidden>
              <div class="text-center">
	                	<p>DELETE SCHEDULE</p>
	                	<h2>'.$row['sched_in'] .' ' .'-'. ' '. $row['sched_out'].'</h2>
	            </div>
              ';
    }
    $output .= "</form>";
    echo $output;

}

if(isset($_POST["delete_sched"]))
 {
   $id = $_POST['del_id'];

   $sql = "DELETE FROM emp_sched WHERE sched_id = '$id'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Schedule has been Deleted !",
                    type: "success"
                  }).then(function() {
                      window.location = "employee_sched.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST['add_deduct']))
{
  $desc = $_POST['add_desc'];
  $amount = $_POST['add_amount'];

  $sql = "INSERT INTO salary_deduct (deduct_desc, deduct_amount) VALUES ('$desc', '$amount')";
  $result = mysqli_query($conn, $sql);

  echo '<script>
           setTimeout(function() {
               Swal.fire({
                   title: "Success !",
                   text: "Deduction has been Added !",
                   type: "success"
                 }).then(function() {
                     window.location = "employee_deduction.php";
                 });
           }, 30);
       </script>';

}

if(isset($_POST["deduct_id"]))
{
    $output = '';
    $sql = "SELECT * FROM salary_deduct WHERE deduct_id = '".$_POST["deduct_id"]."'";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">';
    while($row = mysqli_fetch_array($result))
    {
          $id = $row["deduct_id"];

         $output .= '
              <input type="text" name="deduct_edit_id" class="form-control" value="'.$id.'" hidden>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="deduct_edit_description" value="'.$row['deduct_desc'].'" placeholder="" required>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-1 col-form-label"></label>
                <label class="col-sm-3 col-form-label">Amount</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" name="deduct_edit_amount" value="'.$row['deduct_amount'].'" placeholder="" required>
                </div>
              </div>
              ';
    }
    $output .= "</form>";
    echo $output;

}

if(isset($_POST["deduct_update"]))
 {
   $id = $_POST['deduct_edit_id'];
   $desc = $_POST['deduct_edit_description'];
   $amount = $_POST['deduct_edit_amount'];

   $sql = "UPDATE salary_deduct SET deduct_desc = '$desc', deduct_amount = '$amount' WHERE deduct_id = '$id'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Deduction information has been updated !",
                    type: "success"
                  }).then(function() {
                      window.location = "employee_deduction.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST["del_id"]))
{
    $output = '';
    $sql = "SELECT * FROM salary_deduct WHERE deduct_id = '".$_POST["del_id"]."'";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">';
    while($row = mysqli_fetch_array($result))
    {
          $id = $row["deduct_id"];

         $output .= '
              <input type="text" name="del_id" class="form-control" value="'.$id.'" hidden>
              <div class="text-center">
	                	<p>DELETE DEDUCTION</p>
	                	<h2>'.$row['deduct_desc'].'</h2>
	            </div>
              ';
    }
    $output .= "</form>";
    echo $output;

}

if(isset($_POST["del_deduct"]))
 {
   $id = $_POST['del_id'];

   $sql = "DELETE FROM salary_deduct WHERE deduct_id = '$id'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Deduction has been Deleted !",
                    type: "success"
                  }).then(function() {
                      window.location = "employee_deduction.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST['apply_date']))
{
  $_SESSION['start_month'] = $_POST['startmonth'];
  $_SESSION['end_month'] = $_POST['endmonth'];

  header('location: print_payroll.php');
}

if(isset($_POST["change_id"]))
{
    $output = '';
    $sql = "SELECT * FROM emp_sched";
    $result = mysqli_query($conn, $sql);
    $output .= '
    <form method="POST">
    <br>
    <input type="text" name="change_sched_id" class="form-control" value="'.$_POST['change_id'].'" hidden>
    <div class="form-group row">
      <label class="col-sm-1 col-form-label"></label>
      <label class="col-sm-3 col-form-label">Schedule</label>
      <div class="col-sm-7">
        <select class="form-control" name="new_sched">
    ';
    while($row = mysqli_fetch_array($result))
    {
         $hold = $_POST['change_id'];
         $output .= '

                  <option value='.$row['sched_id'].'>'.$row['sched_in'].' - '.$row['sched_out'].'</option>

              ';
    }
    $output .= "
    </div>
    </div>
    </form>";
    echo $output;

}

if(isset($_POST["change"]))
 {
   $id = $_POST['change_sched_id'];
   $new = $_POST['new_sched'];

   $sql = "UPDATE employee SET sched_id = '$new' WHERE emp_id = '$id'";
   $result = mysqli_query($conn, $sql);

   echo '<script>
            setTimeout(function() {
                Swal.fire({
                    title: "Success !",
                    text: "Schedule information has been updated !",
                    type: "success"
                  }).then(function() {
                      window.location = "print_sched.php";
                  });
            }, 30);
        </script>';
}

if(isset($_POST['new_payslip']))
{
  $_SESSION['card'] = $_POST['new_payslip'];
  header("location: print_payslip.php");
}



?>

<!-- <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Overtime Calculator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>
    <title></title>

  </head>
  <body> -->

  <?php
  try {
    $handler = new PDO('mysql:host=localhost;dbname=luovertime;','root','root');
    // $handler = new PDO('mysql:host=localhost;dbname=overtime;','root','root');
    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo $e->getMessage();
    die();
  }

   ?>



  <div class="top-margin jumbotron">
    <h1 class="display-3"><h2>London Underground operational employee after tax overtime calculator UPDATED FOR 2018/19</h2></h1>
    <p class="lead">Please fill out the following form to obtain an estimate of your take home pay after tax</p>
    <hr class="m-y-2">
    <p>Please note that this is still in a testing phase. If your estimate is off by more than £25-30 please email
    <a href="mailto:david.hill@tube.tfl.gov.uk?subject=overtime_calculator_issues">Dave Hill</a> and I'll see where my calculations have gone awry...
    <p>
      When you first fill out the form and enter your details you will receive an estimate and a unique id. The next time you want to get an estimate
      all you need to do is enter your unique id into the first box and the details you previously entered will auto complete. The only thing you will need
      to change is the number of hours overtime you have completed.
    </p>
  </div>

<?php

if(isset($_POST['overtime'])) {
  $salaryGrade = $_POST['salaryGrade'];
  $pension = $_POST['pension'];
  $ot = $_POST['ot'];
  $extradeduct = $_POST['extradeduct'];
  $sloanDeduct = $_POST['sloanDeduct'];
  $seasonDeduct = $_POST['seasonDeduct'];
  $hsfDeduct = $_POST['hsfDeduct'];


  if (strtoupper($salaryGrade) === "CSM1") {
    $salary = 59853;//47135 2016

  } else if (strtoupper($salaryGrade) === "CSM2") {
    $salary = 53930;

  } else if (strtoupper($salaryGrade) === "CSS1") {
    $salary = 48950;

  } else if (strtoupper($salaryGrade) === "CSS2") {
    $salary = 40170;

  } else if (strtoupper($salaryGrade) === "CSA1") {
    $salary = 33524;

  } else if (strtoupper($salaryGrade) === "CSA2") {
    $salary = 26111;

  } else {
  	$salary = $salaryGrade;
  }



  $salary13 = $salary / 13;



  $salaryNoni = $salary13;

  //National insurance contribution
  function ni($salary13) {
      if ($salary13 > 648 && $salary13 <= 3565.37) { //primary threshold to upper earning limit
         $ni = ($salary13 - 648) / 100 * 12;//12% NI rate
        return $ni;

      } else if($salary13 > 3565.38){
         $ni = ($salary13 - 3565.38) / 100 * 2 + 350.08;//2% NI rate
        return $ni;
    }
  }
  $ni = ni($salary13);


  if (strtoupper($pension) === "YES") {
     $pensionContribution = ($salary13 - 464) / 100 * 5;//6032 is the lower ear$nings limit for $pensions 2017-18
     $salary13 = $salary13 - $pensionContribution; //LUL $pensions currently at 5% minus the lower ear$ning limit
  }

   //$tax
  function taxCalc($salary13) {
      if ($salary13 >= 911.53 && $salary13 <= 3565.38) {
         $tax = ($salary13 - 911.53) / 100 * 20;
        return $tax;
      } else if ($salary13 > 3565.39 && $salary13 <= 12450) {
         $tax = ($salary13 - 3565.39) / 100 * 40 + 530.77;
        return $tax;
    }
  }

  $tax = taxCalc($salary13);
  $finalSalary = $salary13  - $tax - $ni;
  $monthly = $finalSalary; //pay every 4 weeks so 13 times per year
  $otCalculator = ($salaryNoni / 4 / 35) * (1.25 * $ot);
  $salary1 = $salary13 + $otCalculator;
  $salary2 = $salaryNoni + $otCalculator;
  $otni = ni($salary2);//ot $ni calc
  $tax1 = taxCalc($salary1);//ot $tax calc
  $otAftertax = $salary1 - $tax1 - $otni - $finalSalary;//this takes the difference between the overtime salary for the month minus the salary over 13

  $totalExtraDuductions = $extradeduct + $sloanDeduct + $seasonDeduct + $hsfDeduct;

  $total = $monthly + $otAftertax - $totalExtraDuductions;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $salaryGrade1 = trim($salary);
    $pension1 = trim($_POST['pension']);
    $ot1 = trim($_POST['ot']);
    $extradeduct1 = trim($_POST['extradeduct']);
    $sloanDeduct1 = trim($_POST['sloanDeduct']);
    $seasonDeduct1 = trim($_POST['seasonDeduct']);
    $hsfDeduct1 = trim($_POST['hsfDeduct']);

      try {
      //PDO query execution goes here.
      $sql = "INSERT INTO ot (salary, overtime, pension, season, hsf, extra, student) VALUES (?,?,?,?,?,?,?)";
      $query = $handler->prepare($sql);
      $query->execute(array($salaryGrade1, $ot1, $pension1, $seasonDeduct1, $hsfDeduct1, $extradeduct1, $sloanDeduct1));
  }
  catch (\PDOException $e) {
      if ($e->errorInfo[1] == 1062) {

      }
  }



  }










}



$lastId = $handler->lastInsertId();
//mysqli_insert_id() does the same





 ?>
 <?php
if(isset($_POST['Id'])) {

  $uniqueId = $_POST['uniqueId'];
  $validitytest = $handler->prepare("SELECT * FROM ot
  WHERE id = :uniqueId");
  $validitytest->bindParam(':uniqueId',$uniqueId);
  $validitytest->execute();
  $r = $validitytest->fetch(PDO::FETCH_OBJ);
  $sal = htmlspecialchars($r->salary);
  $over = htmlspecialchars($r->overtime);
  $pen = htmlspecialchars($r->pension);
  $sea = htmlspecialchars($r->season);
  $hospsat = htmlspecialchars($r->hsf);
  $ext = htmlspecialchars($r->extra);
  $stu = htmlspecialchars($r->student);

  // echo '<div id="cash" class="col-lg-12 col-md-12 col-sm-12">';
  // echo '<table class="table table-bordered m-b-3"><tbody>';
  // echo '<tr>';
  // echo '<th scope="row">Salary for your grade</th>';
  // echo '<td>£ ' . $salary . '</td>';
  // echo '</tr>';
  // echo '<tr>';
  // echo '<th scope="row">4 weekly Take Home</th>';
  // echo '<td>£' . round($monthly, 2) . '</td>';
  // echo '</tr>';
  // echo '<tr>';
  // echo '<th scope="row">Overtime this month</th>';
  // echo '<td>' . $ot . '</td>';
  // echo '</tr>';
  // echo '<tr>';
  // echo '<th scope="row">Overtime is worth</th>';
  // echo '<td>£' . round($otCalculator,2) . '</td>';
  // echo '</tr>';
  // echo '<tr>';
  // echo '<th scope="row">Regular monthly deductions</th>';
  // echo '<td>£' . $totalExtraDuductions . '</td>';
  // echo '</tr>';
  // echo '<tr>';
  // echo '<th scope="row">Estimated Take Home</th>';
  // echo '<td class="red">£' . round($total, 2) . '</td>';
  // echo '</tr>';
  // echo '<tr>';
  // echo '<th>Your unique id for this estimate is: </th>';
  // echo '<td>' . $lastId . '</td>';
  // echo '</tr>';
  // echo '</tbody>';
  // echo '</table>';
  // echo '</div>';






}



  ?>
 <div class="well">
<form class="" action="ottest.php" method="post">
  <div class="form-group">
    <label for="post_author">Enter unique id to pre-populate entries (ignore if you dont have one)</label>
    <input type="number" value="arse" class="form-control left" name="uniqueId" >
  </div>
  <div class="form-group">

    <input class="btn btn-primary" name="Id" type="submit" value="Enter to pre-populate form">
    <!--  the name create_post is the name that gets picked up by the top if(isset($_POST['create_post'])) {-->
    </div>
</form>

<form class="form" action="ottest.php" method="post">


  <div class="form-group">
    <label for="post_author">Enter your grade (CSA1, CSA2, CSS1, CSS2) or your yearly salary</label>
    <input type="text" class="form-control left" name="salaryGrade" value="<?php if(isset($_POST['Id'])) { echo $sal;}?>">
  </div>
  <div class="form-group">
    <label for="post_author">Do you pay into the LU pension? (type yes or no)</label>
    <input type="text" class="form-control" name="pension" value="<?php if(isset($_POST['Id'])) { echo $pen;}?>">
  </div>
  <div class="form-group">
    <label for="post_author">How many hours overtime have you completed</label>
    <input type="number" class="form-control" name="ot" >
  </div>
  <div class="form-group">
    <label for="post_author">Total monthly student loan payments? (leave blank if not applicable)</label>
    <input type="number" class="form-control" name="sloanDeduct" value="<?php if(isset($_POST['Id'])) { echo $stu;}?>">
  </div>
  <div class="form-group">
    <label for="post_author">Total monthly HSF payments? (leave blank if not applicable)</label>
    <input type="number" class="form-control" name="hsfDeduct" value="<?php if(isset($_POST['Id'])) { echo $hospsat;}?>">
  </div>
  <div class="form-group">
    <label for="post_author">Monthly season ticket loan payments? (leave blank if not applicable)</label>
    <input type="number" class="form-control" name="seasonDeduct" value="<?php if(isset($_POST['Id'])) { echo $sea;}?>">
  </div>
  <div class="form-group">
    <label for="post_author">Any other extras (tbf etc) (leave blank if not applicable)</label>
    <input type="number" class="form-control" name="extradeduct" value="<?php if(isset($_POST['Id'])) { echo $ext;}?>">
  </div>

  <div class="form-group">

    <input class="btn btn-primary" name="overtime" type="submit" value="Enter for quotation">
    <!--  the name create_post is the name that gets picked up by the top if(isset($_POST['create_post'])) {-->
    </div>
</div>
</form>
<?php if(isset($_POST['overtime'])) {


  echo '<div id="cash" class="col-lg-12 col-md-12 col-sm-12">';
  echo '<table class="table table-bordered m-b-3"><tbody>';
  echo '<tr>';
  echo '<th scope="row">Salary for your grade</th>';
  echo '<td>£ ' . $salary . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th scope="row">4 weekly Take Home</th>';
  echo '<td>£' . round($monthly, 2) . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th scope="row">Overtime this month</th>';
  echo '<td>' . $ot . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th scope="row">Overtime is worth</th>';
  echo '<td>£' . round($otCalculator,2) . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th scope="row">Regular monthly deductions</th>';
  echo '<td>£' . $totalExtraDuductions . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th scope="row">Tax</th>';
  echo '<td>£' . round($tax1, 2) . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th scope="row">NI</th>';
  echo '<td>£' . round($otni, 2) . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th scope="row">Pension</th>';
  echo '<td>£' . round($pensionContribution, 2) . '</td>';
  echo '</tr>';
  echo '<th scope="row">Estimated Take Home</th>';
  echo '<td class="red">£' . round($total, 2) . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th>Your unique id for this estimate is: </th>';
  echo '<td>' . $lastId . '</td>';
  echo '</tr>';
  echo '</tbody>';
  echo '</table>';
  echo '</div>';





} ?>








<!-- <div id="cash" class="col-lg-12 col-md-12 col-sm-12">
<table class="table table-bordered m-b-3"><tbody>
<tr>
<th scope="row">Salary for your grade</th>
<td>£<?php echo $salary; ?></td>
</tr>
<tr>
<th scope="row">4 weekly Take Home</th>
<td>£<?php echo round($monthly, 2); ?></td>
</tr>
<tr>
<th scope="row">Overtime this month</th>
<td><?php echo $ot; ?></td>
</tr>
<tr>
<th scope="row">Overtime is worth</th>
<td>£<?php echo round($otCalculator, 2); ?></td>
</tr>
<tr>
<th scope="row">Regular monthly deductions</th>
<td>£<?php echo $totalExtraDuductions;?></td>
</tr>
<tr>
<th scope="row">Estimated Take Home</th>
<td class="red">£<?php echo round($total, 2); ?> </td>
</tr>
<tr>
<th>Your unique id for this estimate is: </th>
<td><?php echo $lastId; ?></td>
</tr>
</tbody>
</table>
</div> -->














  </body>

  <script src="js/jquery.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="js/bootstrap.min.js"></script>


</html>

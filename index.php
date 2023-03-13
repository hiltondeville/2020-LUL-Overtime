<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Overtime Calculator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">

    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-60689461-1', 'auto');
  ga('send', 'pageview');

</script>


  </head>

  <body>

  <div class="container">

    <nav class="navbar navbar-dark bg-dark">
      <a class="navbar-brand" href="#!">Overtime Calculator</a>
      <li class="nav-item">
    <a class="nav-link" href="/ot">Refresh</a>
  </li>
   </nav>


  <?php
  try {
    $handler = new PDO('mysql:host=localhost;dbname=luovertime;','root','root');

    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo $e->getMessage();
    die();
  }

   ?>






  <div class="top-margin jumbotron">
    <h1 class="display-3"><h2>London Underground operational employee after tax overtime calculator UPDATED FOR PAY RISE 2021/22</h2></h1>
    <p class="lead">Please fill out the following form to obtain an estimate of your take home pay after tax</p>
    <p class="lead">Figures are based on HMRC tax codes for 2021/22. Individual circumstances and volume of overtime undertaken over the year may affect the accuracy of the results. Please use as a guide only.</p>
    <hr class="m-y-2">
    <p>If your estimate is off by more than £25-30 please email
    <a href="mailto:david.hill@tube.tfl.gov.uk?subject=overtime_calculator_issues">Dave Hill</a> and I'll see where my calculations have gone awry...

    <p>New feature: Added student loan repayments based on the three schemes that are currently in use (the old scheme being a monthly fixed deduction and the two newer schemes a percentage deduction)</p>
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

  $sloanScheme1= ($_POST['sloanScheme1']);


  $sloanScheme2= ($_POST['sloanScheme2']);





  if (strtoupper($salaryGrade) === "CSM1") {
    $salary = 69525;//47135 2016

  } else if (strtoupper($salaryGrade) === "CSM2") {
    $salary = 62680;

  } else if (strtoupper($salaryGrade) === "CSS1") {
    $salary = 56862;

  } else if (strtoupper($salaryGrade) === "CSS2") {
    $salary = 46661;

  } else if (strtoupper($salaryGrade) === "CSA1") {
    $salary = 38942;

  } else if (strtoupper($salaryGrade) === "CSA2") {
    $salary = 30381;

  } else {
  	$salary = $salaryGrade;
  }



  $salary13 = $salary / 13;



  $salaryNoni = $salary13;

  //National insurance contribution
  function ni($salary13) {
      if ($salary13 > 823.01 && $salary13 <= 4189) { //primary threshold to upper earning limit
         $ni = ($salary13 - 823.01) * 0.1325;//13.25% NI rate
        return $ni;

      } else if($salary13 > 4189.01){
         $ni = ($salary13 - 4189.01) * 0.325 + 445.99;//3.25% NI rate
        return $ni;
    }
  }



  if (strtoupper($pension) === "YES") {
     $pensionContribution = ($salary13 - 492)  * 0.05;//6396 is the lower ear$nings limit for $pensions 2022/23
     $salary13 = $salary13 - $pensionContribution; //LUL $pensions currently at 5% minus the lower ear$ning limit
  }

   //$tax
  function taxCalc($salary13) {
      if ($salary13 >= 967 && $salary13 <= 3866.92) {
         $tax = ($salary13 - 967) / 100 * 20;
        return $tax;
      } else if ($salary13 > 3866.92 && $salary13 <= 12570) {
         $tax = ($salary13 - 3866.92) / 100 * 40 + 579.98;
        return $tax;
    }
  }
  //student loans calculator

  //scheme 1 £18,330
  $scheme1 = ($salaryNoni / 4 / 35) * (1.25 * $ot) + $salaryNoni;//$salaryNoni used as the 9% rate comes off the whole figure after the threshold and overtime is included in the take
  if (strtoupper($sloanScheme1) === "YES") {
    if($scheme1 >= 1456) {
      $scheme1result = floor(($scheme1 - 1456) / 100 * 9);

    }
  }

  //scheme 2 £25,000

  $scheme2 = ($salaryNoni / 4 / 35) * (1.25 * $ot) + $salaryNoni;
  if (strtoupper($sloanScheme2) === "YES") {
    if($scheme2 >= 1976) {
      $scheme2result = floor(($scheme2 - 1976) / 100 * 9);

    }
  }







  $ni = ni($salary13);
  // var_dump($ni);

  $tax = taxCalc($salary13);
   // var_dump($ni);


  $finalSalary = $salary13  - $tax - $ni;
//var_dump($finalSalary );
  $monthly = $finalSalary; //pay every 4 weeks so 13 times per year
//var_dump($monthly);
  $otCalculator = ($salaryNoni / 4 / 35) * (1.25 * $ot);
// var_dump($otCalculator);
  $salary1 = $salary13 + $otCalculator;
// var_dump($salary1);
  $salary2 = $salaryNoni + $otCalculator;
// var_dump($salary2);
  $otni = ni($salary2);//ot $ni calc
// var_dump($otni);
  $tax1 = taxCalc($salary1);//ot $tax calc
// var_dump($tax1);
  $otAftertax = $salary1 - $tax1 - $otni - $finalSalary;//this takes the difference between the overtime salary for the month minus the salary over 13
// var_dump($otAftertax);
  $totalExtraDuductions = $extradeduct + $sloanDeduct + $seasonDeduct + $hsfDeduct;
// var_dump($totalExtraDuductions);
  //$total = $monthly + $otAftertax - $totalExtraDuductions - $scheme1result - $scheme2result;
  $total = $finalSalary + $otAftertax - $totalExtraDuductions - $scheme1result - $scheme2result;
// var_dump($total);
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $salaryGrade1 = trim($salary);
    $pension1 = trim($_POST['pension']);
    $ot1 = trim($_POST['ot']);
    $extradeduct1 = trim($_POST['extradeduct']);
    $sloanDeduct1 = trim($_POST['sloanDeduct']);
    $seasonDeduct1 = trim($_POST['seasonDeduct']);
    $hsfDeduct1 = trim($_POST['hsfDeduct']);
    $scheme1 = trim($_POST['sloanScheme1']);
    $scheme2 = trim($_POST['sloanScheme2']);


      try {
      //PDO query execution goes here.
      $sql = "INSERT INTO otsloantest (salary, overtime, pension, season, hsf, extra, student, sloanScheme1, sloanScheme2) VALUES (?,?,?,?,?,?,?,?,?)";
      $query = $handler->prepare($sql);
      $query->execute(array($salaryGrade1, $ot1, $pension1, $seasonDeduct1, $hsfDeduct1, $extradeduct1, $sloanDeduct1, $scheme1, $scheme2));
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
  $validitytest = $handler->prepare("SELECT * FROM otsloantest
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
  $sch1 = htmlspecialchars($r->sloanScheme1);
  $sch2 = htmlspecialchars($r->sloanScheme2);





}



  ?>
  <div class="<?php if(isset($_POST['overtime'])) { echo "d-none"; } ?>">
 <div class="well">
<form class="" action="index.php" method="post">
  <div class="form-group">
    <label for="post_author">Enter unique id to pre-populate entries (ignore if you dont have one)</label>
    <input type="number" value="arse" class="form-control left" name="uniqueId" >
  </div>

  <div class="form-group">

    <input class="btn btn-primary" name="Id" type="submit" value="Enter to pre-populate form">
    <button type="button" class="btn btn-secondary" data-container="body"
            data-toggle="popover" data-placement="top"
            data-content="When you have entered a quotation, use the unique id number from the form
            to save writing all the data every time, it will remember all your answers so you only
            need to enter the amount of hours worked">
      More info
    </button>
    <!--  the name create_post is the name that gets picked up by the top if(isset($_POST['create_post'])) {-->
    </div>

</form>

<form class="form" action="index.php" method="post">


  <div class="form-group">

    <label for="post_author">Enter your grade (CSA1, CSA2, CSM1, CSS1, CSS2) or your yearly salary</label>
    <input type="text" class="form-control left" name="salaryGrade" value="<?php if(isset($_POST['Id'])) { echo $sal;}?>">
  </div>
  <div class="form-group">

    <label for="post_author">Do you pay into the LU pension? (type yes or no)</label>
    <input type="text" class="form-control" name="pension" value="<?php if(isset($_POST['Id'])) { echo $pen;}?>">
  </div>
  <div class="form-group">
    <label for="post_author">How many hours overtime have you completed</label>
    <input type="number" step="0.01" class="form-control" name="ot" >
  </div>
  <div class="form-group">
    <button type="button" class="btn btn-secondary" data-container="body"
            data-toggle="popover" data-placement="top"
            data-content="As this loan is fixed, just enter the monthly repayment value">
      More info
    </button>
    <label for="post_author">Total monthly student loan payments? FOR PRE 1998 LOANS (leave blank if not applicable)</label>
    <input type="number" class="form-control" name="sloanDeduct" value="<?php if(isset($_POST['Id'])) { echo $stu;}?>">
  </div>
  <div class="form-group">
    <button type="button" class="btn btn-secondary" data-container="body"
            data-toggle="popover" data-placement="top"
            data-content="Type yes if you took out your loan before 1 September 2012, and:

            - you lived in England or Wales; or

            - received EU funding in England or Wales.">
      More info
    </button>
    <button type="button" class="btn btn-success" data-container="body"
            data-toggle="popover" data-placement="right"
            data-content="How much you repay
       You pay back 9% of your income over the minimum amount of:

       £18,330 for Plan 1 - this amount changes on 6 April every year
       £25,000 for Plan 2
       Interest starts being added to your loan from when you get your first payment. How much you pay depends on which plan you’re on.

       Plan 1
       You’re on Plan 1 if you’re:

       an English or Welsh student who started your undergraduate course before 1 September 2012
       a Scottish or Northern Irish student
       Your income per year	Monthly repayments
       £18,330 and under	£0
       £20,000	£12
       £25,000	£50
       £30,000	£87
       £50,000	£237
       Interest on Plan 1
       You currently pay interest of 1.5% on Plan 1. You can find interest rates for previous years.

       Plan 2
       You’re on Plan 2 if you’re an English or Welsh student who started your undergraduate course on or after 1 September 2012.

       Your income per year	Monthly repayments
       £25,000 and under	£0
       £30,000	£37
       £35,000	£75
       £50,000	£187
       Interest on Plan 2
       While you’re studying, interest is inflation plus 3%.

       Once you’ve left your course, your interest rate depends on your income in the previous tax year.

       If you’re self-employed, your income is the total income amount on your Self-Assessment form.

       If you’re an employee, your income is your taxable pay:

       plus any pension contributions
       minus any benefits you get from your employer that are taxed through payroll (ask your employer if you’re not sure)
       If you have more than one job in a year, your interest rate will be based on your combined income from all your jobs.

       Income	Interest rate
       £25,000 or less	Inflation
       £25,000 to £45,000	Inflation plus up to 3%
       Over £45,000	Inflation plus 3%
       The Student Loans Company has more information on how they calculate interest.

       If you have Plan 1 and Plan 2 loans
       You still pay back 9% of your income over £18,330 a year.

       If you earn between £18,330 and £25,000, your payments only go towards your Plan 1 loan.

       If you earn over £25,000, your 9% payments go towards both your loans.

       If you have 2 or more jobs
       Your employers will deduct repayments from your salary - but only for the jobs where you earn over the minimum amount.

       HMRC may send you a tax return to make a self assessment of the repayments you owe for the whole year. You’ll need to pay 9% of all your income over the threshold - but any repayments you’ve already made from your salary will be deducted from this.

       Keep all your payslips and P60 - you’ll need them if you claim a refund.

       You might end up paying your loan back sooner if your income from savings and investments is over £2,000 a year.">
      Detailed info
    </button>

    <label for="post_author">STUDENT LOAN SCHEME 1 (PRE 2012) TYPE "YES" OR LEAVE BLANK IN NOT APPLICALBLE</label>
    <input type="text" class="form-control" name="sloanScheme1" value="<?php if(isset($_POST['Id'])) { echo $sch1;}?>">
  </div>

<div class="form-group">
  <button type="button" class="btn btn-secondary" data-container="body"
          data-toggle="popover" data-placement="top"
          data-content="Type yes if you took out your loan (excluding Postgraduate Loans) on or after 1 September 2012, and:

          - you lived in England or Wales

          - received EU funding in England or Wales

          - you lived in England and took an Advanced Learner Loan for a further education course.">
    More info
  </button>

  <button type="button" class="btn btn-success" data-container="body"
          data-toggle="popover" data-placement="right"
          data-content="How much you repay
     You pay back 9% of your income over the minimum amount of:

     £18,330 for Plan 1 - this amount changes on 6 April every year
     £25,000 for Plan 2
     Interest starts being added to your loan from when you get your first payment. How much you pay depends on which plan you’re on.

     Plan 1
     You’re on Plan 1 if you’re:

     an English or Welsh student who started your undergraduate course before 1 September 2012
     a Scottish or Northern Irish student
     Your income per year	Monthly repayments
     £18,330 and under	£0
     £20,000	£12
     £25,000	£50
     £30,000	£87
     £50,000	£237
     Interest on Plan 1
     You currently pay interest of 1.5% on Plan 1. You can find interest rates for previous years.

     Plan 2
     You’re on Plan 2 if you’re an English or Welsh student who started your undergraduate course on or after 1 September 2012.

     Your income per year	Monthly repayments
     £25,000 and under	£0
     £30,000	£37
     £35,000	£75
     £50,000	£187
     Interest on Plan 2
     While you’re studying, interest is inflation plus 3%.

     Once you’ve left your course, your interest rate depends on your income in the previous tax year.

     If you’re self-employed, your income is the total income amount on your Self-Assessment form.

     If you’re an employee, your income is your taxable pay:

     plus any pension contributions
     minus any benefits you get from your employer that are taxed through payroll (ask your employer if you’re not sure)
     If you have more than one job in a year, your interest rate will be based on your combined income from all your jobs.

     Income	Interest rate
     £25,000 or less	Inflation
     £25,000 to £45,000	Inflation plus up to 3%
     Over £45,000	Inflation plus 3%
     The Student Loans Company has more information on how they calculate interest.

     If you have Plan 1 and Plan 2 loans
     You still pay back 9% of your income over £18,330 a year.

     If you earn between £18,330 and £25,000, your payments only go towards your Plan 1 loan.

     If you earn over £25,000, your 9% payments go towards both your loans.

     If you have 2 or more jobs
     Your employers will deduct repayments from your salary - but only for the jobs where you earn over the minimum amount.

     HMRC may send you a tax return to make a self assessment of the repayments you owe for the whole year. You’ll need to pay 9% of all your income over the threshold - but any repayments you’ve already made from your salary will be deducted from this.

     Keep all your payslips and P60 - you’ll need them if you claim a refund.

     You might end up paying your loan back sooner if your income from savings and investments is over £2,000 a year.">
    Detailed info
  </button>

    <label for="post_author">STUDENT LOAN SCHEME 2 (POST 2012) TYPE "YES" OR LEAVE BLANK IN NOT APPLICALBLE</label>
    <input type="text" class="form-control" name="sloanScheme2" value="<?php if(isset($_POST['Id'])) { echo $sch2;}?>">

  </div>
  <div class="form-group">
    <label for="post_author">Total monthly HSF payments? (leave blank if not applicable)</label>
    <input type="number" class="form-control" name="hsfDeduct" value="<?php if(isset($_POST['Id'])) { echo $hospsat;}?>">
  </div>
  <!-- <div class="form-group">
    <label for="post_author">Monthly season ticket loan payments? (leave blank if not applicable)</label>
    <input type="number" class="form-control" name="seasonDeduct" value="<?php if(isset($_POST['Id'])) { echo $sea;}?>">
  </div> -->
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
</div>
<?php if(isset($_POST['overtime'])) {


  echo '<div id="cash" class="col-lg-12 col-md-12 col-sm-12">';
  echo '<table class="table table-bordered m-b-3"><tbody>';
  echo '<tr>';
  echo '<th scope="row">Salary for your grade</th>';
  echo '<td class="bg-success">£ ' . $salary . ' ' .   $scheme2result .'</td>';
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
  echo '<tr>';
  echo '<th scope="row">Student loan deductions (Scheme 1)</th>';
  echo '<td>£' . round($scheme1result, 2). '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th scope="row">Student loan deductions (Scheme 2)</th>';
  echo '<td>£' . round($scheme2result, 2) . '</td>';
  echo '</tr>';
  echo '<th scope="row">Estimated Take Home</th>';
  echo '<td class="bg-danger">£' . round($total, 2) . '</td>';
  echo '</tr>';
  echo '<tr>';
  echo '<th>Your unique id for this estimate is: </th>';
  echo '<td>' . $lastId . '</td>';
  echo '</tr>';
  echo '<th><a href="/ot">CLICK TO RESET FORM</a></th>';
  echo '<td><a href="/ot">Click to reset</a></td>';
  echo '</tr>';
  echo '</tbody>';
  echo '</table>';
  echo '</div>';





} ?>





</div>

  </body>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script>

  $(function(){
    $('[data-toggle="popover"]').popover()
  });

  </script>
</html>

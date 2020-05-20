<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Mock Test System</title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css"/>
 <link  rel="stylesheet" href="css/font.css"/>
 <script src="js/jquery.js" type="text/javascript"></script>

 
<script src="js/bootstrap.min.js"  type="text/javascript"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
 <!--alert message-->
<?php if(@$_GET['w'])
{echo'<script>alert("'.@$_GET['w'].'");</script>';}
?>
<!--alert message end-->

</head>
<?php
include_once 'dbConnection.php';
?>
<body>
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">Mock Test System</span></div>
<div class="col-md-4 col-md-offset-2">
 <?php
 include_once 'dbConnection.php';
session_start();
  if(!(isset($_SESSION['email']))){
header("location:index.php");

}
else
{
$name = $_SESSION['name'];
$email=$_SESSION['email'];

$query=mysqli_query($con,"SELECT * FROM user WHERE email='$email'") or die("Error222");
while($result=mysqli_fetch_array($query))
{
  $name=$result['name'];
  $gender=$result['gender'];
  $college=$result['college'];
  $mobile=$result['mob'];
}
include_once 'dbConnection.php';
echo '<span class="pull-right top title1" ><span class="log1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Hello,</span> <a href="#" class="log log1" data-toggle="modal" data-target="#profile">'.$name.'</a>&nbsp;|&nbsp;</span>

         <div class="modal fade" id="profile"   >
        <div class="modal-dialog" style="background-color:black">
          <div class="modal-content title1" style="background-color:black">
            <div class="modal-header" style="background-color:black">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title title1"><span style="color:orange">Profile</span></h4>
            </div> <div class="modal-body" style="margin-left:3rem">
                <table>
                <tr><label  class="detail">Name</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<label class="detail">'.$name.'</label></tr><br><br>
                <tr><label  class="detail">Gender</label></tr>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<tr><label class="detail">'.$gender.'</label></tr><br><br>
                <tr><label  class="detail">College</label></tr>&nbsp&nbsp&nbsp&nbsp<tr><label class="detail">'.$college.'</label></tr><br><br>
                <tr><label  class="detail">Email id</label></tr>&nbsp&nbsp&nbsp&nbsp<tr><label class="detail">'.$email.'</label></tr><br><br>
                <tr><label  class="detail">Mobile </label></tr>&nbsp&nbsp&nbsp&nbsp<tr><label class="detail">'.$mobile.'</label></tr><br></table>
            </div></div></div></div>';
 // echo  '<a href="#" class="pull-right btn sub1" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>&nbsp;<span class="title1"><b></b></span></a>';
}?>
</div>
</div>
</div>
<div class="bg">

<!--navigation menu-->
<nav class="navbar navbar-default title1">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
     
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
          <li <?php if(@$_GET['q']==1) echo'class="active"'; ?> ><a href="account.php?q=1"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span class="sr-only">(current)</span></a></li>
          <li <?php if(@$_GET['q']==2) echo'class="active"'; ?>><a href="account.php?q=2"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;History</a></li>
      		<li <?php if(@$_GET['q']==3) echo'class="active"'; ?>><a href="account.php?q=3"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp;Ranking</a></li>
      		<li class="pull-right"> <a href="logout.php?q=account.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Signout</a></li>
  		</ul>
      </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav><!--navigation menu closed-->

<?php
  include 'include/home.php';
  ?>

  </body>
</html>
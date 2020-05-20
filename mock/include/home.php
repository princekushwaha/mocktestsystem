<!-- fetch exams -->
<?php
if(@$_GET['q']==1)
{
  $q=mysqli_query($con,"SELECT * FROM exam")or die('Error200');
  while($row=mysqli_fetch_array($q))
  {
    $ename=$row['ename'];
    if($_SESSION['key']!='admin')
    $next="account.php?q=5&id=".$row['eid'];
    else
    $next="dash.php?q=5&id=".$row['eid'];
    echo 
    '<a href='.$next.' class="exm container"><label style="margin-top:1rem;color:#dea726;cursor:pointer">'.$ename.'</label></a><br>';
  }
}?>



<!-- available tests -->
<?php
if(@$_GET['q']==5)
{
  $_SESSION['hid']=null;
  $_SESSION['array']=null;
  echo  '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
    <tr><td><b>S.N.</b></td><td><b>Title</b></td><td><b>Total question</b></td><td><b>Total Marks</b></td><td><b>Time limit</b></td><td></td></tr>';

  $eid=@$_GET['id'];
  $q=mysqli_query($con,"SELECT * FROM test WHERE eid='$eid'") or die('Error200');
  $c=1;
  while($row=mysqli_fetch_array($q))
  {
    $tid=$row['tid'];
    $title=$row['title'];
    $tques=$row['tques'];
    $tmarks=$row['tmarks'];
    $duration=$row['duration'];
    if($_SESSION['key']=='admin')
      $next='dash.php';
    else
      $next='account.php';
    echo '<tr><td><b>'.$c.'</b></td><td><b>'.$title.'</b></td><td><b>'.$tques.'</b></td><td><b>'.$tmarks.'</b></td><td><b>'.$duration.'</b></td><td></td><td><a href='.$next.'?q=quiz&sn=1&eid='.$eid.'&tid='.$tid.'&n='.$tques.'><button 
      style="background-color:black;color:#dea726;padding:0.2rem;border:none;" onMouseOver=this.style.transform="scale(1.2)" onMouseOut=this.style.transform="scale(1)">START</button></a></td></tr>';
    $c++;
  }


}?>


<!-- history start -->
<?php
if(@$_GET['q']== 2) 
{
echo  '<div class="panel title">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>S.N.</b></td><td><b>Exam Name</b></td><td><b>Question Paper Title</b></td><td><b>Right</b></td><td><b>Wrong<b></td><td><b>Score</b></td>';
$c=0;
$q=mysqli_query($con,"SELECT * FROM history WHERE email='$email' ORDER BY date DESC " ) or die('Error197');
while($row=mysqli_fetch_array($q) )
{
$tid=$row['tid'];
$s=$row['score'];
$w=$row['wrong'];
$r=$row['sahi'];
$l=$row['level'];

$query1=mysqli_query($con,"SELECT * FROM test WHERE tid='$tid'") or die('Error222');
while($result=mysqli_fetch_array($query1)){
  $eid=$result['eid'];
  $title=$result['title'];
  $query2=mysqli_query($con,"SELECT * FROM exam WHERE eid='$eid'");
  while($result=mysqli_fetch_array($query2)){
    $ename=$result['ename'];
  }
}


$c++;
echo '<tr><td>'.$c.'</td><td>'.$ename.'</td><td>'.$title.'</td><td>'.$r.'</td><td>'.$w.'</td><td>'.$s.'</td></tr>';
}
echo'</table></div>';
}?>


<!-- ranking start -->
<?php
if(@$_GET['q']== 3) 
{
$q=mysqli_query($con,"SELECT * FROM rank  ORDER BY score DESC " )or die('Error223');

echo  '<div class="panel title"><div class="table-responsive">  
<table class="table table-striped title1" >
<tr style="color:red"><td><b>Name</b></td><td><b>Exam name</b></td><td><b>Question Paper TItle</b></td><td><b>Out Of</b></td><td><b>Score</b></td></tr>';
$c=0;
while($row=mysqli_fetch_array($q) )
{
$e=$row['email'];
$s=$row['score'];
$tid=$row['tid'];
$q12=mysqli_query($con,"SELECT * FROM user WHERE email='$e'" )or die('Error231');
while($row=mysqli_fetch_array($q12) )
{
$name=$row['name'];
$gender=$row['gender'];
$college=$row['college'];
}
$query1=mysqli_query($con,"SELECT * FROM test WHERE tid='$tid'") or die('Error222');
while($result=mysqli_fetch_array($query1)){
  $eid=$result['eid'];
  $title=$result['title'];
  $tmarks=$result['tmarks'];
  $query2=mysqli_query($con,"SELECT * FROM exam WHERE eid='$eid'") or die('Error 2343');
  while($result=mysqli_fetch_array($query2)){
    $ename=$result['ename'];
  }
}
$c++;
echo '<tr><td>'.$name.'</td><td>'.$ename.'</td><td>'.$title.'</td><td>'.$tmarks.'</td><td>'.$s.'</td><td>';
}
echo '</table></div></div>';}
?>

<!-- quiz -->

<?php
if(@$_GET['q']== 'quiz') {
$eid=@$_GET['eid'];
$tid=@$_GET['tid'];
$sn=@$_GET['sn'];
$total=@$_GET['n'];
if(isset($_GET['hid'])){
$hid=@$_GET['hid'];}else {
  $hid='0';
}
$q=mysqli_query($con,"SELECT * FROM questions WHERE tid='$tid' AND sn='$sn' " );
echo '<div class="panel" style="margin:5%">';
while($row=mysqli_fetch_array($q) )
{
$qns=$row['title'];
$qid=$row['qid'];
echo '<b>Question &nbsp;'.$sn.'&nbsp;::<br />'.$qns.'</b><br /><br />';
}
$q=mysqli_query($con,"SELECT * FROM options WHERE qid='$qid' " );
echo '<form action=update.php?q=quiz&step=2&eid='.$eid.'&tid='.$tid.'&sn='.$sn.'&total='.$total.'&qid='.$qid.
' method="POST"  class="form-horizontal"><br />';

while($row=mysqli_fetch_array($q) )
{
$option=$row['option'];
$optionid=$row['optionid'];
echo'<input type="radio" name="ans" value="'.$optionid.'">'.$option.'<br /><br />';
}
echo'<br /><button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Submit</button></form></div>';
}

?>

<!-- result display -->
<?php
if(@$_GET['q']== 'result' && @$_GET['eid']) 
{
  $hid=$_SESSION['hid'];
$eid=@$_GET['eid'];
$q=mysqli_query($con,"SELECT * FROM history WHERE  hid='$hid'" )or die('Error157');
echo  '<div class="panel">
<center><h1 class="title" style="color:#660033">Result</h1><center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';

while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
$w=$row['wrong'];
$r=$row['sahi'];
$qa=$row['level'];
echo '<tr style="color:#66CCFF"><td>Total Questions</td><td>'.$qa.'</td></tr>
      <tr style="color:#99cc32"><td>right Answer&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>'.$r.'</td></tr> 
    <tr style="color:red"><td>Wrong Answer&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>'.$w.'</td></tr>
    <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>'.$s.'</td></tr>';
}
$q=mysqli_query($con,"SELECT * FROM rank WHERE  email='$email' " )or die('Error157');
while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
echo '<tr style="color:#990000"><td>Overall Score&nbsp;<span class="glyphicon glyphicon-stats" aria-hidden="true"></span></td><td>'.$s.'</td></tr>';
}
echo '</table></div>';

}
?>

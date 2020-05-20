<?php
include_once 'dbConnection.php';
session_start();
$email=$_SESSION['email'];


   // include_once 'dbConnection.php';
   if(@$_GET['q']=='fetch_tests'){

       $exam=@$_GET['exam'];
       $query="SELECT  * FROM test WHERE test.eid=(SELECT eid FROM exam WHERE exam.ename='$exam')";
      $result=mysqli_query($con,$query);

      $count=0;
      $test=array();
      while($row=mysqli_fetch_array($result)){
       $test[$count]=$row['title'];
       $count++;
      }
  
      echo json_encode($test);
      }
   


//delete user
if(isset($_SESSION['key'])){
if(@$_GET['demail'] && $_SESSION['key']=='admin') {
$demail=@$_GET['demail'];
$r1 = mysqli_query($con,"DELETE FROM rank WHERE email='$demail' ") or die('Error');
$r2 = mysqli_query($con,"DELETE FROM history WHERE email='$demail' ") or die('Error');
$result = mysqli_query($con,"DELETE FROM user WHERE email='$demail' ") or die('Error');
header("location:dash.php?q=users");
}
}

//add exam
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'addexam' && $_SESSION['key']=='admin') {
	$name=$_POST['name'];
	$eid=uniqid();
    $query="INSERT INTO exam VALUES ('$eid','$name')";
    mysqli_query($con,$query);
    header("location:dash.php?q=1");
	}
	
}
if(isset($_SESSION['key'])){
	if(@$_GET['q']=='removeexam' && $_SESSION['key']=='admin'){

        $exam=$_POST['exam'];
       //  echo $exam;
        $query=mysqli_query($con,"SELECT eid FROM exam WHERE ename='$exam'");
        while($result=mysqli_fetch_array($query)) $eid=$result['eid'];
        mysqli_query($con,"DELETE FROM exam WHERE ename='$exam'");
        $query=mysqli_query($con,"SELECT tid FROM test WHERE eid='$eid'");
        mysqli_query($con,"DELETE FROM test WHERE eid='$eid'");

        while($tid=mysqli_fetch_array($query)){
        	 //echo $tid['tid'];
        	 $q1=mysqli_query($con,"SELECT qid FROM questions WHERE 'tid='".$tid['tid']);
        	  mysqli_query($con,"DELETE FROM questions WHERE 'tid='".$tid['tid']);
        	  while($result=mysqli_fetch_array($q1)){
        	  	mysqli_query($con,"DELETE FROM answers WHERE qid=".$result['qid']);
        	  	mysqli_query($con,"DELETE FROM options WHERE qid=".$result['qid']);
        	  }


        }
         header("location:dash.php?q=1");
	}
}

//remove qp
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'removetest' && $_SESSION['key']=='admin') {
	$exam=$_POST['exam'];
    $test=$_POST['test'];
     // echo '<h1>'.$test.'</h1>';
	 
     $query="DELETE FROM test WHERE title='$test'";
    mysqli_query($con,$query);
    header("location:dash.php?q=1");
	}
	
}

//add test
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'addques' && $_SESSION['key']=='admin') {
$name = $_POST['name'];
$name= ucwords(strtolower($name));
$eid=$_POST['exam'];
$eid=mysqli_query($con,"SELECT eid FROM exam WHERE ename='$eid'");
$eid=mysqli_fetch_array($eid);
$eid=$eid['eid'];
$total = $_POST['total'];
$right = $_POST['right'];
$wrong = $_POST['wrong'];
$time = $_POST['time'];
$tmarks=(int)$right*(int)$total;
$date = date('Y-m-d h:i:s', time());
$tid=uniqid();
$q3=mysqli_query($con,"INSERT INTO test VALUES ('$eid','$tid','$name','$total','$tmarks','$time','$date','$right','$wrong')") or die('Error');
header("location:dash.php?q=4&step=2&n=".$total."&eid=".$eid."&tid=".$tid);
}
}

//add question
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'addqns' && $_SESSION['key']=='admin') {
$n=@$_GET['n'];
$eid=@$_GET['eid'];
$tid=@$_GET['tid'];
$ch=@$_GET['ch'];

for($i=1;$i<=$n;$i++)
 {
 $qid=uniqid();
 $qns=$_POST['qns'.$i];
$q3=mysqli_query($con,"INSERT INTO questions VALUES  ('$eid','$tid','$qid','$i','$qns', '$ch')") or die("ques");
$oaid=uniqid();
$obid=uniqid();
$ocid=uniqid();
$odid=uniqid();
$a=$_POST[$i.'1'];
$b=$_POST[$i.'2'];
$c=$_POST[$i.'3'];
$d=$_POST[$i.'4'];
$qa=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$a','$oaid')") or die('Error61');
$qb=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$b','$obid')") or die('Error62');
$qc=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$c','$ocid')") or die('Error63');
$qd=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$d','$odid')") or die('Error64');

$e=$_POST['ans'.$i];
switch($e)
{
case 'a':
$ansid=$oaid;
break;
case 'b':
$ansid=$obid;
break;
case 'c':
$ansid=$ocid;
break;
case 'd':
$ansid=$odid;
break;
default:
$ansid=$oaid;
}
$qans=mysqli_query($con,"INSERT INTO answer VALUES  ('$qid','$ansid')");
 }
header("location:dash.php?q=1");
}
}

//quiz start
if(@$_GET['q']== 'quiz' && @$_GET['step']== 2) {
      $eid=@$_GET['eid'];
	  $tid=@$_GET['tid'];
	  $sn=@$_GET['sn'];
	  $total=@$_GET['total'];
	  $ans=$_POST['ans'];
	  $qid=@$_GET['qid'];
	     $q=mysqli_query($con,"SELECT * FROM answer WHERE qid='$qid' " );
			while($row=mysqli_fetch_array($q))
			{
			$ansid=$row['ansid'];
			}
        //fetch question marking
	    $q=mysqli_query($con,"SELECT * FROM test WHERE tid='$tid' " );
	    while($row=mysqli_fetch_array($q))
	    {
	    $qmarks=$row['qmarks'];
	    }
	   $q=mysqli_query($con,"SELECT * FROM test WHERE tid='$tid' " );
		while($row=mysqli_fetch_array($q) )
		{
		$nmarks=$row['nmarks'];
		}
         
        if(isset($_SESSION['hid'])){
            $hid=$_SESSION['hid'];
        	$q=mysqli_query($con,"SELECT * FROM history WHERE hid='$hid'");
            
		 	while($result=mysqli_fetch_array($q)){
		 		$score=$result['score'];
		 		$right=$result['sahi'];
		 		$wrong=$result['wrong'];
		 	}if(isset($_SESSION['array'][$qid])){
		 	if($_SESSION['array'][$qid]==true)
		 	{
		 		$score=$score-$qmarks;
		 		$right=$right-1;
		 		$q=mysqli_query($con,"UPDATE `history` SET `score`=$score,`sahi`=$right, date= NOW()  WHERE  hid='$hid'") or die('Error14');
		 	}else{
		 		$score=$score+$nmarks;
		        $wrong=$wrong-1;
		        $q=mysqli_query($con,"UPDATE `history` SET `score`=$score,`wrong`=$wrong, date= NOW()  WHERE   hid='$hid'")or die('Error14');
		 	}}
          }
       if(!isset($_SESSION['hid'])){
     	$_SESSION['hid']=$hid=uniqid();
     	$q=mysqli_query($con,"INSERT INTO history VALUES('$email','0','0','0','0',NOW(),'$tid','$hid')")or die('Error');}
         
         $hid=$_SESSION['hid'];
         $q=mysqli_query($con,"SELECT * FROM history WHERE  hid='$hid'")or die('Error115');

			while($row=mysqli_fetch_array($q)){
			$s=$row['score'];
			$r=$row['sahi'];
			$w=$row['wrong'];
			}

			if($ans==$ansid){
				$_SESSION['array'][$qid]=true;
				$r++;
                $s=$s+$qmarks;
                $q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`sahi`=$r, date= NOW()  WHERE   hid='$hid'")or die('Error124');
			}else{
				$_SESSION['array'][$qid]=false;
				$w++;
				$s=$s-$nmarks;
				$q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`wrong`=$w, date=NOW() WHERE  hid='$hid'")or die('Error147');
			}

		if($sn != $total)
		{
		$sn++;
		if($_SESSION['key']=='admin')
		header("location:dash.php?q=quiz&step=2&tid=$tid&eid=$eid&sn=$sn&n=$total",false)or die('Error152');
        else
		header("location:account.php?q=quiz&step=2&tid=$tid&eid=$eid&sn=$sn&n=$total",false)or die('Error152');
		}
		else if(true)
		{
		$q=mysqli_query($con,"SELECT score FROM history WHERE hid='$hid'" )or die('Error156');
		while($row=mysqli_fetch_array($q) )
		{
		$s=$row['score'];
		}
		$q=mysqli_query($con,"SELECT * FROM rank WHERE tid='$tid'" )or die('Error161');

		$rowcount=mysqli_num_rows($q);
		if($rowcount == 0)
		{
		$q2=mysqli_query($con,"INSERT INTO rank VALUES('$email','$s',NOW(),'$tid')")or die('Error165');
		}
		else
		{
		while($row=mysqli_fetch_array($q) )
		{
		$sun=$row['score'];
		}
		if(intval($sun) < intval($s) && $_SESSION['key']!='admin'){
		$q=mysqli_query($con,"UPDATE rank SET  email='$email', score='$s', time=NOW() WHERE tid= '$tid'")or die('Error174');
		}
		}
		 if($_SESSION['key']=='admin')
		 	header("location:dash.php?q=result&tid=$tid&eid=$eid");
		 else
		header("location:account.php?q=result&tid=$tid&eid=$eid");
		}
		else
		{
			 if($_SESSION['key']=='admin')
		     header("location:dash.php?q=result&eid=$eid&tid=$tid&hid=$hid");	
	         else 
			 header("location:account.php?q=result&eid=$eid&tid=$tid&hid=$hid");
		}}


?>




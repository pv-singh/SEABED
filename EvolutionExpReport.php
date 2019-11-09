<?php 
///  This file is used for experience report submission

session_start();
require 'connect.inc.php';

if(isset($_SERVER['HTTP_REFERER'])&&!empty($_SERVER['HTTP_REFERER']))
{
	$http_referer=$_SERVER['HTTP_REFERER'];
}

// Check whether the captcha is verified in EvolutionPopupForm.php file
if($_SESSION['captcha']==0)
{
   die('<span style="font-size:30px;color:red">Captcha Mismatch. Please</span><a href="'.$http_referer.'"style="font-size:30px"> try again.</a>');
}

$notify=$_SESSION['notify'];

if(isset($_FILES['report'])&&!empty($_FILES['report']))
{
	$rep='';
	$repFile_name='';
	$repFile_name=$_FILES["report"]["name"];
	$sourcePath = $_FILES['report']['tmp_name']; // Storing source path of the file in a variable
	$targetPath = "upload/".$_FILES['report']['name']; // Target path where file is to be stored
	if(move_uploaded_file($sourcePath,$targetPath))
	{
		$rep=$targetPath;  
	}
}
else   
{
	$rep='';
	$repFile_name='';
}

if(isset($_FILES['rec'])&&!empty($_FILES['rec']))
{
	$rec='';
	$recFile_name='';

	$recFile_name=$_FILES["rec"]["name"];
	$sourcePath = $_FILES['rec']['tmp_name']; // Storing source path of the file in a variable
	$targetPath = "upload/".$_FILES['rec']['name']; // Target path where file is to be stored
	if(move_uploaded_file($sourcePath,$targetPath)) 
	{
		$rec=$targetPath;  
	}
}	
else   
{
	$rec='';
	$recFile_name='';

}

if(isset($_FILES['ref'])&&!empty($_FILES['ref']))
{
	$ref='';
	$refFile_name='';

	$refFile_name=$_FILES["ref"]["name"];
	$sourcePath = $_FILES['ref']['tmp_name']; // Storing source path of the file in a variable
	$targetPath = "upload/".$_FILES['ref']['name']; // Target path where file is to be stored
	if(move_uploaded_file($sourcePath,$targetPath)) 
	{
		$ref=$targetPath;  
	}
}
else   
{
	$ref='';
	$refFile_name='';
}
    
// author details
$author_details= $_SESSION['author_details'];
$arr=$_SESSION['try'];

// counting number of authors
$count=0;
$authorCount=0;
$num=0;
foreach ($arr as $key => $value) 
{
	$count=$count+1;
	$val=$count%4;
	if($val==0)
	{   
		$authorCount=$authorCount+1;
		$email[$authorCount]=$value;
	}
	if($val==1)
	{   
		$num=$num+1;
		$author_name[$num]=$value;
	}

}

$title='';

$author_details=mysqli_real_escape_string($mysqli, $author_details);
  
Global $count;
$count=0;

date_default_timezone_set("UTC");
$actual_time= date('Y-m-d h:i:s', time());
$date1=$actual_time;

$val1=rand(10000000,99999999);
$exp=" ";

$id=$_POST['case_evolve2'];
$title='';
$rev='';
$jus='';

$query1="SELECT `title` FROM `formdata` WHERE `id` = '".$id."' ";
if ($result = mysqli_query($mysqli, $query1)) 
{
	if ($row = mysqli_fetch_row($result)) 
	{
		$title = $row[0];
	}
	mysqli_free_result($result); // Always free your resources.
}
else 
{
	die('<span style="font-size:30px;color:red">Could not connect.</span> <a href="'.$http_referer.'" style="font-size:30px">Please try again.</a>');	
}
    
$titleBeforeEscape = $title;
$title=mysqli_real_escape_string($mysqli, $title);

$query="INSERT INTO `case_revision` (`id`, `case_Id`, `revision`, `justification`, `report`, `notification`, `SubDate`, `allow`, `authorCount`, `author`) VALUES ('$val1', '$id', '$rev', '$jus', '$rep','$notify', '$date1', '0', '$authorCount', '$author_details')";
if($query_run=mysqli_query($mysqli, $query))
{
	$mailLoop='';
	$flag2=0;

	foreach ($email as $key => $value) 
	{
		if($flag2!=0) 
		{
			$mailLoop=$mailLoop.' ,'.$value;
		}
		if($flag2==0)
		{
			$mailLoop=$mailLoop.' '.$value;
			$flag2=1;
		}
		include 'CaseExperienceReportMail.php';
	}
}
else 
{
	die('<span style="font-size:30px;color:red">Could not connect.</span> <a href="'.$http_referer.'" style="font-size:30px">Please try again.</a>');
}

die('<span style="color:green;font-size:30px">Thank you for submission. Please check your mail to get the acknowledgement <br></span><a href="'.$http_referer.'" style="font-size:30px">Go back</a>');

?>
<?php

require 'connect.inc.php';

if(isset($_SERVER['HTTP_REFERER'])&&!empty($_SERVER['HTTP_REFERER']))
$http_referer=$_SERVER['HTTP_REFERER'];

Global $count;
Global $count1;
$flag=0;
$term1 = strtolower($_POST['term1']);
if (strpos($term1, "'") !== FALSE)
{
	$term1=substr($term1, 0, strpos($term1, "'"));
}

$option1=strtolower($_POST['option1']);
$term2 = strtolower($_POST['term2']);
if (strpos($term2, "'") !== FALSE)
{
	$term2=substr($term2, 0, strpos($term2, "'"));
}

$option2=strtolower($_POST['option2']);
$condition =strtolower($_POST['condition']);

if(isset($term1)&&isset($term2)&&isset($option1)&&isset($option2)&&isset($condition))
{

	if(!empty($condition)&&($condition=='and'))
	{   
		if(!empty($term1)&&!empty($term2)&&!empty($option1)&&!empty($option2))
		{
			$flag=1;
			$sql = "SELECT * FROM `formdata` WHERE allow=1 AND (`$option1` LIKE '%$term1%') AND (`$option2` LIKE '%$term2%')";
		}
		else 
		{
			die('All fields are mandatory. <a href="'.$http_referer.'">Try again </a>');	
		}	
	}
	else if(!empty($condition)&&($condition=='or'))
	{ 
		if(!empty($term1)&&!empty($term2)&&!empty($option1)&&!empty($option2))
		{
			$flag=1;
			$sql = "SELECT * FROM `formdata` WHERE allow=1 AND (`$option1` LIKE '%$term1%') OR (`$option2` LIKE '%$term2%')"; 
		}
		else if(!empty($term1)&&!empty($option1))
		{
			$flag=1;
			$sql = "SELECT * FROM `formdata` WHERE allow=1 AND `$option1` LIKE '%$term1%'";
		} 
		else if(!empty($term2)&&!empty($option2))
		{
			$flag=1;
			$sql = "SELECT * FROM `formdata` WHERE allow=1 AND `$option2` LIKE '%$term2%'";  
		}
		else
		{
			die('could not get data. <a href="'.$http_referer.'">Try again </a>');
		}
	}
}

if($flag==1)
{
	$query_run=mysqli_query($mysqli, $sql);
	if(! $query_run ) 
	{
		die('Could not get data: <a href="'.$http_referer.'">Try again </a> ');
	}
	$num=mysqli_num_rows($query_run);

	if($num==0)
	{
		mysqli_close($mysqli);
		die('no result found. <a href="'.$http_referer.'">Try again </a>');
	}

	echo "<table>";
	while($row = mysqli_fetch_assoc($query_run)) 
	{
		echo "<b>Title</b> :{".$row['title']."}  <br> ";
		echo "<b>File Name</b> : {".$row['fileName']."} <br> ".
		$path=$row['targetPath'];
		echo '<a href="'.$path.'" target="_blank">Open File</a>';
		echo '----------------------------<br>';
	}
	echo "</table> <br>";

	mysqli_close($mysqli);
	die('<a href="'.$http_referer.'">Go back</a>');  
}

function show($arr,$val)
{
	echo 'Authors: ';
	$count1=0;
	$count=0;
	foreach ($arr as $key => $value) 
	{
		$count=$count+1; 
		$val=$count%3;
		if($val==1)
		{ 
			$count1=$count1+1; 
			if($count1==$val)
			{
				echo $value ;
			}
			else
			{
				echo $value.' , ';
			}	
		}
	}
}

?>
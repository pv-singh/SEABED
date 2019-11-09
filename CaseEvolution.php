<?php 

// This file is used in case evolution section. 
// Based on the case on which user clicks, this php file retrieves data from formdata table and displays in the form of a table

if(isset($_SESSION['id'])&&!empty($_SESSION['id']))
session_destroy();

session_start();
require 'connect.inc.php';

$field="abstract";
if(isset($_POST['id'])&&!empty($_POST['id']))
{
  $id1=$_POST['id'];
  $query="SELECT * FROM `formdata` WHERE `id` LIKE '$id1'";

  if($query_run=mysqli_query($mysqli, $query))
  {
    
    $num_row=mysqli_num_rows($query_run);
  
    if($query_result=mysqli_fetch_assoc($query_run))
      {
         foreach ($query_result as $key=>$value) 
         {
                if($key=='id')
                  {
                      echo '<span style="font-size:25px">';
                      $_SESSION['id']=$query_result['id'];
                      echo '#'.$query_result['id'].' &nbsp;';
                  }
                  
                  
                if($key=='title')
               {
                   $title1 = $query_result['title'];
                   echo $title1.'</span>';
                   $_SESSION['title']=$title1;
               }
               
               if($key=='abstract')
               {
                   $abstract=$query_result['abstract'];
               }
               
               $target_path=''.$query_result['targetPath'];
               $_SESSION['path']=$query_result['targetPath'];
               
               
                if($key=='author')
                 {
                      $author=unserialize($value);
                      $length=$query_result['authorCount'];
                      $val=0;
                      echo '<br>';
                      echo str_repeat("&nbsp;", 15);
                      echo '<span style="color:#001f3f"> uploaded by ';
                     
                      while($length!=0)
                      {
                           if($length==1)
                           echo strtoupper($author[$val]).' &nbsp; ';
                           else
                            echo strtoupper($author[$val]).' ,  ';
                           $val=$val+4;
                           $length=$length-1;
                      }
               
                 }
                
                if($key=='date')
                $date=$query_result['date'];
                $_SESSION['mysqli'] = $mysqli;
               
         }          
      }
      
       echo ' on '.$date.'</span> &nbsp;';     
       //free resources
        mysqli_free_result($query_run);
  }


}
?>



<style type="text/css">
 .myPar table, .myPar td {
    border: 2px solid #3D9970;
    border-spacing: 2px;
    height:30px; 
}
</style>
  
<a href="<?php 
$result=fork(); 
echo $result;   ?>"download class="btn btn-info" style="background-color:#722040;">fork </a> 

<?php echo '<br><br><hr>'; ?>

<table align="center" class="myPar" width="570" cellpadding="0" cellspacing="0" style="margin-top:-7em; margin-right: 70px;">
<tr> 
<td style="text-align:left;">
 </td>
<br/>
</tr>

<tr>
<td ><div style="font-style: normal; font-size: 15px;height: 100px;width: 300px;text-align:left;"><b>Abstract  :</b> <br/> <?php echo $abstract; ?> <br/></div> <br/></td>
</tr>

<tr> 
<td style="text-align:left;"><a href="<?php echo $target_path; ?>" target="_blank">View Case</a> </td>
</tr>

<tr>
<td style="text-align:left;"><a href="<?php echo $target_path; ?>" target="_blank" download>download Case</a> </td>
<br/>
</tr>

<tr>
<td style="text-align:right;">
<?php
// case_revision table contains both experience report and case revision file for the given case id.
$query=" SELECT * FROM `case_revision` WHERE `case_Id`=".$_SESSION['id']." AND `allow`= 1 ORDER BY `SubDate`";
if($query_run=mysqli_query($mysqli, $query))
{
    
	$num_row=mysqli_num_rows($query_run);
	$v=1;
	$p=1;
	$e=1;
	while($query_result=mysqli_fetch_assoc($query_run))
	{
		if(!empty($query_result['revision']))
		{
			  echo '<a href="'.$query_result['revision'].' " target="_blank"> Revision'.$v.'</a>&nbsp;';
			  echo '<a href="'.$query_result['justification'].' " target="_blank"> Justification'.$v.'</a>&nbsp;';
			  $v=$v+1;
		}
		if(!empty($query_result['report']))
		{
			  echo '<a href="'.$query_result['report'].' " target="_blank"> Report'.$p.'</a>&nbsp;';
			  $p=$p+1;
		  
		}
		
		$author=unserialize($query_result['author']);
		$length=$query_result['authorCount'];
				
		$val=0;
		echo '<br>';
		echo str_repeat("&nbsp;", 15);
				 
		$firstname = "";
		while($length!=0)
		{
			 if($length==1)
			 $firstname =  $firstname.strtoupper($author[$val]).' &nbsp; ';
			 else
			 $firstname =  $firstname.strtoupper($author[$val]).' ,  ';
			 $val=$val+4;
		   
			 $length=$length-1;
		}
		  
	  echo '&nbsp; &nbsp;&nbsp;&nbsp; <span style="color:brown">uploaded by '. $firstname.' on '.$query_result['SubDate'].'</span><hr><br>';   
	}
         
}
?>
</td>
</tr>

</table>

<?php
function create_zip($files=array(), $destination, $overwrite)
{
	if(file_exists($destination)&&!$overwrite) 
	{
		return false;
	}
	else
	{
		$valid_files=array();
		if(is_array($files))
		{ 

			foreach ($files as $file) 
			{ 

				if(file_exists($file))
				{  
				$valid_files[]=$file; 
				}
			}
			if(count($valid_files))
			{
				$zip=new ZipArchive();

				if($zip->open($destination,true))
				{
					foreach ($valid_files as $file) 
					{
						$zip->addFile($file,$file);
					}
					if($zip->close())
					{
						return $destination;
					}

				}
				//$zip->open($destination,true);
			}
		}
	} 
	
}

// function for fork button 
function fork()
{
	$files_to_zip=array();
	$files_to_zip[0]=$_SESSION['path'];
	$query=" SELECT * FROM `case_revision` WHERE `case_Id`=".$_SESSION['id']." AND `allow`= 1";
	if($query_run=mysqli_query($_SESSION['mysqli'], $query))
	{
		$num_row=mysqli_num_rows($query_run);
		$i=1;
		while($query_result=mysqli_fetch_assoc($query_run))
		{
			if(!empty($query_result['revision']))
			$files_to_zip[$i++]=$query_result['revision'];

			if(!empty($query_result['report']))
			$files_to_zip[$i++]=$query_result['report'];
		}
		$result=create_zip($files_to_zip,"Archive/Case".$_SESSION['id'].".zip",true);
		return $result;

	}
}
?>

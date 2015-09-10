<html>
<head>
<title>Subdoamin Finder!</title></head></html>
<?php 

/****************************************************************************************************************
File:check.php
Project: Subdomain Finder v1.0
Author: Jagmohan Singh

*****************************************************************************************************************/
include('connect.php');
function clean($str) 
    {
    $str = @trim($str);
    if(get_magic_quotes_gpc()) 
      {
      $str = stripslashes($str);
      }
    return mysql_real_escape_string($str);
    }

echo "<br>";
$url_input=htmlentities(clean($_POST['input']));//getting input from the index.html page
error_reporting(0);
if($url_input=='') die;//script will end if input is null
/*****function to check the input url for protocols used*****/
function string_check($input)
    {
        global $check_string;
        if (preg_match('/http:\/\/www\./', $input)) 
        {
        return $check_string=1;
        }

        if (preg_match('/https:\/\/www\./', $input)) 
        {
        return $check_string=2;
        }
        if (preg_match('/ftp:\/\/www\./', $input)) {
        return $check_string=3;
        }
        if (preg_match('/http:\/\//', $input)) {
        return $check_string=4;
        }
        if (preg_match('/https:\/\//', $input)) {
        return $check_string=5;
        }
        if (preg_match('/ftp:\/\//', $input)) {
        return $check_string=6;
        }
        if (preg_match('/www\./', $input)) 
        {
        return $check_string=7;
        }
        else return $input;
    }
    /* function to get the doamin name without protocols i.e http://, https:// ftp:// etc*/
function get_string($a,$url)
    {
    if($a==1)
      {
        $return=preg_replace('/http:\/\/www\./', '', $url);   
        return $return;
      }
    if($a==2)
      {
        $return=preg_replace('/https:\/\/www\./', '', $url);    
        return $return;
      }
    if($a==3)
      {
        $return=preg_replace('/ftp:\/\/www\./', '', $url);    
        return $return;
      }
    if($a==4)
      {
        $return=preg_replace('/http:\/\//', '', $url);    
        return $return;
      }
    if($a==5)
      {
          $return=preg_replace('/https:\/\//', '', $url);   
          return $return;
      }
    if($a==6)
      {
          $return=preg_replace('/ftp:\/\//', '', $url);   
          return $return;
      }
    if($a==7)
      {
          $return=preg_replace('/www\./', '', $url);    
          return $return;  
      }
    else 
      {
        $return=$url;
        return $return;
      }
  
    }
    /*function to remove the direcotries appended to the url*/
function string_slash_replace($input)
    {
      $return=preg_replace('/\/(.*)/', '', $input);
      return $return;
    }

$b=string_check($url_input);
$string_without_protocol=get_string($b,$url_input);
$input=trim(string_slash_replace($string_without_protocol));
//echo $input;


$q=mysql_query("SELECT * FROM domain WHERE subdomain='$input'");

$q_row=mysql_fetch_array($q);
$found=$q_row['found'];
$checked =$q_row['checked'];
$lastscan=$q_row['lastscan'];
$lasttime=$q_row['lasttime'];
	echo'Last scan for <strong>'.$input.'</strong> was done on '.$lastscan.'at '.$lasttime.'<br>total number of found subdomains:'.$found;
	echo '<form method="post" action="complete_find.php">
Enter For Complete Scan <input type="hidden" name="input" value="'.$input.
	'"><input type="submit" value="Enter">
</form>';
echo '<form action="find.php" method="post">
Enter For Partial Scan <input type="hidden" name="input" value="'.$input.
  '"><input type="submit" value="Enter">
</form>';


?>
<html>
<head>
<title>Subdoamin Finder!</title></head></html>


<?php 
/****************************************************************************************************************
File:find.php
Project: Subdomain Finder v1.0
Author: Jagmohan Singh

*****************************************************************************************************************/

/***************************************************************************
Java script for ptinting the result

***************************************************************************/
echo '
<style type="css/text">
.tube{
  padding: 10px;
}
</style>
<script>function insertcode($text, $place, $replace)
{
  var $this = $text;
  var logbox = document.getElementById($place);
  if($replace == 0)
    document.getElementById($place).innerHTML = logbox.innerHTML+$this;
  else
    document.getElementById($place).innerHTML = $this;
}</script>

Verificat: <span id="verified">0</span> / <span id="total">0</span><br />

<div class="tube" id="logbox">';
function urlExists($url=NULL)
    {
        if($url == NULL) return false;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50000000000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5000000000);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode>=200 && $httpcode<400){
            return true;
        } else {
            return false;
        }
    }
/************************************************************************
show function to print the output at desired id
************************************************************************/

function show($msg, $br=1, $stop=0, $place='logbox', $replace=0) 
    {
    if($br == 1) $msg .= "<br />";
    echo "<script type=\"text/javascript\">insertcode('".$msg."', '".$place."', '".$replace."');</script>";
    if($stop == 1) exit;
    @flush();@ob_flush();
    }


/***************************************************************************
***************************************************************************/

include('connect.php');//including file to connect to database
/*********clean function to sanitize the magis quotes**************/
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
$url_input=htmlentities(clean($_POST['input']));
error_reporting(0);
if($url_input=='') die;//script will end if input is null
/*****function to check the input url*****/
$input=$url_input;
echo "<strong>subdomain of </strong>"."<strong>".$input."</strong>";
echo  "<br>";
echo 'Found subdomain <span id="found">0</span>';
echo "<br>";
$ti=time();
$t=date('g:i a', $ti);
$scandate=date("Y-m-d");
$live=urlExists($input);
if($live){
$result=mysql_query("SELECT * FROM domain WHERE subdomain='$input'");
if(mysql_num_rows($result)>0)
    {
      mysql_query("update domain set lastscan='$scandate',lasttime='$t'");
    $row=mysql_fetch_array($result);
    $domain=$row['subdomain'];
    $id=$row['id'];
    $check_subdomain="subdomain_".$id;
    $result2=mysql_query("SELECT * FROM $check_subdomain");
    $r_n=mysql_num_rows($result2);
    while($row=mysql_fetch_array($result2))
        {
        $table_name = $row['name'];
        echo '<a href="'.$table_name.'">'.$table_name.'</a>';
        echo '<br>';
        } 
    }
else 
    {
      mysql_query("INSERT INTO domain(subdomain,found,checked,lastscan,lasttime) values('$input',0,0,'$scandate','$t')");
    $result3=mysql_query("select * from domain where subdomain='$input'");
    $row3=mysql_fetch_array($result3);
    $id=$row3['id'];
    $name="subdomain_".$id;
    mysql_query("create table $name(id int not null auto_increment,name varchar(100),primary key(id))");
    }
  
$r= mysql_query("select *from domain where subdomain='$input'");
$nsn=mysql_num_rows($r);
if(mysql_num_rows($r)>0)
    {
     $r_row=mysql_fetch_array($r);
     $r_domain=$r_row['subdomain'];
     $found=$r_row['found'];
     $checked=$r_row['checked'];
     $r_domain_id=$r_row['id'];
     $domain_name="subdomain_".$r_domain_id;
     $dot=".";
     $new=mysql_query("select *from $domain_name");
     $fn=mysql_num_rows($new);
     $verificate=$checked;
     $my =mysql_query("select *from pre where id >$checked");
     $my_num= mysql_num_rows($my)+$checked;
     show($my_num, 0, 0, 'total', 1);
     while($my_row=mysql_fetch_array($my))
          {
          $string= $my_row['pre'];
          $url=trim("http://".trim($string).".".$input);
          $query=mysql_query("select *from $domain_name where name='$url' where id>$checked");
          $n=mysql_num_rows($query);
          set_time_limit(0);
          $new_url=$url;
          show($verificate, 0, 0, 'verified', 1);
          if($n<1)
            {
            $sn=urlExists($new_url);
            if($sn)
              {
               mysql_query("insert into $domain_name(name) values('$url')");
               show('<a href="'.$url.'">'.$url.'</a>', 1, 0, 'logbox', 0);
               $fn++;
              }    
            $verificate++;
            mysql_query("update domain set found='$fn',checked='$verificate' where subdomain='$input'");
            }
            else
              {

              }
            show($fn, 0, 0, 'found', 1);  
    
            }
       
    }
}  else {
    echo "domain doesn't exist!! or internet is not working";
  }
?>

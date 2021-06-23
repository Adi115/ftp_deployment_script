<?php 
ob_start(); 
set_time_limit(0); 

$sourcedir=$_POST['dir']; //"D:\axmius\img"; //this is the folder that you want to upload with all subfolder and files of it.

$ftpserver=$_POST['host']; //ftp domain name
$ftpusername=$_POST['user'];  //ftp user name 
$ftppass=$_POST['pass']; //ftp passowrd
$ftpremotedir="./"; //ftp main folder

$check_date = date('Y/m/d', strtotime($_POST['check_date']));


$ftpconnect = ftp_connect($ftpserver); 
$ftplogin = ftp_login($ftpconnect, $ftpusername, $ftppass); 

if((!$ftpconnect) || (!$ftplogin))  
{ 
  echo "cant connect!"; 
  die(); 
} 


function direction($dirname) 
{ 
  global $from,$fulldest,$ftpremotedir,$ftpconnect,$ftpremotedir,$check_date; 
  //print_r($dirname);
  chdir($dirname."//"); 
  $directory = opendir("."); 

  while($information=readdir($directory))  
  { 
    //print_r($information);
    if ($information!='.' and $information!='..' and $information!="Thumbs.db") 
    {  
        $readinfo="$dirname\\$information";

        $localfil=str_replace("".$from."//","",$dirname).""; 
        $localfold="$localfil//$information"; 
        $ftpreplacer=str_replace(array("////","//"),array("/","/"),"$ftpremotedir//".str_replace("".$fulldest."","",$dirname)."//$information"); 
        
        if (date("Y/m/d",filemtime($readinfo)) > $check_date) {
          print_r($readinfo);
            if(!is_dir($information)) 
            { 
            $loading = ftp_put($ftpconnect, $ftpreplacer, $readinfo, FTP_BINARY); 

              if (!$loading)  
              { 
                  echo "<font color=red>Files not found... Please try again...</font>"; echo "<br>";  fls(); 
              }  
              else  
              { 
                  echo "<font color=green> Please wait... Uploading files</font>"; echo "<br>"; fls(); 
              } 
            } 
            else 
            {  
              ftp_mkdir($ftpconnect, $ftpreplacer); 
              direction("$dirname//$information"); 
              chdir($dirname."//"); 
              fls(); 
            } 
      }
    } 
  } 
  closedir ($directory); 
} 

function fls() 
{ 
    ob_end_flush(); 
    ob_flush(); 
    flush(); 
    ob_start(); 
} 

$from=getcwd(); 
$fulldest="$sourcedir"; 
//print_r($fulldest);
direction($fulldest); 
ftp_close($ftpconnect); 
echo '<font color=red>Your folder is now ready for use <font color=red>'; 
?>

>
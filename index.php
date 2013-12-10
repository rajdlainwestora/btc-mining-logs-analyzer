<?php
/*

BASH SCRIPT:

curl -X POST -F "logs=`tail -10 cgminer-3.8.3/log`" http://____________________________/btc/

*/

if ($_POST) {
	$my_file = 'logs';
	$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
	$new_data = "\n".$_POST['logs'];
	echo fwrite($handle, $new_data);
	echo "\n";
}
else {

/*
$fp = fopen('logs', 'r');
fseek($fp, -1, SEEK_END); 
$pos = ftell($fp);
$lastLine = "";
// Loop backword util "\n" is found.
while((($C = fgetc($fp)) != "\n") && ($pos > 0)) {
    $lastLine = $C.$lastLine;
    fseek($fp, $pos--);
}
var_dump($lastLine);
*/

/*
$maxLength = 1;
$fp = fopen('logs', 'r');
fseek($fp, -$maxLength , SEEK_END); 
//$fewLines = explode("\n", fgets($fp, $maxLength));
//$lastLine = $fewLines[count($fewLines) - 1];
var_dump(fgets($fp, $maxLength));
//var_dump($fewLines);
*/








function read_file($file, $lines)
{
       $handle = fopen($file, "r");
       $linecounter = $lines;
       $pos = -2;
       $beginning = false;
       $text = array();
       while ($linecounter > 0) {
         $t = " ";
         while ($t != "\n") {
           if(fseek($handle, $pos, SEEK_END) == -1) {
$beginning = true; break; }
           $t = fgetc($handle);
           $pos --;
         }
         $linecounter --;
         if($beginning) rewind($handle);
         $text[$lines-$linecounter-1] = fgets($handle);
         if($beginning) break;
       }
       fclose ($handle);
       return array_reverse($text); // array_reverse is optional: you can also just return the $text array which consists of the file's lines. 
}




$lastTenLines = read_file('logs', 10);

$alert = true;
$lastLogs = "";

foreach($lastTenLines as $line) {
	$lastLogs .= $line;
	$lastLogs .= '<br />';
	if (strpos($line, 'Accepted') !== false) {
		$alert = false;
	}
}

if ($alert) {
	echo '<html><body style="background-color: #f00; color: #fff;">
	<h1>ALERT!</h1>
	<embed height="50" width="300" src="alert.mp3">
	</body></html>';
}
else {





echo '<html>
<head>
<script type="text/JavaScript">
<!--
function timedRefresh(timeoutPeriod) {
	setTimeout("location.reload(true);",timeoutPeriod);
}
//   -->
</script>
</head>
<body onload="JavaScript:timedRefresh(60000);">
<h1>Wszystko OK</h1>'.$lastLogs.'
</body>
</html>';




}

}


//$dir = __dir__."/test";
//$name = $_FILES["logFile"]["name"];
//var_dump(move_uploaded_file($name, $dir));
//echo "\n\n";
//var_dump(is_uploaded_file($name));
//echo "\n\n";

?>

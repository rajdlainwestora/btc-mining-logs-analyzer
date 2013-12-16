<?php
/*
BASH SCRIPT:
curl -X POST -F "logs=`tail -10 cgminer-3.8.3/log`" http://____________________________/btc/
*/

function read_file($file, $lines) {
	$handle = fopen($file, "r");
	$linecounter = $lines;
	$pos = -2;
	$beginning = false;
	$text = array();
	while ($linecounter > 0) {
		$t = " ";
		while ($t != "\n") {
			if(fseek($handle, $pos, SEEK_END) == -1) {
				$beginning = true; break;
			}
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

if ($_POST) {
	$my_file = 'logs';
	$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
	$new_data = "\n".$_POST['logs'];
	echo fwrite($handle, $new_data);
	echo "\n";
}
else {
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
		$template['bodyStyle'] = 'background-color: #f00; color: #fff;';
		$template['title'] = 'ALERT!';
		$template['timer'] = 300000;
	}
	else {
		$template['bodyStyle'] = '';
		$template['title'] = 'OK';
		$template['timer'] = 60000;
	}
	?>
		<html><body style="<?php echo $template['bodyStyle']?>" onload="JavaScript:setTimeout('location.reload(true);',<?php echo $template['timer']?>);">
			v.0.2
			<h1><?php echo $template['title']?></h1>
			<?php echo $lastLogs?>
			<?php if($alert):?><br /><embed height="50" width="300" src="alert.mp3"><?php endif;?>
		</body></html>
	<?php
}
?>

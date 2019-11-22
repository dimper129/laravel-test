<?php
	$uploadedfile = base64_decode($store->getQueryString('uploadedfile'));
	
	$filename = base64_decode($store->getQueryString('filename'));

	$deleteafterdownload = $store->getQueryString('deleteafterdownload');
	
	//$filename = str_replace(array(' ', "'", '"'), array('_', "\'", '\"'), $filename);
	//$my_file = 'eurostar.txt';
	//$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
	//fwrite($handle, PHP_EOL . "uploaded>>" .$uploadedfile . " filename->" . $filename . PHP_EOL );
	//fclose($handle);

	if (file_exists($uploadedfile))
	{
		$filesize = filesize($uploadedfile);
		
		header( "Pragma: public" );
		header( "Expires: 0" );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Cache-Control: public" );
		header( "Content-Type: application/force-download" );
		header( "Content-Disposition: attachment; filename=$filename;" );
		header( "Content-Transfer-Encoding: binary" );
		header( "Content-Length: $filesize" );
	
		readfile($uploadedfile);
		
		if ($deleteafterdownload)
			@unlink($uploadedfile);
	}
	else
	{
		echo '<strong>ERROR::</strong> File does not exist!<br><a href="javascript:;" onclick="self.close();">Close</a>';
	    echo $uploadedfile . '/' . $filename;
		echo '<br>';
	}
	exit;
?>
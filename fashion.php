<?php
$ftp_server='';
$ftp_user_name='';
$ftp_user_pass='';
$file = 'public_html/wordpress.zip';
$remote_file = 'http://wordpress.org/latest.zip';

// set up basic connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// upload a file
if (ftp_put($conn_id,$file, $remote_file,FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

//unzip wordpress
ftp_chmod($conn_id,0777,'./public_html');
$zip = new ZipArchive;	
	if ($zip->open('wordpress.zip') ==TRUE) {
    $zip->extractTo('./');
    $zip->close();
}

//rename dir to appear in home

$handle=opendir('./wordpress');
	while($file=readdir($handle)){
	if($file=='.'||$file=='..')continue;
	else{
	$oldname='public_html'.'/wordpress/'.$file;
	$newname='public_html'.'/'.$file;
	ftp_rename($conn_id,$oldname,$newname);
	}
}
//chmod to previous permissions
ftp_chmod($conn_id,0750,'./public_html');

// close the connection
ftp_close($conn_id);
?> 

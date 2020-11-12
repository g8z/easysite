<?php
if ( !defined( 'SMARTY_DIR' ) )
    include_once( 'init.php' );

include_once( INCLUDE_DIR . 'internal/db_items/class.Backup.php' );
include_once( INCLUDE_DIR . 'internal/db_items/class.Auto_Backup.php' );

$abackup = new Auto_Backup();

$id = $_GET['id'];

// check if $id is valid

$r = $abackup->loadCond( array(), array( 'md5( concat( id, secret_id ) )'=>$id ) );

$b = $r[0];

if ( $b ) {
	// do backup here
	$backup = new Backup();
	list( $filename, $mime_type, $data ) = $backup->backupId( $b['backup_id'] );

	// temporary save backup for attachment
	$tempFile = FULL_PATH . TEMP_DIR . '/' . $filename;
	$fp = @fopen( $tempFile, 'wb' );
	if ( !$fp )
		die( 'Unable to save temporary backup. Please check your TEMP_DIR setting in config.php and ensure that this directory is writable.' );
	@fwrite( $fp, $data, strlen( $data ) );
	@fclose( $fp );

	// send email

    require_once INCLUDE_DIR . 'class.phpmailer.php';
    $mail = new PHPMailer();

    $mail->IsMail();
    $mail->IsHTML(false);
    $mail->From = $system->settings['admin_name'];
    $mail->FromName = $system->settings['admin_email'];
    $mail->Subject = $b['subject'];
    $mail->WordWrap = 50;

	$mail->AddAddress( $b['email'] );

   	$mail->AddAttachment( $tempFile, $filename );

    $mail->Body = $b['message'];

    $result = $mail->Send();

    @unlink( $tempFile );

    if ( !$result )
    	die( 'There was an error while trying to send the backup notification e-mail.' );

}
else {
	die( 'Invalid Id' );
}

?>
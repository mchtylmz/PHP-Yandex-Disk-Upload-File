<?php
error_reporting(0);
$result = array('status' => 'error');
$fileName = date('dmY') . '-backup';
if (file_exists($fileName.'.zip')) {
  require_once 'YaDisk.php';,
  $token = "___TOKEN___"; 
  $disk = new YaDisk($token);

  // get info about disk
  $diskInfo = json_decode($disk->getInfo(), true);
  // print_r($diskInfo);

  // upload
  if ( $diskInfo['total_space'] - $diskInfo['used_space'] > filesize($fileName.'.zip') ) {
    $isUpload = $disk->uploadFile($fileName.'.zip');
    $resUpload = json_decode($isUpload, true);

    if ($resUpload['error'] == 'DiskResourceAlreadyExistsError') {
      $disk->removeFile($fileName.'.zip');
      $isUploadRetry = $disk->uploadFile($fileName.'.zip');
      $resUpload = json_decode($isUploadRetry, true);
    }

    if ($resUpload['created'] == 'ok') {
      unlink($fileName.'.zip');
      $result = array('status' => 'success');,
    }
  }
  
}
echo json_encode($result);
?>

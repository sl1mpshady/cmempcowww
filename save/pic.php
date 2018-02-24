<?php
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
$data = array();
$fileName = $_FILES['file']['name'];
$tmpName  = $_FILES['file']['tmp_name'];
$fileSize = $_FILES['file']['size'];
$fileType = $_FILES['file']['type'];
if ($fileName){
    $fp      = fopen($tmpName, 'r+');
    $content = fread($fp, filesize($tmpName)); //reads $fp, to end of file length
    
    fclose($fp);
    $ext = get_file_extension(stripslashes($fileName));
    $ext = strtolower($ext);
    if( $ext == "jpg" || $ext == "jpeg" )
        $source = imagecreatefromjpeg( $tmpName );
    else if( $ext == "png" )
        $source = imagecreatefrompng( $tmpName );
    else
        $source = imagecreatefromgif( $tmpName );

    list( $width, $height) = getimagesize( $tmpName );          
    $ratio = $height / $width;

    $nw = 144;
    $nh = ceil( $ratio * $nw );
    $thumb = imagecreatetruecolor( $nw, $nw );

    imagecopyresampled( $thumb, $source, 0, 0, 0,0, $nw, $nw, $width, $height );
    $picname = 'photos_'.uniqid(mt_rand(10, 15)).'_'.time().'.'.$ext;
    imagejpeg($thumb, $picname, 100);  //imagejpeg($resampled, $fileName, $quality);            
    $instr = fopen($picname,"rb");  //need to move this to a safe directory
    $image = addslashes(fread($instr,filesize($picname)));                        
    $query = mysql_query("UPDATE customer SET custPicture='$image' WHERE custID='".$_GET['i']."'") or (mysql_error());
    fclose($instr);
    imagedestroy($thumb);
    unlink($picname);
    $data[]=array($query);
    echo json_encode($data);
}
function get_file_extension( $file )  {
    if( empty( $file ) )
        return;
    $ext = end(explode( ".", $file ));
    return $ext;
}
?>
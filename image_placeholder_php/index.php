<?php
defined('DS') or define('DS',DIRECTORY_SEPARATOR);
?>
<style type="text/css">
    input[type="text"]{
        width: 600px;
    }
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Convert image to image placeholder</title>
</head>

<body>
<p>Input info require before convert</p>
<form method="post" action="">
<table>
    <tr>
        <td>Folder images (*)</td>
        <td><input type="text" name="image" placeholder="Folder images to convert" value="<?php echo isset($_POST['image'])?$_POST['image']:dirname(__FILE__).DS.'images' ?>" /></td>
    </tr>
    <tr>
        <td>Folder image placeholder (*)</td>
        <td><input type="text" name="image_new" placeholder="Folder convert images" value="<?php echo isset($_POST['image_new'])?$_POST['image_new']:dirname(__FILE__).DS.'images_new' ?>" /></td>
    </tr>
    <tr>
        <td>Using font(*)</td>
        <td>
            <select name="font">
                <option value="Crysta" selected>Crysta</option>
                <option value="Tahoma">Tahoma</option>
            </select>
        </td>
    </tr>
    
    <tr>
        <td></td>
        <td><input type="submit" value="Export"/></td>
    </tr>
</table>

</form>
</body>
</html>

<?php

ini_set('max_execution_time', 0);
$path_old = $_POST['image']; 
$path_new = $_POST['image_new'];
$level = 5;
if(empty($_POST) || !file_exists($path_old)){
    if (!file_exists($path_old) && !is_dir($path_old)) {
	echo '<p style="color:red;">This file exists!</p>';
        return;
    }
    echo '<p style="color:red;">Input full information required</p>';
    return;
}

if (!file_exists($path_new) && !is_dir($path_newv)) {
    mkdir($path_new);         
}

$backgroud = "cccccc"; /* color for backgroud image */
$color = "969696"; /* color for text */
$list = list_files($path_old, $level);
$images = preg_grep('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $list);

foreach ($images as $key => $value) {
	$real_path = substr($value, strlen($path_old) + 1);
	$arr = explode('/', $real_path);
	$filename = array_pop($arr);
	$p = $path_new;
	foreach ($arr as $a){
		$p .= '/'.$a;
		if(is_dir($p)) continue;
		else mkdir($p, 0755);
	}	
	$path = $path_new.'/'.implode('/', $arr);
    $size = getimagesize($value);
    if(is_array($size)){
		print_r($key);
        create_image($size[0], $size[1], $backgroud, $color,$path,$filename);    
    }
}
function list_files( $folder = '', $levels = 100 ) {
	if ( empty($folder) )
		return false;

	if ( ! $levels )
		return false;

	$files = array();
	if ( $dir = @opendir( $folder ) ) {
		while (($file = readdir( $dir ) ) !== false ) {
			if ( in_array($file, array('.', '..') ) )
				continue;
			if ( is_dir( $folder . '/' . $file ) ) {
				$files2 = list_files( $folder . '/' . $file, $levels - 1);
				if ( $files2 )
					$files = array_merge($files, $files2 );
				else
					$files[] = $folder . '/' . $file . '/';
			} else {
				$files[] = $folder . '/' . $file;
			}
		}
	}
	@closedir( $dir );
	return $files;
}
//Function that has all the magic
function create_image($width, $height, $bg_color, $txt_color, $path, $filename) {
    $font = 'Crysta';
    if(isset($_POST['font'])){
        $font = $_POST['font'];
    }
            
    //Define the text to show
    $text = "$width X $height";

    //Create the image resource 
    $image = ImageCreate($width, $height);

    //We are making two colors one for BackGround and one for ForGround
    $bg_color = ImageColorAllocate($image, base_convert(substr($bg_color, 0, 2), 16, 10), base_convert(substr($bg_color, 2, 2), 16, 10), base_convert(substr($bg_color, 4, 2), 16, 10));

    $txt_color = ImageColorAllocate($image, base_convert(substr($txt_color, 0, 2), 16, 10), base_convert(substr($txt_color, 2, 2), 16, 10), base_convert(substr($txt_color, 4, 2), 16, 10));

    //Fill the background color 
    ImageFill($image, 0, 0, $bg_color);

    //Calculating (Actually astimationg :) ) font size
    $fontsize = ($width > $height) ? ($height / 10) : ($width / 10);

    //Write the text .. with some alignment astimations
    imagettftext($image, $fontsize, 0, ($width / 2) - ($fontsize * 2.75), ($height / 2) + ($fontsize * 0.2), $txt_color, $font.'.ttf', $text);

    //Tell the browser what kind of file is come in 
    header("Content-Type: image/png");

    //Output the newly created image in png format 
    //imagepng($image);
    imagepng($image, $path.'/'.$filename);

    //Free up resources
    ImageDestroy($image);
}
?>

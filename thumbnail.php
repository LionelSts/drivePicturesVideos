<?php

function createThumbnail($path, $dest, $Width, $Height){
    $image = imagecreatefrompng($path);
    $imgWidth = imagesx($image);
    $imgHeight = imagesy($image);
    /*
    if($Height == null){
        $ratio = $imgWidth / $imgHeight;
        if ($imgWidth > $imgHeight){
            $Height = floor($Width / $ratio);
        }
        else{
            $Height = $Width;
            $Width = floor($Width * $ratio);
        }
    }*/
    $dest_x=0;
    $dest_y=0;
    $src_x=0;
    $src_y=0;
    //resize image
    if($imgWidth/297 > $imgHeight/197){

    }else if($imgWidth/297 < $imgHeight/197){

    }else{

    }

    $thumbnail = imagecreatetruecolor($Width, $Height);
    imagecolortransparent($thumbnail,imagecolorallocate($thumbnail, 0, 0, 0));
    imagealphablending($thumbnail, false);
    imagesavealpha($thumbnail, true);
    imagecopyresampled(
        $thumbnail,
        $image,
        0, 0, 0, 0,
        $Width, $Height,
        $imgWidth, $imgHeight
    );

    return imagepng($thumbnail, $dest, 0);
}

?>

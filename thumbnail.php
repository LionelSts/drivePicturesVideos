<?php

function createThumbnail($path, $dest, $Width, $Height){
    $resize_Width=$Width;
    $resize_Height=$Height;

    $image = imagecreatefrompng($path);
    $imgWidth = imagesx($image);
    $imgHeight = imagesy($image);
    $ratio = $imgWidth / $imgHeight;

    $dest_x=0;
    $dest_y=0;
    $src_x=0;
    $src_y=0;


    //resize image
    if($imgWidth<=$imgHeight){
        $resize_Width=floor($Height*$ratio);
        $dest_x = intval(($Width - $resize_Width) / 2);
    }
    elseif($imgWidth>$imgHeight){
        $resize_Height=floor($Width/$ratio);
        $dest_y = intval(($Height - $resize_Height) / 2);
    }
    $thumbnail = imagecreatetruecolor($Width, $Height);
    imagealphablending( $thumbnail, false );
    imagesavealpha( $thumbnail, true );
    imagecopyresampled(
        $thumbnail,
        $image,
        $dest_x, $dest_y, 0, 0,
        $resize_Width, $resize_Height,
        $imgWidth, $imgHeight
    );

    return imagepng($thumbnail, $dest, 9);
}

<?php
// Fonction de cadrage de la miniature
function createThumbnail($path, $dest, $Width, $Height): bool
{
    $resize_Width=$Width;
    $resize_Height=$Height;

    $image = imagecreatefrompng($path);
    $imgWidth = imagesx($image);
    $imgHeight = imagesy($image);
    $ratio = $imgWidth / $imgHeight;

    $dest_x=0;
    $dest_y=0;

    // Redimensionnement de l'image
    if($imgWidth<=$imgHeight){
        $resize_Width=floor($Height*$ratio);
        $dest_x = intval(($Width - $resize_Width) / 2);
    }
    else {
        $resize_Height=floor($Width/$ratio);
        $dest_y = intval(($Height - $resize_Height) / 2);
    }
    $thumbnail = imagecreatetruecolor($Width, $Height);                                                                 // On créé une nouvelle image
    imagealphablending( $thumbnail, false );
    imagesavealpha( $thumbnail, true );
    imagecopyresampled(                                                                                                 // On colle l'ancienne image dessus et au centre
        $thumbnail,
        $image,
        $dest_x, $dest_y, 0, 0,
        $resize_Width, $resize_Height,
        $imgWidth, $imgHeight
    );

    return imagepng($thumbnail, $dest, 9);
}

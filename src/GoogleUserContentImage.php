<?php
require_once './lib/wideimage/lib/WideImage.php';

class GoogleUserContentImage {
    public static function isGoogleUserContentImage($imgURI) {
        // Assume it is Google user content which may be rate limited
        $result = TRUE;

        // Disqualifying conditions -
        // Leaving this open to add more as may be needed

        // Does not begin with https
        if (strpos($imgURI, 'https://') !== 0) {
            $result = FALSE;
        }

        // Does not contain .googleusercontent. somewhere in the middle of the URI
         if (!strpos($imgURI, '.googleusercontent.')) {
             $result = FALSE;
         }
        return $result;
    }



    public static function downloadImg($imgURI, $destinationImgFile) {
        // Note: DestinationImgFile must end in a valid image type like '.jpeg'
        //       or results may be unpredictable.

        // Make sure there is no $destinationImgFile or fail
        if ( file_exists($destinationImgFile) ) {
            unlink($destinationImgFile);
        }
        if ( file_exists($destinationImgFile) ) {
            return FALSE;
        }

        // Retrieve a valid image class
        $image = WideImage::loadFromFile($imgURI);
        if ( !($image instanceof WideImage_PaletteImage) || !($image instanceof WideImage_TrueColorImage) ) {
           return FALSE;
        }

        // Make sure file is saved
        $image->saveToFile($destinationImgFile);
        if ( file_exists($destinationImgFile) ) {
            return TRUE;
        }

        return FALSE;
    }
}


?>

<?php
/**
* TO DO: Correct format of this comment
*
* Support img tags imbedded in JSON files by:
*   Downloading Google User Content and saving in an image folder
*   Replacing generic folder names with actual image folder
*
**/
class ImageFile {
    public static function parse(&$input, $delimeter, $returnDelimiter = FALSE) {
        $pos = strpos($input, $delimeter);
        if ($pos === FALSE) {
            $result = $input;
            $input = '';
        } else {
            if ($returnDelimiter) {
                $pos += strlen($delimeter);
            }
            $result = substr($input, 0, $pos);
            $input = substr($input, $pos);
        }
        return $result;
    }

    public static function parseImgSrc($input) {

        $output = [];

        // Parse for ...<img...src=\" https...googleusercontent.com... \" >...
        // https...googleusercontent.com... is downloadable
        // everything else is content

        while ($input) {
            $content = '';

            // Ignore through <img and add to general content
            $content .= self::parse($input, '<img');

            // Get any possible contents after <imb
            $imgTagContents = self::parse($input, '>');

            // If image end tag not found
            if (!$input) {
                $content .= $imgTagContents;
                $imgTagContents = '';
            }

            // Get image tag part through src=\" and add to general content
            $content .= self::parse($imgTagContents, 'src=\"', TRUE);

            // Get image URI
            $imgURI = self::parse($imgTagContents, '\"');

            // If no end delimiter, add result to general contents
            if (!$imgTagContents) {
                $content .= $imgURI;
                $imgURI = '';
            }

            // Save contents not part of an image URI
            $output[] = (object) ['imgURI' => '', 'contents' => $contents];

            // Save image URI
            if ($imgURI) {
                $output[] = (object) ['imgURI' => $contents, 'contents' => ''];
                $input = $imgTagContents . $input;
            }
        }
        return $output;
    }

    static function replaceGenericImgFolder() {

    }
}

?>

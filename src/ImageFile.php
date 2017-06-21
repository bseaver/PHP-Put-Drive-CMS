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
    static function parseImgSrc($input) {

        function parse(&$input, $onWhat, $includeOnWhat = FALSE) {
            $pos = strpos($input, $onWhat);
            if ($pos === FALSE) {
                return '';
            }
            if (!$includeOnWhat) {
                $pos += strlen($onWhat);
            }
            $result = substr($input, $pos);
            $input = substr($input, 0, $pos);
            return $result;
        }

        $output = [];

        // Parse for ...<img ... src=\" https...googleusercontent.com... \" >...
        // https...googleusercontent.com... is downloadable
        // everything else is content

        while ($input) {
            $content = '';
            $downloadable = FALSE;

            $beginImgTag = parse($input, '<img');
            if (!$beginImgTag) {
                $content .= $input;
                $input = '';
            }

            $imgTagContents = parse($beginImgTag, '>');
            if (!$imgTagContents) {
                $content .= $input . $beginImgTag;
                $input = '';
            }

            $imgSrc = parse($imgTagContents, 'src=\"');
            if (!$imgSrc) {
                $content .= $input . $beginImgTag . $imgTagContents;
                $input = '';
            }

            $theRest = parse($imgSrc, '\"', TRUE);
            if (!$theRest) {
                $content .= $input . $beginImgTag . $imgTagContents . $imgSrc;
                $input = '';
            } else {
                $input = $theRest;
                $content = $imgSrc;
            }

            $output[] = $content;
        }
        return $output;
    }

    static function replaceGenericImgFolder() {

    }
}

?>

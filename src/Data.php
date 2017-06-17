<?php
class Data {
    public $data;

    public function __construct($file) {
        $contents = file_get_contents($file);
        $this->data = json_decode($contents);
    }

    // Due to inconsistent data entry in the Beer Description,
    // We may need to create our own header with the beer's name
    public function missingBeerHeader($id) {
        // Grab name and description fields
        $arrayIndex = $id - 1;
        $name        = $this->data->Brews[$arrayIndex]->Name;
        $description = $this->data->Brews[$arrayIndex]->Description;

        // Remove all non alpha characters and set to lower case
        $name = strtolower( preg_replace("/[^[:alnum:]]/u", '', $name) );
        $description = strtolower( preg_replace("/[^[:alnum:]]/u", '', $description) );

        // Look for span around name in the description
        // if none then we have a missing beer header
        return strpos($description, 'span'.$name.'span') === FALSE;
    }
}

?>

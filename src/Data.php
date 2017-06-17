<?php
class Data {
    public $data;

    public function __construct($file) {
        $contents = file_get_contents($file);
        $this->data = json_decode($contents);

        // print_r($this->data['Breweries'][..]); // Array
        //print_r($this->data->Breweries[0]->City); // Object
    }

    public function getBreweries()
    {
        return $this->data->Breweries;
    }
}

?>

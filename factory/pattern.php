<?php


//The Factory Design Pattern provides a simple interface to acquire a new instance of an object,
//while sheltering the calling code from the steps to determine which base class is actually
//instantiated.


class CD
{
    public $title = '';
    public $band = '';
    public $tracks = array();

    public function __construct()
    {}

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setBand($band)
    {
        $this->band = $band;
    }

    public function addTrack($track)
    {
        $this->tracks[] = $track;
    }
}
$title = 'Waste of a Rib';
$band = 'Never Again';
$tracksFromExternalSource = array('What It Means', 'Brrr', 'Goodbye');

$cd = new CD();
$cd->setTitle($title);
$cd->setBand($band);
foreach ($tracksFromExternalSource as $track) {
    $cd->addTrack($track);
}


class enhancedCD
{
    public $title = '';
    public $band = '';
    public $tracks = array();

    public function __construct()
    {
        $this->tracks[] = 'DATA TRACK';
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setBand($band)
    {
        $this->band = $band;
    }

    public function addTrack($track)
    {
        $this->tracks[] = $track;
    }
}

class CDFactory
{
    public static function create($type)
    {
        $class = strtolower($type) . "CD";
             
        return new $class;
    }
}

$type = 'enhanced';
             
$cd = CDFactory::create($type);
$cd->setBand($band);
$cd->setTitle($title);
foreach ($tracksFromExternalSource as $track) {
    $cd->addTrack($track);
} 


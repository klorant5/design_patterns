<?php


//The Decorator Design Pattern is best suited for altering or decorating portions of an existing
//object’s content or functionality without modifying the structure of the original object.

//When requirements are introduced that require small changes to the content or functionality of an
//application without compromising the stability of the existing code base, it is best practice to create a
//Decorator object.

//To make small modifications to content or functionality of existing objects without modifying their
//structure, the Decorator Design Pattern should be used.

class CD
{

    public $trackList;

    public function __construct()
    {
        $this->trackList = array();
    }

    public function addTrack($track)
    {
        $this->trackList[] = $track;
    }

    public function getTrackList()
    {
        $output = '';

        foreach ($this->trackList as $num => $track) {
            $output .= ($num + 1) . ") {$track}. ";
        }

        return $output;
    }
}


$tracksFromExternalSource = array('What It Means', 'Brr', 'Goodbye');

$myCD = new CD();

foreach ($tracksFromExternalSource as $track) {
    $myCD->addTrack($track);
}

print "The CD contains: " . $myCD->getTrackList();

//egy kis módosítás: minden track a kimeneten csupa nagy betűvel kell megjelenjen

class CDTrackListDecoratorCaps
{
    private $__cd;

    public function __construct(CD $cd)
    {
        $this->__cd = $cd;
    }

    public function makeCaps()
    {
        foreach ($this->__cd->trackList as &$track) {
            $track = strtoupper($track);
        }
    }
}

//-----
$myCD = new CD();

foreach ($tracksFromExternalSource as $track) {
    $myCD->addTrack($track);
}

$myCDCaps = new CDTrackListDecoratorCaps($myCD);
$myCDCaps->makeCaps();

print "The CD contains the following tracks: " . $myCD->getTrackList();
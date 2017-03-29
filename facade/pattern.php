<?php

//The Façade Design Pattern hides complexity from a calling object by creating a simple façade
//interface in front of the collection of required logic and methods.

//The website passes its inventory to a different system in the company nightly as part of a required audit.
//This other system will accept the request via a post to its web service. It is an older system, however, and
//works with only uppercase strings. The code needs to acquire   CD  objects, apply uppercase to all their
//properties, and create a well-formed XML document to be posted to the web service.

//!!!(magyarul: A bemenet CD obj aminek minden property-je nagybetűs kell legyen, de nem így kapja meg. majd az egészet
//egy XML-be kell írni, hogy elküldhessük a web servicenek)
//The following is a simple example of a CD class:
class CD
{
    public $tracks = array();
    public $band = '';
    public $title = '';

    public function __construct($title, $band, $tracks)
    {
        $this->title = $title;
        $this->band = $band;
        $this->tracks = $tracks;
    }
}

//When a new   CD  is instantiated, the constructor adds the title, band, and track list to the   CD  object. To
//build the   CD  object, the steps are pretty simple:
$tracksFromExternalSource = array('Shut Up', 'The Rock Show', 'First Date', 'Roller Coaster');
$title = 'Take off your pants and jacket';
$band = 'Blink 182';

$cd = new CD($title, $band, $tracksFromExternalSource);


class CDUpperCase
{
    public static function makeString(CD $cd, $type)
    {
        $cd->$type = strtoupper($cd->$type);
    }

    public static function makeArray(CD $cd, $type)
    {
        $cd->$type = array_map('strtoupper', $cd->$type);
    }
}

class CDMakeXML
{
    public static function create(CD $cd)
    {
        $doc = new DomDocument();

        $root = $doc->createElement('CD');
        $root = $doc->appendChild($root);

        $title = $doc->createElement('TITLE', $cd->title);
        $title = $root->appendChild($title);

        $band = $doc->createElement('BAND', $cd->band);
        $band = $root->appendChild($band);

        $tracks = $doc->createElement('TRACKS');
        $tracks = $root->appendChild($tracks);

        foreach ($cd->tracks as $track) {
            $track = $doc->createElement('TRACK', $track);
            $track = $tracks->appendChild($track);
        }

        return $doc->saveXML();
    }
}

class WebServiceFacade
{
    /**
     * több komplex lépést valósít meg egy függvényben. csak ezt kell meghívni nem a benne lévő műveleteket
     * @param CD $cd
     * @return string
     */
    public static function makeXMLCall(CD $cd)
    {
        CDUpperCase::makeString($cd, 'title');
        CDUpperCase::makeString($cd, 'band');
        CDUpperCase::makeArray($cd, 'tracks');

        $xml = CDMakeXML::create($cd);

        return $xml;
    }
}

print WebServiceFacade::makeXMLCall($cd);
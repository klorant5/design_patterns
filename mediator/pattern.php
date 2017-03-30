<?php
//Mediator = közvetítő

//The Mediator Design Pattern is used to develop an object that communicates or mediates
//changes to a collection of similar objects without them interacting with each other directly.


//When changes to a source object should be communicated to other related but uncoupled objects, an
//object based on the Mediator Design Pattern should be used to manage the updates.

//The example website allows bands to come in and manage their music collection. It also allows them to
//update their profile, change information about their band, and update their CD information. Recently,
//artists were allowed to upload a collection of MP3s as well as ship CDs from the website. Because of this,
//the website needs to keep CDs and their MP3 counterparts in sync with each other.
//The initial version of the website allowed the band to change its band name from the profile page or
//from an individual CD itself. The   CD  object had a method that would accept the band change and update
//it in the database:
class CD
{
    public $band = '';
    public $title = '';

    public function save()
    {
        //stub - writes data back to database - use this to verify
        var_dump($this);
    }

    public function changeBandName($newName)
    {
        $this->band = $newName;
        $this->save();
    }
}

//With the addition of our MP3 archive, another similar object needs to be created to work with that
//archive. The artist must also be able to change their band name on the MP3 archive page. The band name
//must also then change in the CD that is associated with it.

//The Mediator Design Pattern should now be used. First, the   CD  class is modified in order to take
//advantage of this. Then, the MP3 archive class is created similarly:
class CD
{
    public $band = '';
    public $title = '';
    protected $_mediator;

    public function __construct($mediator = null)
    {
        $this->_mediator = $mediator;
    }

    public function save()
    {
        //stub - writes data back to database - use this to verify
        var_dump($this);
    }

    public function changeBandName($newName)
    {
        if (!is_null($this->_mediator)) {
            $this->_mediator->change($this, array('band' => $newName));
        }
        $this->band = $newName;
        $this->save();
    }
}

class MP3Archive
{
    public $band = '';
    public $title = '';
    protected $_mediator;

    public function __construct($mediator = null)
    {
        $this->_mediator = $mediator;
    }

    public function save()
    {
        //stub - writes data back to database - use this to verify
        var_dump($this);
    }

    public function changeBandName($newName)
    {
        if (!is_null($this->_mediator)) {
            $this->_mediator->change($this, array('band' => $newName));
        }
        $this->band = $newName;
        $this->save();
    }
}

class MusicContainerMediator
{
    protected $_containers = array();

    public function __construct()
    {
        $this->_containers[] = 'CD';
        $this->_containers[] = 'MP3Archive';
    }

    public function change($originalObject, $newValue)
    {
        $title = $originalObject->title;
        $band = $originalObject->band;

        foreach ($this->_containers as $container) {
            if (!($originalObject instanceof $container)) {     //Pl: ha $originalObject nem CD akkor
                                                                // hozza létre a CD-t és mentse el bele az adatokat
                $object = new $container;
                $object->title = $title;
                $object->band = $band;

                foreach ($newValue as $key => $val) {
                    $object->$key = $val;
                }

                $object->save();
            }
        }
    }
} 

//usage:
$titleFromDB = 'Waste of a Rib';
$bandFromDB = 'Never Again';
             
$mediator = new MusicContainerMediator();
$cd = new CD($mediator);
$cd->title = $titleFromDB;
$cd->band = $bandFromDB;
             
$cd->changeBandName('Maybe Once More');

//When working with uncoupled objects that have similar properties that need to stay in sync, using an
//object based on the Mediator Design Pattern is best practice.
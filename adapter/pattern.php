<?php


//használat
//régi kódok bővítése funkciókkal,

//régi osztály nem kompatibilis az őt használó új osztállyal

//------------------------------------------------------------------------------------
//példa 1: logToConsole osztály logolja az errorObjectet amiben a $__errorban van a hibaüzi a kóddal és a szöveggel együtt


class errorObject
{
    private $__error;

    public function __construct($error)
    {
        $this->__error = $error;
    }

    public function getError()
    {
        return $this->__error;
    }
}

class logToConsole
{
    private $__errorObject;

    public function __construct($errorObject)
    {
        $this->__errorObject = $errorObject;
    }

    public function write()
    {
        fwrite(STDERR, $this->__errorObject->getError());
    }
}

/** create the new 404 error object **/
$error = new errorObject("404:Not Found");

/** write the error to the console **/
$log = new logToConsole($error);
$log->write();

//------------------------------------------------------------------------------------
//probléma/feladat: át kellene írni a kódot úgy hogy CSV fájlba logoljuk a hibákat: 1. oszlop: kód, 2. oszlop: üzenet
//MERT a logToCSV egy más formátumú errorObjectet vár.
//ennek a megoldása a meglévő errorObject módosítása nélkül Adapter-rel a következő:

class logToCSV
{
    const CSV_LOCATION = 'log.csv';

    private $__errorObject;

    public function __construct($errorObject)
    {
        $this->__errorObject = $errorObject;
    }

    public function write()
    {
        $line = $this->__errorObject->getErrorNumber();
        $line .= ',';
        $line .= $this->__errorObject->getErrorText();
        $line .= "\n";

        file_put_contents(self::CSV_LOCATION, $line, FILE_APPEND);
    }
}

class logToCSVAdapter extends errorObject
{
    private $__errorNumber, $__errorText;

    public function __construct($error)
    {
        parent::__construct($error);

        $parts = explode(':', $this->getError());

        $this->__errorNumber = $parts[0];
        $this->__errorText = $parts[1];
    }

    public function getErrorNumber() {
        return $this->__errorNumber;
    }

    public function getErrorText()
    {
        return $this->__errorText;
    }
}

/** create the new 404 error object adapted for csv **/
$error = new logToCSVAdapter("404:Not Found");

/** write the error to the csv file **/
$log = new logToCSV($error);
$log->write();
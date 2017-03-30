<?php

//The Iterator Design Pattern helps construct objects that can provide a single standard interface
//to loop or iterate through any type of countable data.

class CD
{
    public $band = '';
    public $title = '';
    public $trackList = array();

    public function __construct($band, $title)
    {
        $this->band = $band;
        $this->title = $title;
    }

    public function addTrack($track)
    {
        $this->trackList[] = $track;
    }
}

class CDSearchByBandIterator implements Iterator
{
    private $__CDs = array();
    private $__valid = false;

    public function __construct($bandName)
    {
        $db = mysql_connect('localhost', 'user', 'pass');
        mysql_select_db('test');

        $sql = "select CD.id, CD.band, CD.title, tracks.tracknum, ";
        $sql = "tracks.title as tracktitle ";
        $sql .= "from CD left join tracks on CD.id=tracks.cid where band='";
        $sql .= mysql_real_escape_string($bandName);
        $sql .= "' order by tracks.tracknum";
        $results = mysql_query($sql);

        $cdID = 0;
        $cd = null;

        while ($result = mysql_fetch_array($results)) {
            if ($result['id'] !== $cdID) {
                if (!is_null($cd)) {
                    $this->__CDs[] = $cd;
                }
                $cdID = $result['id'];
                $cd = new CD($result['band'], $result['title']);
            }

            $cd->addTrack($result['tracktitle']);
        }

        $this->__CDs[] = $cd;
    }

    public function next()
    {
        $this->__valid = (next($this->__CDs) === false) ? false : true;
    }

    public function rewind()
    {
        $this->__valid = (reset($this->__CDs) === false) ? false : true;
    }

    public function valid()
    {
        return $this->__valid;
    }

    public function current()
    {
        return current($this->__CDs);
    }

    public function key()
    {
        return key($this->__CDs);
    }
}

$queryItem = 'Never Again';
             
$cds = new CDSearchByBandIterator($queryItem);
             
print '<h1> Found the Following CDs </h1>';
print '<table><tr><th>Band</th><th>Title</th><th> Num Tracks </th></tr>';
foreach ($cds as $cd) {
    print "<tr><td>{$cd->band}</td><td>{$cd->title}</td><td>";
    print count($cd-> trackList) . ' </td></tr>';
}
print '</table>'; 
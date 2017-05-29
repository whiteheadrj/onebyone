<?php

class DistanceManager
{
    const PATH = '/var/www/localhost/data/distances.json';

    private $distances = array();
    public function __construct()
    {
        $this->loadDistances();
    }

    private function loadDistances()
    {
        if (file_exists(self::PATH)) {
            $jsonString = file_get_contents(self::PATH);
            if ($jsonString !== false) {
                $this->distances = json_decode($jsonString, true);
            }
        }
    }

    public function getSortedDistancesForID($id)
    {
        $dArray = array();

        foreach ($this->distances as $key => $d) {
            if (stripos($key, $id) !== false) {
                $otherID = $this->getOtherIDFromKey($key, $id);
                $dArray[$otherID] = $d;
            }
        }
        asort($dArray);

        return $dArray;
    }

    private function getOtherIDFromKey($key, $id)
    {
        $array = explode('-', $key);
        foreach ($array as $v) {
            if ($v != $id) {
                return $v;
            }
        }
    }
}

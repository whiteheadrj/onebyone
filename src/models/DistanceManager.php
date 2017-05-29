<?php

class DistanceManager
{
    const PATH = '../data/distances.json';

    private $distances = array();
    public function __construct()
    {
        $this->loadDistances();
    }

    private function getFullPath($path)
    {
        return __DIR__.'/'.$path;
    }

    private function loadDistances()
    {
        if (file_exists($this->getFullPath(self::PATH))) {
            $jsonString = file_get_contents($this->getFullPath(self::PATH));
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

    public function getSortedAvgDistanceFromComp($companions, $teachees)
    {
        $sorted = array();
        foreach ($teachees as $id) {
            $sorted[$id] = $this->computeAverage($companions, $id);
        }
        asort($sorted);

        return $sorted;
    }

    private function computeAverage($companions, $id)
    {
        $count = 0;
        $total = 0;
        foreach ($companions as $cID) {
            $value = $this->getDistanceBetweenIDs($id, $cID);
            if ($value !== null) {
                ++$count;
                $total += $value;
            }
        }
        if ($count === 0) {
            return 'NA';
        }

        return $total / $count;
    }

    public function getDistanceBetweenIDs($id1, $id2)
    {
        if (array_key_exists($id1.'-'.$id2, $this->distances)) {
            return $this->distances[$id1.'-'.$id2];
        } elseif (array_key_exists($id2.'-'.$id1, $this->distances)) {
            return $this->distances[$id2.'-'.$id1];
        }

        return null;
    }
}

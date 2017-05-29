<?php

class CompanionshipManager
{
    const PATH = '/var/www/localhost/data/companionships.json';

    private $companionships = array();
    public function __construct()
    {
        $this->load();
    }

    private function load()
    {
        if (file_exists(self::PATH)) {
            $jsonString = file_get_contents(self::PATH);
            if ($jsonString !== false) {
                $this->companionships = json_decode($jsonString, true);
            }
        }
    }

    public function addToCompanionship($id1, $id2)
    {
        $key = $this->getCompanionshipIDByIndividualID($id1);
        $key2 = $this->getCompanionshipIDByIndividualID($id2);
        if (!empty($key) && !empty($key2) && $key == $key2) {
            return;
        } elseif (empty($key) && empty($key2)) {
            $this->createNewCompanionship($id1, $id2);
        } elseif (!empty($key)) {
            $this->addToCompanionshipByCompID($key, $id2);
        } elseif (!empty($key2)) {
            $this->addToCompanionshipByCompID($key2, $id1);
        }
    }

    private function createNewCompanionship($id1, $id2)
    {
        $this->companionships[uniqid()] = array($id1, $id2);
    }

    public function addToCompanionshipByCompID($companionshipsID, $individualID)
    {
        if (!in_array($individualID, $this->companionships[$companionshipsID])) {
            $this->companionships[$companionshipsID][] = $individualID;
        }
    }

    public function removeFromCompanionship($individualID)
    {
        $key = $this->getCompanionshipIDByIndividualID($individualID);
        if (!empty($key)) {
            if (in_array($individualID, $this->companionships[$key])) {
                $this->companionships[$key] = array_diff($this->companionships[$key], array($individualID));
                sort($this->companionships[$key]);
            }
        }
    }

    public function removeFromCompanionshipByKey($companionshipsID, $individualID)
    {
        if (in_array($individualID, $this->companionships[$companionshipsID])) {
            $this->companionships[$companionshipsID] = array_diff($this->companionships[$companionshipsID], array($individualID));
        }
    }

    public function save()
    {
        file_put_contents(self::PATH, json_encode($this->companionships));
    }

    public function getCompanionships()
    {
        return $this->companionships;
    }

    public function countOfCompanionships()
    {
        $count = 0;
        foreach ($this->companionships as $c) {
            if (count($c) > 1) {
                ++$count;
            }
        }

        return $count;
    }

    public function getCompanionsByIndividualID($individualID)
    {
        if (!empty($this->companionships)) {
            foreach ($this->companionships as $companionsArray) {
                if (in_array($individualID, $companionsArray)) {
                    return array_diff($companionsArray, array($individualID));
                }
            }
        }

        return array();
    }

    public function getCompanionshipIDByIndividualID($individualID)
    {
        if (!empty($this->companionships)) {
            foreach ($this->companionships as $key => $companionsArray) {
                if (in_array($individualID, $companionsArray)) {
                    return $key;
                }
            }
        }

        return '';
    }
}

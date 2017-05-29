<?php

include_once 'Member.php';

class MemberManager
{
    const PATH = '../data/members.json';

    private $members = array();

    public function __construct()
    {
        $this->loadMembers();
    }

    private function getFullPath($path)
    {
        return __DIR__.'/'.$path;
    }

    private function loadMembers()
    {
        if (file_exists($this->getFullPath(self::PATH))) {
            $jsonString = file_get_contents($this->getFullPath(self::PATH));
            if ($jsonString !== false) {
                $arrayMembers = json_decode($jsonString, true);
                $this->loopThroughMembersArray($arrayMembers);
            }
        }
    }

    private function loopThroughMembersArray($array)
    {
        foreach ($array as $mem) {
            $m = new Member();
            $m->LDSOrgID = $this->getKeyValue($mem, 'LDSOrgID');
            $m->Name = $this->getKeyValue($mem, 'Name');
            $m->Gender = $this->getKeyValue($mem, 'Gender');
            $m->Phone = $this->getKeyValue($mem, 'Phone');
            $m->Address1 = $this->getKeyValue($mem, 'Address1');
            $m->Address2 = $this->getKeyValue($mem, 'Address2');
            $m->City = $this->getKeyValue($mem, 'City');
            $m->State = $this->getKeyValue($mem, 'State');
            $m->Zip = $this->getKeyValue($mem, 'Zip');
            $m->Lat = $this->getKeyValue($mem, 'Lat');
            $m->Long = $this->getKeyValue($mem, 'Long');
            $m->AddressLevel = $this->getKeyValue($mem, 'AddressLevel');
            $m->Email = $this->getKeyValue($mem, 'Email');
            $m->EmailLevel = $this->getKeyValue($mem, 'EmailLevel');
            $m->PhoneLevel = $this->getKeyValue($mem, 'PhoneLevel');
            $m->Photo1 = $this->getKeyValue($mem, 'Photo1');
            $m->Photo1Approved = $this->getKeyValue($mem, 'Photo1Approved');
            $m->Photo2 = $this->getKeyValue($mem, 'Photo2');
            $m->Photo2Approved = $this->getKeyValue($mem, 'Photo2Approved');
            $m->Callings = $this->getKeyValue($mem, 'Callings');
            $m->Birthday = $this->getKeyValue($mem, 'Birthday');
            $m->BirthdayLevel = $this->getKeyValue($mem, 'BirthdayLevel');
            $m->Priesthood = $this->getKeyValue($mem, 'Priesthood');
            $m->MoveInDate = $this->getKeyValue($mem, 'MoveInDate');
            $this->members[$m->LDSOrgID] = $m;
        }
    }

    private function getKeyValue($array, $key)
    {
        if (isset($array[$key]) && !empty($array[$key])) {
            return $array[$key];
        }

        return null;
    }

    public function countOfMembers()
    {
        return count($this->members);
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getMales()
    {
        $males = array();
        foreach ($this->members as $m) {
            if ($m->Gender == 'MALE') {
                $males[] = $m->LDSOrgID;
            }
        }

        return $males;
    }

    public function getFemales()
    {
        $males = array();
        foreach ($this->members as $m) {
            if ($m->Gender == 'FEMALE') {
                $males[] = $m->LDSOrgID;
            }
        }

        return $males;
    }

    public function getNameSortOrder()
    {
        $name = array();
        foreach ($this->members as $m) {
            $name[$m->Name] = $m->LDSOrgID;
        }
        ksort($name);

        return array_values($name);
    }

    public function getMemberByID($id)
    {
        if (array_key_exists($id, $this->members)) {
            return $this->members[$id];
        }

        return null;
    }

    public function getMemberNamesByIDArray($array)
    {
        $names = array();
        foreach ($array as $id) {
            if (array_key_exists($id, $this->members)) {
                $names[] = $this->members[$id]->Name;
            }
        }

        sort($names);

        return implode('/', $names);
    }
}

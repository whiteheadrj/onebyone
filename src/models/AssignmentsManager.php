<?php

class AssignmentsManager
{
    const PATH = '/var/www/localhost/data/assignments.json';

    private $assignments = array();
    public function __construct()
    {
        $this->load();
    }

    private function load()
    {
        if (file_exists(self::PATH)) {
            $jsonString = file_get_contents(self::PATH);
            if ($jsonString !== false) {
                $this->assignments = json_decode($jsonString, true);
            }
        }
    }

    public function addAssignment($individualID, $companionshipID)
    {
        $this->assignments[$individualID] = $companionshipID;
    }

    public function removeByIndividualID($individualID)
    {
        if (array_key_exists($individualID, $this->assignments)) {
            unset($this->assignments[$individualID]);
        }
    }

    public function removeAllByCompanionshipID($companionshipID)
    {
        foreach ($this->assignments as $k => $v) {
            if ($v == $companionshipID) {
                unset($this->assignments[$k]);
            }
        }
    }

    public function save()
    {
        file_put_contents(self::PATH, json_encode($this->assignments));
    }

    public function getAll()
    {
        return $this->assignments;
    }

    public function getCompAssignedToIndividual($individualID)
    {
        if (array_key_exists($individualID, $this->assignments)) {
            return $this->assignments[$individualID];
        }

        return null;
    }

    public function getIndividualsWithAssignments($ids)
    {
        $assigned = array_keys($this->assignments);

        return array_intersect($assigned, $ids);
    }

    public function getIndividualsWithoutAssignments($ids)
    {
        $assigned = array_keys($this->assignments);

        return array_diff($ids, $assigned);
    }

    public function getIndividualsAssignedToComp($companionshipID)
    {
        $assigned = array();
        foreach ($this->assignments as $k => $v) {
            if ($v == $companionshipID) {
                $assigned[] = $k;
            }
        }

        return $assigned;
    }
}

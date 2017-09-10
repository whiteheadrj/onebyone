<?php

error_reporting(-1);
ini_set('display_errors', 1);

include_once 'MemberManager.php';
include_once 'CompanionshipManager.php';
include_once 'AssignmentsManager.php';
include_once 'Member.php';

class UnassignDeadCompanionships
{
    const PATH = '../data/members.json';


    private $companionshipManager;
    private $assignmentsManager;

    public function process()
    {
        $this->loadData();
        $this->processData();
        $this->saveData();
    }

    private function loadData()
    {
        $this->companionshipManager = new CompanionshipManager();
        $this->assignmentsManager = new AssignmentsManager();
    }

    private function processData()
    {
        foreach ($this->assignmentsManager->getAll() as $companionship) {
            if ($this->companionshipManager->getCountOfCompanionsByKey($companionship)==0) {
                $this->assignmentsManager->removeAllByCompanionshipID($companionship);
            }
        }
    }

    private function saveData()
    {
        $this->assignmentsManager->save();
    }
}

$processor = new UnassignDeadCompanionships();
$processor->process();

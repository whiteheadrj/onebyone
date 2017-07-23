<?php

error_reporting(-1);
ini_set('display_errors', 1);

include_once 'MemberManager.php';
include_once 'OldMemberManager.php';
include_once 'TeacherManager.php';
include_once 'CompanionshipManager.php';
include_once 'DistanceManager.php';
include_once 'AssignmentsManager.php';
include_once 'Member.php';

class NewDataManager
{
    const PATH = '../data/members.json';

    private $oldMembers = array();
    private $members = array();
    //private $teachers = array();
    //private $companionships = array();
    //private $assignments = array();

    private $memberManager;
    private $teacherManager;
    private $oldMemberManager;
    private $companionshipManager;
    private $assignmentsManager;

    public function processNewData()
    {
        $this->loadData();
        $this->processData();
        $this->saveData();
    }

    private function loadData()
    {
        $this->memberManager = new MemberManager();
        $this->members = $this->memberManager->getMembers();

        $this->oldMemberManager = new OldMemberManager();
        $this->oldMembers = $this->oldMemberManager->getMembers();

        $this->teacherManager = new TeacherManager();
        //$this->teachers = $this->teecherManager->getTeachers();

        $this->companionshipManager = new CompanionshipManager();
        //$this->companionships = $this->companionshipManager->getCompanionships();

        $this->assignmentsManager = new AssignmentsManager();
        //$this->assignments = $this->assignmentsManager->getAll();
    }

    private function processData()
    {
        foreach ($this->oldMembers as $mem) {
            $this->processOldMember($mem);
        }
    }

    private function processOldMember(member $m)
    {
        if (!array_key_exists($m->LDSOrgID, $this->members)) {
            $m->Active = 0;
            $this->memberManager->addMember($m);

            $this->assignmentsManager->removeByIndividualID($m->LDSOrgID);

            $this->teacherManager->removeTeacher($m->LDSOrgID);

            $compID = $this->companionshipManager->getCompanionshipIDByIndividualID($m->LDSOrgID);
            if (!empty($compID)) {
                $this->companionshipManager->removeFromCompanionship($m->LDSOrgID);

                if ($this->companionshipManager->getCountOfCompanionsByKey($compID) == 0) {
                    $this->assignmentsManager->removeAllByCompanionshipID($compID);
                }
            }
        }
    }

    private function saveData()
    {
        $this->memberManager->save();
        $this->teacherManager->save();
        $this->assignmentsManager->save();
        $this->companionshipManager->save();
    }
}

$newData = new NewDataManager();
$newData->processNewData();

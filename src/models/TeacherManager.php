<?php

class TeacherManager
{
    const PATH = '/var/www/localhost/data/teachers.json';

    private $teachers = array();
    public function __construct()
    {
        $this->load();
    }

    private function load()
    {
        if (file_exists(self::PATH)) {
            $jsonString = file_get_contents(self::PATH);
            if ($jsonString !== false) {
                $this->teachers = json_decode($jsonString, true);
            }
        }
    }

    public function addTeacher($id)
    {
        if (!in_array($id, $this->teachers)) {
            $this->teachers[] = $id;
        }
    }

    public function removeTeacher($id)
    {
        if (in_array($id, $this->teachers)) {
            $this->teachers = array_diff($this->teachers, array($id));
        }
    }

    public function replaceTeacherList($teachers)
    {
        $this->teachers = $teachers;
    }

    public function save()
    {
        file_put_contents(self::PATH, json_encode($this->teachers));
    }

    public function countOfTeachers()
    {
        return count($this->teachers);
    }

    public function getTeachers()
    {
        return $this->teachers;
    }
}

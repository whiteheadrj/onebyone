<?php

class TeacherManager
{
    const PATH = '../data/teachers.json';

    private $teachers = array();
    public function __construct()
    {
        $this->load();
    }

    private function getFullPath($path)
    {
        return __DIR__.'/'.$path;
    }

    private function load()
    {
        if (file_exists($this->getFullPath(self::PATH))) {
            $jsonString = file_get_contents($this->getFullPath(self::PATH));
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
        file_put_contents($this->getFullPath(self::PATH), json_encode($this->teachers));
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

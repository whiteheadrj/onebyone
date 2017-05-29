<?php

class NotTeachingManager
{
    const PATH = '../data/notTeaching.json';
    const PATH2 = '../data/notTeachingReasons.json';

    private $notTeaching = array();
    private $notTeachingReasons = array();
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
                $this->notTeaching = json_decode($jsonString, true);
            }
        }
        if (file_exists($this->getFullPath(self::PATH2))) {
            $jsonString = file_get_contents($this->getFullPath(self::PATH2));
            if ($jsonString !== false) {
                $this->notTeachingReasons = json_decode($jsonString, true);
            }
        }
    }

    public function addByID($id, $reason)
    {
        if (!in_array($id, $this->notTeaching)) {
            $this->notTeaching[] = $id;
        }
        $this->notTeachingReasons[$id] = $reason;
    }

    public function removeByID($id)
    {
        if (in_array($id, $this->notTeaching)) {
            $this->notTeaching = array_diff($this->notTeaching, array($id));
        }
        if (array_key_exists($id, $this->notTeachingReasons)) {
            unset($this->notTeachingReasons[$id]);
        }
    }

    public function replaceListIDs($notTeachingIDs)
    {
        $this->notTeaching = $notTeachingIDs;
    }

    public function replaceListReasons($notTeachingReasons)
    {
        $this->notTeachingReasons = $notTeachingReasons;
    }

    public function save()
    {
        file_put_contents($this->getFullPath(self::PATH), json_encode($this->notTeaching));
        file_put_contents($this->getFullPath(self::PATH2), json_encode($this->notTeachingReasons));
    }

    public function countOf()
    {
        return count($this->notTeaching);
    }

    public function getListIDs()
    {
        return $this->notTeaching;
    }

    public function getReasons()
    {
        return $this->notTeachingReasons;
    }
}

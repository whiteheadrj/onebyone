<?php

class NotTeachingManager
{
    const PATH = '/var/www/localhost/data/notTeaching.json';
    const PATH2 = '/var/www/localhost/data/notTeachingReasons.json';

    private $notTeaching = array();
    private $notTeachingReasons = array();
    public function __construct()
    {
        $this->load();
    }

    private function load()
    {
        if (file_exists(self::PATH)) {
            $jsonString = file_get_contents(self::PATH);
            if ($jsonString !== false) {
                $this->notTeaching = json_decode($jsonString, true);
            }
        }
        if (file_exists(self::PATH2)) {
            $jsonString = file_get_contents(self::PATH2);
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
        file_put_contents(self::PATH, json_encode($this->notTeaching));
        file_put_contents(self::PATH2, json_encode($this->notTeachingReasons));
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

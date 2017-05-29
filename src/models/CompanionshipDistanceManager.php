<?php

class CompanionshipDistanceManager
{
    private $distances = array();
    private $companionships = array();

    public function __construct($distances, $companionships)
    {
        $this->distances = $distances;
        $this->companionships = $companionships;
    }

    public function computeDistances()
    {
        $avgDistances = $this->setDefaults();
        if (!empty($this->companionships) && !empty($this->distances)) {
            foreach (array_keys($avgDistances) as $key) {
                $avgDistances[$key] = $this->computeCompanionshipAvg($key);
            }
        }

        asort($avgDistances);

        return $avgDistances;
    }

    private function setDefaults()
    {
        $avgDistances = array();
        if (!empty($this->companionships)) {
            foreach (array_keys($this->companionships) as $key) {
                $avgDistances[$key] = 'NA';
            }
        }

        return $avgDistances;
    }

    private function computeCompanionshipAvg($companionshipKey)
    {
        $count = 0;
        $total = 0;
        $companions = $this->companionships[$companionshipKey];
        foreach ($companions as $id) {
            if (array_key_exists($id, $this->distances)) {
                ++$count;
                $total += $this->distances[$id];
            }
        }
        if ($count === 0) {
            return 'NA';
        }

        return $total / $count;
    }
}

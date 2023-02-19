<?php

trait HashCode
{
    function hashCode64($str) {
        $str = (string)$str;
        $hash = 0;
        $len = strlen($str);
        if ($len == 0 )
            return $hash;

        for ($i = 0; $i < $len; $i++) {
            $h = $hash << 5;
            $h -= $hash;
            $h += ord($str[$i]);
            $hash = $h;
            $hash &= 0x7FFFFFFF;
        }
        return $hash;
    }
}


$h = hashCode64('hi');

    print_r($h);



class Hash
{
    public function __construct()
    {
        $this->table = [];
        $this->count = 0;
    }

    public function hash($str)
    {
        $k = 65537;
        $m = 2 ** 20;

        $result = 0;
        $power = 1;
        for ($i = 0; $i < strlen($str); $i += 1) {
            $result = ($result + $power * ord($str[$i])) % $m;
            $power = ($power * $k) % $m;
        }

        return $result;
    }

    public function calculateIndex($table, $key)
    {
        return $this->hash((string)$key) % count($table);
    }

    public function rebuildTableIfNeed()
    {
        if (count($this->table) === 0) {
            $this->table = array_fill(0, 128, null);
        } else {
            $loadFactor = $this->count / count($this->table);
            if ($loadFactor >= 0.8) {
                $newTable = array_fill(0, count($this->table) * 2, null);
                foreach ($this->table as $list) {
                    foreach ($list as $pair) {
                        $newIndex = $this->calculateIndex($newTable, $pair['key']);
                        if (!isset($newTable[$newIndex])) {
                            $newTable[$newIndex] = new LinkedList();
                        }

                        $newTable[$newIndex]->insertAtEnd($pair);
                    }
                }

                $this->table = $newTable;
            }
        }
    }

    public function set($key, $value)
    {
        $this->rebuildTableIfNeed();

        $index = $this->calculateIndex($this->table, $key);
        if (!isset($this->table[$index])) {
            $this->table[$index] = new LinkedList();
        }

        $this->table[$index]->insertAtEnd(['key' => $key, 'value' => $value]);
        $this->count += 1;
    }

    public function get($key)
    {
        $index = $this->calculateIndex($this->table, $key);
        if (!isset($this->table[$index])) {
            return null;
        }

        foreach ($this->table[$index] as $pair1) {
            $pair = (array)$pair1;
            if ($pair['value']['key'] === $key) {
                return $pair['value']['value'];
            }
        }

        return null;
    }
}



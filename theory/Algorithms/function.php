<?php

class Timer
{
    private int $t;

    public function __construct()
    {
        $this->t = 0;
    }

    public function start()
    {
        $this->t = microtime(true);
        return $this->t;
    }

    public function finish()
    {
        return microtime(true) - $this->t;
    }
}


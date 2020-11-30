<?php

namespace App\Reservations;

class ConditionOutput
{
    const UNDECIDED = 1;

    const DENY = 2;
    const ALLOW = 3;

    protected $output;
    protected $state;
    protected $message;

    private function __construct($state, $output=null)
    {
        $this->output = $output;

        $this->state = $state;
    }

    static public function allow($output=null)
    {
        return new self(self::ALLOW, $output);
    }

    static public function deny($output=null)
    {
        return new self(self::DENY, $output);
    }

    static public function undecided($output=null)
    {
        return new self(self::UNDECIDED, $output);
    }

    public function body()
    {
        return $this->output;
    }

    public function hasBody()
    {
        return ! is_null($this->output);
    }

    public function state()
    {
        return $this->state;
    }

    public function hasMessage()
    {
        return ! is_null($this->message);
    }

    public function message($message=null)
    {
        if(is_null($message))
            return $this->message;

        $this->message = $message;

        return $this;
    }

    public function shouldContinue()
    {
        return $this->state == self::UNDECIDED;
    }

    public function isAllowed()
    {
        return $this->state == self::ALLOW;
    }

    public function isDenied()
    {
        return $this->state == self::DENY;
    }
}


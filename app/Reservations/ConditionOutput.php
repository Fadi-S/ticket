<?php

namespace App\Reservations;

class ConditionOutput
{
    const UNDECIDED = 1;

    const DENY = 2;
    const ALLOW = 3;
    const WAITING_CONFIRMATION = 4;

    protected $output;
    protected $state;
    protected $message;

    protected $confirmation;

    private function __construct($state, $output=null, $confirmation=null)
    {
        $this->state = $state;
        $this->output = $output;
        $this->confirmation = $confirmation;
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

    static public function confirmation($output=null, $confirmation=null)
    {
        return new self(self::WAITING_CONFIRMATION, $output, $confirmation);
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

    public function waitingForConfirmation()
    {
        return $this->state == self::WAITING_CONFIRMATION;
    }

    public function getConfirmationName()
    {
        return $this->confirmation;
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


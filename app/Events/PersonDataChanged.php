<?php

namespace App\Events;


use App\Models\Person;
use Illuminate\Queue\SerializesModels;

class PersonDataChanged extends Event
{
    use SerializesModels;

    /**
     * @var Person
     */
    protected $_person;
    /**
     * @var array
     */
    protected $_input;

    /**
     * Create a new event instance.
     *
     * @param Person $person
     * @param $input
     */
    public function __construct(Person $person, $input)
    {
        $this->_person = $person;
        $this->_input = $input;
    }


    /**
     * @return Person
     */
    public function getPerson(){
        return $this->_person;
    }

    /**
     * @return array
     */
    public function getInput(){
        return $this->_input;
    }
}
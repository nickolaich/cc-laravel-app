<?php

namespace App\Listeners;

use App\Events\PersonDataChanged;
use App\Events\SchemeDataChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SavePersonData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PodcastWasPurchased  $event
     * @return void
     */
    public function handle(PersonDataChanged $event)
    {
        // Access the podcast using $event->podcast...
        $person = $event->getPerson();
        $input = $this->_cleanInput($event->getInput());
        $person->update($input);
    }

    protected function _cleanInput($input){
        $keys = ["forename", "surname", "email"];
        $ret = [];
        foreach($keys as $k){
            if (isset($input[$k])){
                $ret[$k] = $input[$k];
            }
        }
        return $ret;
    }


}
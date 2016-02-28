<?php

namespace App\Listeners;

use App\Events\PersonDataChanged;
use App\Events\SchemeDataChanged;
use App\Models\Scheme\Question;
use App\Models\Scheme\Result;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SaveSchemeData
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
     * @param  PodcastWasPurchased $event
     * @return void
     */
    public function handle(PersonDataChanged $event)
    {
        // Access the podcast using $event->podcast...
        $input = $this->_parseInput($event->getInput());
        if (empty($input)) {
            return;
        }

        $person = $event->getPerson();
        foreach ($input as $qId => $qValue) {
            $question = Question::find($qId);
            if (!$question) {
                // wrong input. we don't want to work with invalid data,
                // but also we don't want to skip process
                continue;
            }
            $result = Result::whereQuestion($question)->wherePerson($person)->first();
            if (!$result) {
                $result = new Result();
                $result->question()->associate($question);
                $result->person()->associate($person);
                $result->scheme()->associate($question->section->scheme);
                $new = true;
            } else {
                $new = false;
            }
            // TODO:: question handlers for special types of questions!!!
            $result->result_text = $qValue;

            $result->saveData($new);
        }
    }

    /**
     * Parse input (it could be q_{question_id}) from POST/PUT requests
     * @param $input
     */
    protected function _parseInput($input)
    {
        $questionsData = [];
        foreach ($input as $key => $value) {
            if (strpos($key, "q_") !== false) {
                $questionsData[substr($key, 2, strlen($key) - 2)] = $value;
            }
        }
        return $questionsData;
    }
}
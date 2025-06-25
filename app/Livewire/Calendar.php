<?php

namespace App\Livewire;

use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Calendar extends Component
{
    public $events = [];

    public function mount()
    {
        // Fetch initial events
        $this->events = [
            [
                'id' => 1,
                'title' => 'Event 1',
                'start' => '2024-10-15T10:00:00',
                'end' => '2024-10-15T12:00:00',
            ],
            
        ];
    }

    public function updateEvent($eventId, $start, $end)
    {
        
        foreach ($this->events as &$event) {
            if ($event['id'] == $eventId) {
                $event['start'] = $start;
                $event['end'] = $end;
                break;
            }
        }
        
    }

    
    public function render()
    {
        
        return view('livewire.calendar');
    }
}

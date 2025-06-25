<?php

namespace App\Livewire;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;



class Search extends Component
{

    public $searchTerm = '';

    public $searchResults;
    public function submitSearch () {
        $searchResults = DB::table('appointments')->select('id', 'platform', 'description')->get();
        $this->searchResults = $searchResults;
        $this->dispatch('search-results-updated');
    }
    public function render()
    {
        return view('livewire.search');
    }
}

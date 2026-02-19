<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movie;

class MoviesTable extends Component
{
    use WithPagination;

    public $perPage = 10;

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $movies = Movie::orderByDesc('popularity')->paginate($this->perPage);
        return view('livewire.movies-table', [
            'movies' => $movies,
        ]);
    }
}

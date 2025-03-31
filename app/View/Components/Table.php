<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $headers;
    public $sortBy;
    public $direction;

    public function __construct($headers, $sortBy = null, $direction = null)
    {
        $this->headers = $headers;
        $this->sortBy = $sortBy;
        $this->direction = $direction;
    }

    public function render()
    {
        return view('components.table');
    }
}

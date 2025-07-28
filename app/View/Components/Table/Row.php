<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Row extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public  $row,
        public iterable $columns,
        public ?int  $index = 0,
        public bool $withIndex = true,
        public mixed $actions = null
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.row');
    }
}

<?php

namespace App\View\Components\CargoDeclaration;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Cargo extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $cargoTypes = null ,
        public $packings = null ,
        public $insurances = null ,
        public iterable|null $provinces = [],
        public iterable|null $cities = [],
        public iterable|null $cities1 = [],
        public string|null $mode = 'create',
        public $cargo = null
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cargo-declaration.cargo');
    }
}

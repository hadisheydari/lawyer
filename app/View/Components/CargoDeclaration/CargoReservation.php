<?php

namespace App\View\Components\CargoDeclaration;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CargoReservation extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $companies = null ,
        public $cargoReservation = null ,
        public $cargo = null ,
        public string|null $mode = 'create',

    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cargo-declaration.cargo-reservation');
    }
}

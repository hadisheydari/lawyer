<?php

namespace App\View\Components\CargoDeclaration;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CargoInformation extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public iterable|null $provinces = [],
        public iterable|null $cities = [],
        public string|null $locationType = null,
        public bool|null $isShow = false,
        public $cargo = null
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cargo-declaration.cargo-information');
    }
}

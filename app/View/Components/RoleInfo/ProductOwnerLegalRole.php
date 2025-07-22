<?php

namespace App\View\Components\RoleInfo;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductOwnerLegalRole extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public iterable|null $cities = [],
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.role-info.product-owner-legal-role');
    }
}

<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectBox extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public string $label,
        public array $options = [],
        public array|string|null $selected = null,
        public bool $multiple = false,
        public ?string $placeholder = null,
        public bool $required = false,
        public bool $disabled = false,
        public bool $hidden = false,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.select-box');
    }
}

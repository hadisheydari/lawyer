<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string|null $id,
        public string $name,
        public string $label,
        public string $type = 'text',
        public bool $hidden = false,
        public string|null $value = null,
        public string|null $placeholder = null,
        public int|float|null $min = null,
        public int|float|null $max = null,
        public int|null $minlength = null,
        public int|null $maxlength = null,
        public bool $disabled = false,
        public bool $required = false,
        public bool $readonly = false,
        public bool $numberFormat = false,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input');
    }
}

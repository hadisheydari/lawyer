<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;

class BaseTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public iterable $headers,
        public iterable $columns,
        public iterable $rows,
        public bool $withIndex = true,
        public mixed $actions = null,
    )
    {}
    public function isPaginated(): bool
    {
        return $this->rows instanceof LengthAwarePaginator || $this->rows instanceof Paginator;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.base-table');
    }
}

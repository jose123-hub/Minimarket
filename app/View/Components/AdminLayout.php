<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    /**
     * @param string $title    Page title shown in the topbar (e.g. "Categories")
     * @param string|null $subtitle  Small subtitle under the title (e.g. "Organize your product catalog")
     * @param string $active   Sidebar nav key to highlight: dashboard|inventory|categories|rewards|purchases|promotions|reports
     */
    public function __construct(
        public string $title = 'Express',
        public ?string $subtitle = null,
        public string $active = '',
    ) {
    }

    public function render(): View
    {
        return view('layouts.admin');
    }
}
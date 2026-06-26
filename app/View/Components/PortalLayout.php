<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PortalLayout extends Component
{
    public string $role;

    public function __construct(
        public string $title = 'Express',
        public ?string $subtitle = null,
        public string $active = '',
    ) {
        $this->role = Auth::user()?->role ?? 'cashier';
    }

    public function render(): View
    {
        return view('layouts.portal');
    }
}
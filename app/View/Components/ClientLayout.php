<?php

namespace App\View\Components;

use App\Models\Client;
use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ClientLayout extends Component
{
    public string $title;
    public string $active;
    public $client;

    public function __construct(
        string $title = 'Tienda',
        string $active = 'store',
        $client = null
    ) {
        $user = Auth::user();

        $this->title = $title;
        $this->active = $active;

        $this->client = $client ?? Client::where('user_id', $user?->id)->first();
    }

    public function render(): View
    {
        return view('components.client-layout');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\View\View;

class RappelController extends Controller
{
    public function index(): View
    {
        $rappels = Notification::with('marche')
            ->whereNotNull('echeance_at')
            ->orderBy('echeance_at')
            ->get();

        return view('rappels.index', compact('rappels'));
    }
}

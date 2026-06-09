<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::with('marche')
            ->latest()
            ->get();

        $nonLues = $notifications->where('lu', false)->count();

        return view('notifications.index', compact('notifications', 'nonLues'));
    }

    public function marquerToutLu(): RedirectResponse
    {
        Notification::where('lu', false)->update(['lu' => true]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}

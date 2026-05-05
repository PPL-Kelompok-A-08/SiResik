<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Menampilkan semua notifikasi pengguna
     */
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead(Request $request, string $notificationId): RedirectResponse
    {
        $notification = auth()->user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
        }

        return back();
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi sudah ditandai sebagai dibaca');
    }
}

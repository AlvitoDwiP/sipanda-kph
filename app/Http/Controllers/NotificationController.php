<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->query('filter', 'all');

        if (!Schema::hasTable('notifications')) {
            return view('pages.notifications.index', [
                'notifications' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20),
                'filter' => $filter,
                'unreadCount' => 0,
            ])->with('warning', 'Fitur notifikasi belum aktif. Jalankan migrasi database terlebih dahulu.');
        }

        $query = $user->notifications()->latest();
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(20)->withQueryString();

        return view('pages.notifications.index', [
            'notifications' => $notifications,
            'filter' => $filter,
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }

    public function read(Request $request, string $notification)
    {
        $item = $request->user()->notifications()->where('id', $notification)->firstOrFail();
        if ($item->read_at === null) {
            $item->markAsRead();
        }

        $actionUrl = $item->data['action_url'] ?? null;

        return $actionUrl ? redirect($actionUrl) : back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); // all|unread

        $q = $request->user()->notifications()->latest();
        if ($filter === 'unread') {
            $q = $request->user()->unreadNotifications()->latest();
        }

        $notifications = $q->paginate(20);

        return view('notifications.index', compact('notifications', 'filter'));
    }

    public function show(Request $request, string $id)
    {
        $n = $request->user()->notifications()->where('id', $id)->firstOrFail();

        if (is_null($n->read_at)) {
            $n->markAsRead();
        }

        $data = $n->data ?? [];
        $actionUrl = $data['action_url'] ?? null;

        // If there is a target page, redirect there
        if ($actionUrl) return redirect($actionUrl);

        return view('notifications.show', compact('n'));
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('status', 'All notifications marked as read.');
    }
}

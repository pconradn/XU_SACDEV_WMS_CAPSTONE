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

        if ($actionUrl) return redirect($actionUrl);

        return view('notifications.show', compact('n'));
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('status', 'All notifications marked as read.');
    }
    
    public function go($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $data = $notification->data;

        if (!empty($data['org_id']) && !empty($data['target_sy_id'])) {

            $allowed = \App\Models\OrgMembership::where('user_id', auth()->id())
                ->where('organization_id', $data['org_id'])
                ->where('school_year_id', $data['target_sy_id'])
                ->whereNull('archived_at')
                ->exists();

            if (!$allowed) {
                abort(403);
            }

            session([
                'active_org_id' => $data['org_id'],
                'encode_sy_id' => $data['target_sy_id'],
            ]);
        }

        $notification->markAsRead();

        return redirect($data['route'] ?? route('dashboard'));
    }



    public function partial()
    {
        $user = auth()->user();

        $unreadCount = $user->unreadNotifications()->count();

        $recentNotifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'unread_count' => $unreadCount,
            'html' => view('partials.notifications', compact('recentNotifications'))->render(),
        ]);
    }



}

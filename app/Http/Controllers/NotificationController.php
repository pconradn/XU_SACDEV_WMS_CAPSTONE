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

        $orgId = data_get($data, 'org_id');

        $syId = data_get($data, 'target_sy_id')
            ?? data_get($data, 'school_year_id')
            ?? data_get($data, 'target_school_year_id')
            ?? data_get($data, 'sy_id');

        if (!empty($orgId) && !empty($syId)) {
            $orgId = (int) $orgId;
            $syId = (int) $syId;

            $hasMembership = \App\Models\OrgMembership::query()
                ->where('user_id', auth()->id())
                ->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->whereNull('archived_at')
                ->exists();

            $hasProjectAssignment = \App\Models\ProjectAssignment::query()
                ->where('user_id', auth()->id())
                ->whereNull('archived_at')
                ->whereHas('project', function ($q) use ($orgId, $syId) {
                    $q->where('organization_id', $orgId)
                        ->where('school_year_id', $syId);
                })
                ->exists();

            if (!$hasMembership && !$hasProjectAssignment) {
                abort(403);
            }

            session([
                'active_org_id' => $orgId,
                'encode_sy_id' => $syId,
            ]);

            session()->save();
        }

        $notification->markAsRead();

        $route = $data['route'] ?? route('dashboard', absolute: false);

        if (is_string($route) && preg_match('/^https?:\/\//i', $route)) {
            $path = parse_url($route, PHP_URL_PATH) ?: route('dashboard', absolute: false);
            $query = parse_url($route, PHP_URL_QUERY);

            $route = $query ? $path . '?' . $query : $path;
        }

        return redirect($route);
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

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Search users for chat (AJAX)
     * GET /users/search?q=term
     */
    public function search(Request $request)
    {
        $q = $request->query('q');
        $filter = $request->query('filter'); // 'all' | 'unread' | 'important'

        $usersQuery = User::where('id', '!=', Auth::id())
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            }));

        // Apply server-side filters
        if ($filter === 'unread') {
            $usersQuery->whereExists(function ($q2) {
                $q2->select(\DB::raw(1))
                    ->from('messages')
                    ->whereColumn('messages.sender_id', 'users.id')
                    ->where('messages.recipient_id', Auth::id())
                    ->where('messages.is_read', false);
            });
        } elseif ($filter === 'important') {
            // Important = has attachments OR has unread
            $usersQuery->whereExists(function ($q2) {
                $q2->select(\DB::raw(1))
                    ->from('messages')
                    ->whereColumn('messages.sender_id', 'users.id')
                    ->where('messages.recipient_id', Auth::id())
                    ->where(function ($q3) {
                        $q3->whereNotNull('messages.attachment_path')
                           ->orWhere('messages.is_read', false);
                    });
            });
        }

        $users = $usersQuery->limit(50)
            ->get()
            ->map(function ($u) {
                $unread = \App\Models\Message::where('sender_id', $u->id)
                    ->where('recipient_id', Auth::id())
                    ->where('is_read', false)
                    ->count();

                $last = \App\Models\Message::where(function ($q) use ($u) {
                    $q->where('sender_id', $u->id)->where('recipient_id', Auth::id());
                })->orWhere(function ($q) use ($u) {
                    $q->where('sender_id', Auth::id())->where('recipient_id', $u->id);
                })->orderBy('created_at', 'desc')->first();

                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'profile_photo_url' => $u->profile_photo_url,
                    'unread_count' => $unread,
                    'last_message_at' => $last?->created_at?->toJSON(),
                    'last_message' => $last?->body,
                    'important' => (bool) \App\Models\Message::where('sender_id', $u->id)
                                        ->where('recipient_id', Auth::id())
                                        ->whereNotNull('attachment_path')
                                        ->exists(),
                ];
            });

        return response()->json($users);
    }
}

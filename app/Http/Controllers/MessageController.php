<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    public function conversation(User $user, Request $request)
    {
        $me = Auth::user();

        $messages = Message::where(function ($q) use ($me, $user) {
            $q->where('sender_id', $me->id)->where('recipient_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('sender_id', $user->id)->where('recipient_id', $me->id);
        })->with('sender')->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();
        $data['sender_id'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $data['attachment_path'] = $path;
        }

        $message = Message::create($data);

        return response()->json(["message" => $message], Response::HTTP_CREATED);
    }

    public function update(UpdateMessageRequest $request, Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        // enforce 20 minutes edit window
        $now = now();
        if ($message->created_at->diffInMinutes($now) > 20) {
            return response()->json(['error' => 'Edit window expired'], Response::HTTP_FORBIDDEN);
        }

        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            // delete old file if present
            if ($message->attachment_path) {
                Storage::disk('public')->delete($message->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('attachments', 'public');
        }

        // if body changed, set edited_at
        if (array_key_exists('body', $data) && $message->body !== ($data['body'] ?? '')) {
            $data['edited_at'] = now();
        }

        $message->update($data);

        return response()->json(['message' => $message]);
    }

    public function destroy(Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        if ($message->attachment_path) {
            Storage::disk('public')->delete($message->attachment_path);
        }

        $message->delete();

        return response()->json(['status' => 'deleted']);
    }

    /**
     * Mark all messages from $user to current user as read
     */
    public function markRead(User $user)
    {
        $me = Auth::user();

        Message::where('sender_id', $user->id)
            ->where('recipient_id', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['status' => 'ok']);
    }
}

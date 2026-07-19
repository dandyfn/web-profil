<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'comment' => 'required|string|max:1000',
        ]);

        \App\Models\Comment::create([
            'post_id' => $id,
            'name' => strip_tags($request->name),
            'comment' => strip_tags($request->comment), // Aman dari serangan script inject
        ]);

        return back()->with('success', 'Komentar terkirim!');
    }

    public function updateComment(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|max:1000']);
        $comment = \App\Models\Comment::findOrFail($id);
        $comment->update(['comment' => strip_tags($request->comment)]);
        return back()->with('success', 'Komentar berhasil diperbarui!');
    }

    public function destroyComment($id)
    {
        $comment = \App\Models\Comment::findOrFail($id);
        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus!');
    }
}

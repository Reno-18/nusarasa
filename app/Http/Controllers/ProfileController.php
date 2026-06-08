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
        $user = $request->user();
        $unlockedBadges = $user->badges;

        return view('profile.edit', [
            'user' => $user,
            'unlockedBadges' => $unlockedBadges,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // Handle active badge selection
        if (array_key_exists('active_badge_id', $data)) {
            if (empty($data['active_badge_id'])) {
                // User wants no badge
                $data['active_badge_id'] = null;
            } else {
                // Verify the user actually owns the badge
                $ownsBadge = $user->badges()->where('badges.id', $data['active_badge_id'])->exists();
                if (!$ownsBadge) {
                    unset($data['active_badge_id']); // Ignore invalid badge selection
                }
            }
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

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

    /**
     * Toggle the user's leaderboard visibility status.
     */
    public function toggleLeaderboard(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->update(['show_on_leaderboard' => !$user->show_on_leaderboard]);

        return Redirect::route('profile.edit')->with('success', 'Status penampilan di leaderboard berhasil diubah!');
    }
}

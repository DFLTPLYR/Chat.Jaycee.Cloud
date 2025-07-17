<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileAvatarRequest;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Http\Requests\Settings\UpdateProfilePreferenceRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
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

        return to_route('profile.edit');
    }

    public function updateAvatar(ProfileAvatarRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Store the file with a randomized name
        $file = $request->file('avatar');
        $randomName = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();

        // $path = $file->storeAs('avatars', $randomName, 's3');

        $path = Storage::disk('r2')->putFile(
            'avatar',
            $file,
        );

        $user->update([
            'profile_photo' => $path,
        ]);

        return to_route('profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Update the user's text size preference.
     */
    public function updateTextSizePreference(UpdateProfilePreferenceRequest $request)
    {
        $user = Auth::user();

        $user->preference->update([
            'text_size' => $request->input('text_size'),
        ]);

        return back()->with('test');
    }
}

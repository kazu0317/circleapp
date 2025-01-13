<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\User;
use App\Models\Application;

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

    public function updateJoinNumber(Request $request, $userId)
    {
        // 指定されたユーザーを取得
        $user = User::findOrFail($userId);

        // 参加回数を取得（例: Application テーブルの count）
        $joinNumber = Application::where('user_id', $userId)->count();

        // join_number カラムに参加回数をセット
        $user->join_number = $joinNumber;

        // ユーザーを保存
        $user->save();

        return response()->json(['message' => 'Join number updated successfully']);
    }

    public function show(User $user)
    {
        return view('profile.person_detail')->with(['user' => $user]);
    }

}



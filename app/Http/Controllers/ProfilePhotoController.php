<?php

namespace App\Http\Controllers;

use App\Support\ProfilePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilePhotoController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();

        ProfilePhoto::deleteIfExists($user->profile_photo_path ?? null);

        $path = ProfilePhoto::storeForUser($request->file('foto'), (int) $user->id);

        $user->profile_photo_path = $path;
        $user->save();

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
    }

    public function destroy()
    {
        $user = Auth::user();

        ProfilePhoto::deleteIfExists($user->profile_photo_path ?? null);

        $user->profile_photo_path = null;
        $user->save();

        return redirect()->back()->with('success', 'Foto profil berhasil dihapus');
    }
}

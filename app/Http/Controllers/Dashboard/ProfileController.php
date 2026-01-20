<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function index()
    {
        return view('dashboard.pages.profile');
    }

    /**
     * Update user name
     */
    public function updateName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Name updated successfully',
            'name' => $user->name
        ]);
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo) {
            $oldPhotoPath = public_path('assets/profile-photos/' . basename($user->profile_photo));
            if (file_exists($oldPhotoPath)) {
                @unlink($oldPhotoPath);
            }
        }

        // Create directory if it doesn't exist
        $uploadPath = public_path('assets/profile-photos');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $extension = $request->file('photo')->getClientOriginalExtension();
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $extension;

        // Move uploaded file to public directory
        $request->file('photo')->move($uploadPath, $filename);

        // Store relative path in database
        $photoPath = 'assets/profile-photos/' . $filename;
        $user->profile_photo = $photoPath;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated successfully',
            'photo_url' => asset($photoPath)
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
}


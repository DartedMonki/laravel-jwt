<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    private function hashPassword($password)
    {
        $salt = env('HASH_SALT'); // Retrieve the salt from the .env file
        return hash('sha256', $salt . $password); // Hash the password using SHA-256 with the salt
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Hash the password using SHA-256 with salt
        $hashedPassword = $this->hashPassword($request->password);

        // Store the user with the hashed password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashedPassword,
        ]);

        $token = JWTAuth::fromUser($user);

        $cookie = cookie('token', $token, 60); // Cookie valid for 60 minutes

        return response()->json(compact('user', 'token'))->withCookie($cookie);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Retrieve the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the hashed password matches the stored hashed password
        if ($user->password !== $this->hashPassword($request->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = JWTAuth::fromUser($user);

        $cookie = cookie('token', $token, 60); // Cookie valid for 60 minutes

        return response()->json(['message' => 'Successfully logged in'])->withCookie($cookie);
    }


    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        $cookie = Cookie::forget('token');

        return response()->json(['message' => 'Successfully logged out'])->withCookie($cookie);
    }

    public function me()
    {
        $user = Auth::user();

        // Return user details
        return response()->json([
            'user' => $user,
        ]);
    }

    public function checkAuth(Request $request)
    {
        // Check if the token is present in the request
        $token = $request->cookie('token') ?? $request->bearerToken(); // Get from cookie or Authorization header

        if (!$token) {
            return response()->json(['message' => 'User not authenticated.'], 200); // User not authenticated
        }

        try {
            // Attempt to get the authenticated user
            if (!$user = JWTAuth::setToken($token)->authenticate()) {
                return response()->json(['message' => 'User not authenticated.'], 200); // User not authenticated
            }

            // If successful, return the user details
            return response()->json(['message' => 'User is authenticated.', 'user' => $user], 200);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token is invalid or expired.'], 401);
        }
    }

}

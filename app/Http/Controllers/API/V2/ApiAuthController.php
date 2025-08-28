<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 401);
        }
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            // successfull authentication
            $user = User::find(Auth::user()->id);
            $tokenScope = $this->getScopes($user);
            $tokenResult = $user->createToken('appTokenV2', $tokenScope);
            $token = $tokenResult->token;
            $token->save();
            $user_token['token'] = $tokenResult->accessToken;

            Log::info('Generated Token: ' . $user_token['token']);
            Log::info('Generated Scopes : ' . $tokenScope[0]);
            return response()->json([
                'success' => true,
                'token' => $user_token,
                'expires_at' => $tokenResult->token->expires_at,
                'user' => $user,
            ], 200);
        } else {
            // failure to authenticate
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate.',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke(); // Revoke access token
        $token->refreshToken()->delete(); // Revoke refresh token
        return response()->json(['message' => 'Successfully logged out']);
    }

    private function getScopes(User $user)
    {
        if ($user->role->role_type === 'admin') {
            return ['admin'];
        } elseif ($user->role->role_type === 'seller') {
            return ['seller'];
        } else {
            return ['user'];
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $userService;

    public function login(Request $request)
    {
        Log::info($request->all());
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Tạo token (nếu dùng Sanctum)
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }
        return response()->json(['message' => 'Sai email hoặc mật khẩu'], 401);
        dd($request->all());
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // 1. Validation
        // Using Validator facade for more control over validation rules
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // 'unique:users' ensures email is not already taken
            'password' => ['required', 'string'], // 'confirmed' automatically checks for password_confirmation field
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON
            return response()->json([
                'message' => 'Dữ liệu đăng ký không hợp lệ.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // You can also use $request->validate() which throws a ValidationException automatically:
        /*
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dữ liệu đăng ký không hợp lệ.',
                'errors' => $e->errors()
            ], 422);
        }
        */

        // 2. User Creation & Password Hashing
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'password' => Hash::make($request->password), // Hash the password
                // Add any other default fields if necessary, e.g., 'role_id' => 1
            ]);
            return response()->json([
                'message' => 'Đăng ký tài khoản thành công!',
                'user' => $user->only(['id', 'name', 'email']),
            ], 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi trong quá trình đăng ký. Vui lòng thử lại.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        return $this->userService->logout();
    }
}

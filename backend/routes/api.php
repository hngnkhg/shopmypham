<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController; // Đảm bảo đúng namespace cho AuthController

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Các route KHÔNG CẦN xác thực (dành cho người dùng chưa đăng nhập)
// Đây là các route public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']); // Sử dụng /auth/login để khớp với frontend của bạn

// Các route CẦN xác thực (dành cho người dùng đã đăng nhập)
// Tất cả các route trong group này sẽ yêu cầu token Sanctum hợp lệ
Route::middleware('auth:sanctum')->group(function () {
    // Lấy thông tin người dùng hiện tại
    // Đây là route mà frontend (authStore.fetchUser()) gọi để lấy thông tin user sau khi F5
    Route::get('/auth/user', function (Request $request) {
        return response()->json([
            'user' => $request->user()->only(['id', 'name', 'email', 'phone', 'address']), // Chỉ trả về các trường cần thiết
        ]);
    });

    // Đăng xuất người dùng
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Các route khác yêu cầu xác thực sẽ nằm ở đây
    // Ví dụ:
    // Route::put('/user/profile', [UserController::class, 'updateProfile']);
    // Route::post('/user/change-password', [UserController::class, 'changePassword']);
});
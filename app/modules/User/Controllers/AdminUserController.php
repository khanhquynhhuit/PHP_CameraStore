<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Viết phần trả về danh sách người dùng cho Admin
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Viết phần trả về view tạo người dùng
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Viết phần lưu người dùng mới
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Viết phần trả về chi tiết người dùng
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Viết phần trả về form chỉnh sửa người dùng
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Viết phần cập nhật người dùng
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Viết phần xóa người dùng
    }
}

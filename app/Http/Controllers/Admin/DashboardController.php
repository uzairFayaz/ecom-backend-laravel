<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
class DashboardController extends Controller {
    public function index() {
        $users = User::whereHas('role', fn($query) => $query->where('role_name', '!=', 'admin'))->get();
        return view('admin.dashboard', compact('users'));
    }
}

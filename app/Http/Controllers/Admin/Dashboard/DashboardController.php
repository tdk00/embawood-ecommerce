<?php
namespace App\Http\Controllers\Admin\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Account\UserDeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.pages.dashboard.dashboard');
    }
}

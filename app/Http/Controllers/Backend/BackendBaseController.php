<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackendBaseController extends Controller
{
    public function __construct()
    {
        $adminId = session('admin_id');
        if (empty($adminId) || (int)$adminId < 1) {
            return redirect(url('backend/login'));
        }
    }
}

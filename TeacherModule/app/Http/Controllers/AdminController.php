<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class AdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        return view('admin.index', ['admin' => $admin]);
    }

    public function getRoles(Request $request)
    {

        if ($request->ajax()) {
            $data = Roles::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->role_id . '" class="edit bg-emerald-500 px-4 py-1 rounded-md editRole">Edit</a> <a href="javascript:void(0)"  data-id="' . $row->role_id . '" class="delete bg-red-500 px-4 py-1 rounded-md deleteRole">Delete</a></div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.roles');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $news=News::with('Admin')->get();

            return datatables($news)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return $data->title;
                })
                ->addColumn('posted_by', function ($data) {

                    return $data->admin->first_name.' '.$data->admin->last_name;
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->status ? 'checked' : '';
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-status" type="checkbox" data-id="' . $data->id . '" id="toggleStatus' . $data->id . '" ' . $checked . '>
                        <label class="form-check-label" for="toggleStatus' . $data->id . '"></label>
                    </div>
                </li>
            </ul>';
                })
                ->addColumn('action', function ($data) {
                    return '<button onclick="getEditModal(\'' . route('Admin.courses.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>';
                })
                ->rawColumns(['name','action','status'])
                ->make(true);
        }

        return view('admin.news.index');
    }

    public function store(Request $request)
    {
        News::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'admin_id'=>Auth::id(),
        ]);
    }
}

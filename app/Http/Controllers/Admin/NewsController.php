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
            $news = News::where('department_id', Auth::user()->admin->department_id)->with('admin')->get();
            return datatables($news)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return $data->title;
                })
                ->addColumn('posted_by', function ($data) {

                    return $data->admin->first_name . ' ' . $data->admin->last_name;
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
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <button onclick="getEditModal(\'' . route('admin.news.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('admin.news.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name', 'action', 'status'])
                ->make(true);
        }

        return view('admin.news.index');
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Create a new news entry in the database
        News::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'admin_id' => Auth::id(),
            'department_id' => Auth::user()->admin->department_id,
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }


    public function updateStatus(Request $request)
    {

        News::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $data['news'] = News::where('admin_id', Auth::id())->find($id);

        return view('admin.news.edit-form', $data);
    }

    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Find the instructor by user_id
        $news = News::where('department_id', Auth::user()->admin->department_id)->find($id);
        // Check if the instructor exists
        if (!$news) {
            return redirect()->route('admin.news.index')->with('error', 'News not found.');
        }

        // Update instructor details
        $news->title = $request->title;
        $news->description = $request->description;

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function delete($id)
    {

        $news = News::find($id);
        $news->delete();
        return response()->json(['success' => true, 'message' => 'News deleted successfully.']);

    }
}

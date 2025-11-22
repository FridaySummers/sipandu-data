<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use Illuminate\Http\Request;

class DataManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $submissions = class_exists(DataSubmission::class) ? DataSubmission::paginate(15) : null;

        return view('datamanagement', ['submissions' => $submissions]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dinas_id' => 'required',
            'data' => 'required',
        ]);

        if (class_exists(DataSubmission::class)) {
            DataSubmission::create($validated);
        }

        return redirect()->route('datamanagement')->with('success', 'Data submitted successfully');
    }

    public function update(Request $request, DataSubmission $submission)
    {
        $this->authorize('update', $submission);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'data' => 'required',
        ]);

        $submission->update($validated);

        return redirect()->route('datamanagement')->with('success', 'Data updated successfully');
    }
}

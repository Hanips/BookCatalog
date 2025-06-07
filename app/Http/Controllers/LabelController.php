<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::all();
        // Assuming you will create a view at resources/views/adminpage/labels/index.blade.php
        return view('adminpage.labels.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Assuming you will create a view at resources/views/adminpage/labels/create.blade.php
        return view('adminpage.labels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:labels,name',
            'type' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'size' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
        ]);

        Label::create($request->all());

        return redirect()->route('labels.index') // Assuming you will have a route named 'labels.index'
                         ->with('success', 'Label created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label)
    {
        // Assuming you will create a view at resources/views/adminpage/labels/show.blade.php
        return view('adminpage.labels.show', compact('label'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        // Assuming you will create a view at resources/views/adminpage/labels/edit.blade.php
        return view('adminpage.labels.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Label $label)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:labels,name,' . $label->id,
            'type' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'size' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
        ]);

        $label->update($request->all());

        return redirect()->route('labels.index') // Assuming you will have a route named 'labels.index'
                         ->with('success', 'Label updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        $label->delete();

        return redirect()->route('labels.index') // Assuming you will have a route named 'labels.index'
                         ->with('success', 'Label deleted successfully.');
    }
}

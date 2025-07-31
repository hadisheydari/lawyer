<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Setting\PackingRequest;
use App\Models\Packing;

class PackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packings = Packing::latest()->paginate(10);
        return view('setting.packings.index', compact('packings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('setting.packings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PackingRequest $request)
    {
        Packing::create($request->validated());

        return redirect()->route('packings.index')
            ->with('success', 'نوع بسته‌بندی با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Packing $packing)
    {
        return view('setting.packings.show', compact('packing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Packing $packing)
    {
        return view('setting.packings.edit', compact('packing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PackingRequest $request, Packing $packing)
    {
        $packing->update($request->validated());

        return redirect()->route('packings.index')
            ->with('success', 'نوع بسته‌بندی با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Packing $packing)
    {
        $packing->delete();

        return redirect()->route('packings.index')
            ->with('success', 'نوع بسته‌بندی با موفقیت حذف شد.');
    }
}

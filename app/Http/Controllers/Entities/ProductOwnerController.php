<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Models\ProductOwner;
use Illuminate\Http\Request;
use App\Http\Requests\Entities\ProductOwnerRequest;

class ProductOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductOwnerRequest $request)
    {
        $data = array_merge(
            $request->validated(),
            [
                'user_id' => auth()->id(),
                'product_owner_type' => $request->session()->get('user_type'),
                'document' => $request->hasFile('document') ? $request->file('document')->store('product_owners/documents') : null,
            ]
        );

        ProductOwner::create($data);

        return redirect()->route('dashboard')->with('success', 'مالک محصول با موفقیت ایجاد شد.');

    }

    /**
     * Display the specified resource.
     */
    public function show(ProductOwner $productOwner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductOwner $productOwner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductOwnerRequest $request, ProductOwner $productOwner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductOwner $productOwner)
    {
        //
    }
}

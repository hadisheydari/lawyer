<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Models\ProductOwner;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Entities\ProductOwnerRequest;

class ProductOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product_owners = ProductOwner::with('user')->where('user_id', auth()->id())->get();

        return view('entities.product-owners.index', compact( 'product_owners'));
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
                'document' => $request->hasFile('document') ? $request->file('document')->store('product_owners/documents' , 'public' ) : null,
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
        $productOwner->load(['user', 'company', 'city', 'province', 'vehicle']);
        $province = Province::find($productOwner->province_id);
        $cities = [];
        if ($province) {
            $cities = City::where('province_code', $province->code)->pluck('name', 'id');
        }
        $provinces = Province::pluck('name', 'id');
        return view('entities.product-owners.show', compact('productOwner'  , 'provinces' , 'cities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductOwner $productOwner)
    {
        $productOwner->load(['user', 'company', 'city', 'province', 'vehicle']);
        $province = Province::find($productOwner->province_id);
        $cities = [];
        if ($province) {
            $cities = City::where('province_code', $province->code)->pluck('name', 'id');
        }
        $provinces = Province::pluck('name', 'id');
        return view('entities.product-owners.edit', compact('productOwner'  , 'provinces' , 'cities'));    }

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

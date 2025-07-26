<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Requests\Entities\DriverRequest;

class DriverController extends Controller
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
    public function store(DriverRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // آرایه‌ای که کلید فایل‌ها و مسیر ذخیره رو مشخص می‌کند
        $fileFields = [
            'national_card_file' => 'drivers/national_cards',
            'smart_card_file' => 'drivers/smart_cards',
            'certificate_file' => 'drivers/certificates',
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store($path);
            }
        }

        Driver::create($data);

        return redirect()->route('dashboard')
            ->with('success', 'راننده با موفقیت ساخته شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DriverRequest $request, Driver $driver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        //
    }
}

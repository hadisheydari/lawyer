<?php

namespace App\Http\Controllers\Rating;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Partition;
use App\Http\Requests\Rating\RatingRequest;
use App\Traits\ApiResponseTrait;


class RatingController extends Controller
{
    use ApiResponseTrait;

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
    public function create(string $partition)
    {
        return view('rating.create', compact('partition'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RatingRequest $request): JsonResponse|RedirectResponse
    {
        $userId = $request->user()->id;
        $data = $request->validated();
        $data['user_id'] = $userId;
        Rating::create($data);
        $partition = Partition::where('id' , $request['partition_id'])->first();
        if ($request->expectsJson()) {
            return $this->success('امتیاز با موفقیت برای شما ثبت شد .', 200 , null);
        }

        return redirect()->route('partitions.index_of_partition' ,['cargo' => $partition->cargo_id, 'status' => 'delivered'])
            ->with('success', 'امتیاز با موفقیت ساخته شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        $user = User::find($rating->user_id);

        $role = $user->getRoleNames()->first();

        return view('rating.create' , compact('rating' , 'role'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        //
    }
}

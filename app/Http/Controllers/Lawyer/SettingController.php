<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\LawyerSchedule;
use App\Models\LawyerScheduleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    private function lawyer()
    {
        return Auth::guard('lawyer')->user();
    }

    // ─── نمایش تنظیمات ───────────────────────────────────────────────────────
    public function index()
    {
        $lawyer    = $this->lawyer();
        $schedules = LawyerSchedule::where('lawyer_id', $lawyer->id)
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        $exceptions = LawyerScheduleException::where('lawyer_id', $lawyer->id)
            ->where('exception_date', '>=', now()->toDateString())
            ->orderBy('exception_date')
            ->get();

        $days = [
            0 => 'شنبه',
            1 => 'یکشنبه',
            2 => 'دوشنبه',
            3 => 'سه‌شنبه',
            4 => 'چهارشنبه',
            5 => 'پنج‌شنبه',
        ];

        return view('lawyer.settings.index', compact('lawyer', 'schedules', 'exceptions', 'days'));
    }

    // ─── به‌روزرسانی پروفایل ─────────────────────────────────────────────────
    public function updateProfile(Request $request)
    {
        $lawyer = $this->lawyer();

        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'nullable|email|unique:lawyers,email,' . $lawyer->id,
            'phone'            => 'required|string|max:15|unique:lawyers,phone,' . $lawyer->id,
            'bio'              => 'nullable|string|max:2000',
            'education'        => 'nullable|string|max:500',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'specializations'  => 'nullable|array',
            'specializations.*'=> 'string|max:100',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'available_for_chat'        => 'boolean',
            'available_for_call'        => 'boolean',
            'available_for_appointment' => 'boolean',
        ], [
            'name.required'  => 'نام الزامی است.',
            'phone.required' => 'شماره موبایل الزامی است.',
            'phone.unique'   => 'این شماره قبلاً ثبت شده است.',
            'email.unique'   => 'این ایمیل قبلاً ثبت شده است.',
        ]);

        $data = [
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'bio'              => $request->bio,
            'education'        => $request->education,
            'experience_years' => $request->experience_years ?? 0,
            'specializations'  => $request->specializations ?? [],
            'available_for_chat'        => $request->boolean('available_for_chat'),
            'available_for_call'        => $request->boolean('available_for_call'),
            'available_for_appointment' => $request->boolean('available_for_appointment'),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('lawyers', 'public');
            $data['image'] = $path;
        }

        $lawyer->update($data);

        return back()->with('success', 'پروفایل با موفقیت به‌روز شد.');
    }

    // ─── به‌روزرسانی ساعات کاری ──────────────────────────────────────────────
    public function updateSchedule(Request $request)
    {
        $lawyer = $this->lawyer();

        $request->validate([
            'schedules'                => 'required|array',
            'schedules.*.day_of_week'  => 'required|integer|min:0|max:5',
            'schedules.*.is_available' => 'required|boolean',
            'schedules.*.start_time'   => 'required_if:schedules.*.is_available,1|date_format:H:i|nullable',
            'schedules.*.end_time'     => 'required_if:schedules.*.is_available,1|date_format:H:i|after:schedules.*.start_time|nullable',
        ]);

        foreach ($request->schedules as $scheduleData) {
            LawyerSchedule::updateOrCreate(
                [
                    'lawyer_id'   => $lawyer->id,
                    'day_of_week' => $scheduleData['day_of_week'],
                ],
                [
                    'is_available' => $scheduleData['is_available'],
                    'start_time'   => $scheduleData['is_available'] ? $scheduleData['start_time'] : null,
                    'end_time'     => $scheduleData['is_available'] ? $scheduleData['end_time'] : null,
                ]
            );
        }

        return back()->with('success', 'ساعات کاری به‌روز شد.');
    }

    // ─── افزودن روز استثنا (تعطیل یا خاص) ───────────────────────────────────
    public function addException(Request $request)
    {
        $lawyer = $this->lawyer();

        $request->validate([
            'exception_date' => 'required|date|after_or_equal:today',
            'is_available'   => 'required|boolean',
            'reason'         => 'nullable|string|max:255',
        ], [
            'exception_date.required'         => 'تاریخ الزامی است.',
            'exception_date.after_or_equal'   => 'تاریخ نمی‌تواند در گذشته باشد.',
        ]);

        LawyerScheduleException::updateOrCreate(
            [
                'lawyer_id'      => $lawyer->id,
                'exception_date' => $request->exception_date,
            ],
            [
                'is_available' => $request->is_available,
                'reason'       => $request->reason,
            ]
        );

        $label = $request->is_available ? 'روز کاری اضافه' : 'روز تعطیل';
        return back()->with('success', "{$label} ثبت شد.");
    }

    // ─── حذف روز استثنا ──────────────────────────────────────────────────────
    public function deleteException(LawyerScheduleException $exception)
    {
        if ($exception->lawyer_id !== $this->lawyer()->id) {
            abort(403);
        }

        $exception->delete();

        return back()->with('success', 'روز استثنا حذف شد.');
    }
}
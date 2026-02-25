<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        $diehBase1404 = config('legal.dieh_base_1404', 1_000_000_000);
        $coinPrice    = config('legal.coin_price', 70_000_000);

        return view('public.calculators.index', compact('diehBase1404', 'coinPrice'));
    }

    /** GET /api/calculators/mahrieh?coins=114&cash=0&year=1390 */
    public function calcMahrieh(Request $request): JsonResponse
    {
        $request->validate([
            'coins' => 'nullable|numeric|min:0',
            'cash'  => 'nullable|numeric|min:0',
            'year'  => 'required|integer|min:1360|max:1404',
        ]);

        $coins       = (float) ($request->coins ?? 0);
        $cash        = (float) ($request->cash  ?? 0);
        $year        = (int)   $request->year;
        $coinPrice   = config('legal.coin_price', 70_000_000);
        $inflRate    = 0.35;
        $currentYear = 1404;
        $years       = max(0, $currentYear - $year);

        $coinVal = $coins * $coinPrice;
        $cashVal = ($cash / 10) * pow(1 + $inflRate, $years);
        $total   = $coinVal + $cashVal;

        return response()->json([
            'total'    => $total,
            'coin_val' => $coinVal,
            'cash_val' => $cashVal,
            'note'     => 'این مبلغ تخمینی بوده و ملاک قانونی ندارد.',
        ]);
    }

    /** GET /api/calculators/dieh?type=full_male&month=1&percent=100 */
    public function calcDieh(Request $request): JsonResponse
    {
        $request->validate([
            'type'    => 'required|in:full_male,full_female,custom',
            'month'   => 'required|integer|min:1|max:12',
            'percent' => 'nullable|numeric|min:1|max:100',
        ]);

        $base    = config('legal.dieh_base_1404', 1_000_000_000);
        $type    = $request->type;
        $month   = (int) $request->month;
        $percent = (float) ($request->percent ?? 100);

        $amount = match ($type) {
            'full_female' => $base / 2,
            'custom'      => $base * $percent / 100,
            default       => $base,
        };

        $sacredMonths = [1, 7, 9, 12];
        $isSacred = in_array($month, $sacredMonths);
        if ($isSacred) {
            $amount *= (4 / 3);
        }

        return response()->json([
            'total'     => $amount,
            'is_sacred' => $isSacred,
            'base'      => $base,
            'note'      => 'بر اساس مصوبه ۱۴۰۴ قوه قضاییه.',
        ]);
    }

    /** GET /api/calculators/court */
    public function calcCourt(Request $request): JsonResponse
    {
        $request->validate([
            'type'   => 'required|in:money,non-money,appeal,cassation',
            'amount' => 'nullable|numeric|min:0',
            'stage'  => 'required|in:first,appeal,cassation',
        ]);

        $type   = $request->type;
        $amount = (float) ($request->amount ?? 0);
        $stage  = $request->stage;
        $fee    = 0;
        $rate   = '';

        if ($type === 'non-money') {
            $fee  = $stage === 'first' ? 2_000_000 : 1_500_000;
            $rate = 'مقطوع';
        } elseif ($type === 'money') {
            if ($amount <= 2_000_000_000) {
                $fee  = $amount * 0.035;
                $rate = '۳.۵٪';
            } elseif ($amount <= 5_000_000_000) {
                $fee  = 2_000_000_000 * 0.035 + ($amount - 2_000_000_000) * 0.03;
                $rate = '۳٪';
            } else {
                $fee  = 2_000_000_000 * 0.035 + 3_000_000_000 * 0.03 + ($amount - 5_000_000_000) * 0.02;
                $rate = '۲٪';
            }
            if ($stage === 'appeal')    { $fee *= 0.5; }
            if ($stage === 'cassation') { $fee *= 0.3; }
        }

        return response()->json([
            'fee'   => $fee,
            'rate'  => $rate,
            'total' => $fee + 500_000,
        ]);
    }
}

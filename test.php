<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Prop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StatsController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function count(Request $request)
    {
        $query = Prop::whereHas('flags', function ($query) {
            $query->where('skip_count', false);
        });

        switch ($request->input('kind')) {
            case null:
                $query->whereHas('flags', function ($query) {
                    $query->where('proportion', false);
                });
                break;
            case 'proportion':
                $query->whereHas('flags', function ($query) {
                    $query->where('proportion', true);
                });
                break;
            case 'new_building':
                $query->whereHas('flags', function ($query) {
                    $query->where('new_building', true)->where('proportion', false);
                });
                break;
            case 'old_building':
                $query->whereHas('flags', function ($query) {
                    $query->where('new_building', false)->where('proportion', false);
                });
                break;
        }

        if ($request->has('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        if ($request->has('function_id')) {
            $query->where('function_id', $request->function_id);
        }

        if ($request->has('locality_id')) {
            $query->whereHas('address', function ($query) use ($request) {
                $query->where('locality_id', $request->locality_id);
            });
        }

        if ($request->has('district_id')) {
            $query->whereHas('address', function ($query) use ($request) {
                $query->where('district_id', $request->district_id);
            });
        }

        $query->addSelect(DB::raw('sum(case when `rooms` = 1 then 1 ELSE 0 END) as `1_room_count`'));
        $query->addSelect(DB::raw('sum(case when `rooms` = 2 then 1 ELSE 0 END) as `2_room_count`'));
        $query->addSelect(DB::raw('sum(case when `rooms` = 3 then 1 ELSE 0 END) as `3_room_count`'));
        $query->addSelect(DB::raw('sum(case when `rooms` = 4 then 1 ELSE 0 END) as `4_room_count`'));

        $result = $query->addSelect(DB::raw('count(`id`) as `total_count`, year(`transaction_date`) as `year`, month(`transaction_date`) as `month`'))->orderBy('year')->orderBy('month')->groupBy('year', 'month')->without('flags', 'type', 'address', 'function')->get();
        $index = count($result) - 1;

        for ($i = $index; $i >= 0; $i--) {
            $result[$i]['1_room_count'] = (int) $result[$i]['1_room_count'];
            $result[$i]['2_room_count'] = (int) $result[$i]['2_room_count'];
            $result[$i]['3_room_count'] = (int) $result[$i]['3_room_count'];
            $result[$i]['4_room_count'] = (int) $result[$i]['4_room_count'];

            switch ($result[$i]->month) {
                case 1:
                    $result[$i]->month_label = trans('months.yan');
                    break;
                case 2:
                    $result[$i]->month_label = trans('months.feb');
                    break;
                case 3:
                    $result[$i]->month_label = trans('months.mar');
                    break;
                case 4:
                    $result[$i]->month_label = trans('months.apr');
                    break;
                case 5:
                    $result[$i]->month_label = trans('months.may');
                    break;
                case 6:
                    $result[$i]->month_label = trans('months.jun');
                    break;
                case 7:
                    $result[$i]->month_label = trans('months.jul');
                    break;
                case 8:
                    $result[$i]->month_label = trans('months.aug');
                    break;
                case 9:
                    $result[$i]->month_label = trans('months.sep');
                    break;
                case 10:
                    $result[$i]->month_label = trans('months.oct');
                    break;
                case 11:
                    $result[$i]->month_label = trans('months.nov');
                    break;
                case 12:
                    $result[$i]->month_label = trans('months.dec');
                    break;
            }
        }

        return [
            'raw' => $result,
            'years' => $result->groupBy('year')->map(function ($item) {
                return [
                    'months' => collect($item)->groupBy('month')->map(function ($item) {
                        return $item[0];
                    }),
                ];
            }),
        ];
    }
}

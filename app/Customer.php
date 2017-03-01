<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * @package App
 *
 * @property Carbon created_at
 */
class Customer extends Model
{
    protected $table = 'customer';

    protected $fillable = ['name', 'surname', 'phone', 'status_id', 'created_at', 'updated_at'];

    public function status()
    {
        return $this->belongsTo(CustomerStatus::class);
    }

    /**
     * @return Customer
     */
    public static function firstCustomer()
    {
        return self::orderBy('created_at')
            ->first();
    }

    /**
     * @return Customer
     */
    public static function lastCustomer()
    {
        return self::orderBy('created_at', SORT_DESC)
            ->first();
    }

    public static function range()
    {
        return [
            'start' => self::firstCustomer()->created_at,
            'end' => self::lastCustomer()->created_at
        ];
    }

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @return array
     */
    public static function conversion($start = null, $end = null)
    {
        if(!$start) {
            $start = self::firstCustomer()->created_at;
        }

        if(!$end) {
            $end = Carbon::now();
        }

        $result = [
            'start' => $start->timestamp,
            'end' => $end->timestamp,
            'value' => 0
        ];

        /** @var Collection $data */
        $data = self::select(['status_id', DB::raw('COUNT(id) as count')])
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->groupBy('status_id')
            ->get()
            ->keyBy('status_id');

        if(!$data->has(CustomerStatus::STATUS_REGISTERED)) {
            return $result;
        }

        $sum = $data->sum('count');
        $result['value'] = $data[CustomerStatus::STATUS_REGISTERED]['count'] / $sum;
        return $result;
    }

    public static function months()
    {
        $range = self::range();
        /** @var Collection $rows */
        $rows = self::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as date"),
                'status_id',
                DB::raw('count(id) count')
            )->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->groupBy('status_id')
            ->where('created_at', '>=', $range['start']->modify('first day of next month')->startOfDay())
            ->where('created_at', '<=', $range['end']->modify('last day of previous month')->endOfDay())
            ->get();

        return self::prepare($rows, function($month) {
            $date = Carbon::createFromFormat('Y-m', $month);
            return [
                'start' => $date->modify('first day of this month')->startOfDay()->timestamp,
                'end' => $date->modify('last day of this month')->endOfDay()->timestamp,
                'value' => 0
            ];
        });
    }

    public static function weeks()
    {
        $range = self::range();
        /** @var Collection $rows */
        $rows = self::select(
            DB::raw("CONCAT(YEAR(created_at), '-', WEEK(created_at)) as date"),
            'status_id',
            DB::raw('count(id) count')
        )->groupBy(DB::raw("CONCAT(YEAR(created_at), '-', WEEK(created_at))"))
            ->groupBy('status_id')
            ->where('created_at', '>=', $range['start']->modify('first day of next week')->startOfDay())
            ->where('created_at', '<=', $range['end']->modify('last day of previous week')->endOfDay())
            ->get();

        return self::prepare($rows, function($date) {
            list($year, $week) = explode('-', $date);
            $date = new Carbon();
            $date->setISODate($year, $week);
            return [
                'start' => $date->modify('monday this week')->startOfDay()->timestamp,
                'end' => $date->modify('sunday this week')->endOfDay()->timestamp,
                'value' => 0
            ];
        });
    }

    protected static function prepare($rows, callable $prepare)
    {
        $parsed = [];
        foreach($rows as $row) {
            $parsed[$row['date']][$row['status_id']] = $row['count'];
        }

        $data = [];
        foreach($parsed as $date => $item) {
            $prepared = $prepare($date);
            if(!array_key_exists(CustomerStatus::STATUS_REGISTERED, $item)) {
                $data[] = $prepared;
                continue;
            }

            $prepared['value'] = $item[CustomerStatus::STATUS_REGISTERED] / array_sum($item);
            $data[] = $prepared;
        }

        return $data;
    }
}

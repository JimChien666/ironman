<?php

namespace App\Providers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Listening for query events
        DB::listen(function ($query) {
            // Escape Query
            $sql = $query->sql;
            // Binding Data
            $bindings = $query->bindings;
            // Spend Time
            $time = $query->time;

            // 針對 Binding 資料進行格式的處理
            // 例如字串就加上引號
            foreach ($bindings as $index => $binding) {
                if (is_bool($binding)) {
                    $bindings[$index] = ($binding) ? ('1') : ('0');
                } elseif (is_string($binding)) {
                    $bindings[$index] = "'$binding'";
                }
            }

            // 依據將 ? 取代成 Binding Data
            $sql = preg_replace_callback('/\?/', function () use (&$bindings) {
                return array_shift($bindings);
            }, $sql);

            // 寫入 storage/logs/laravel.log
            Log::info($sql);
        });
    }

}



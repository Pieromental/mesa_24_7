<?php

namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DynamicQueryFilter
{

    public static function apply(Request $request, string $modelClass, array $with = [])
    {
        $query = $modelClass::query();
        $table = (new $modelClass)->getTable();
        $columns = Schema::getColumnListing($table);

        $params = $request->query();

        foreach ($params as $key => $value) {
            if (in_array($key, ['sort_by', 'order', 'page', 'limit', 'offset'])) {
                continue;
            }

            if (in_array($key, $columns)) {
                if (is_array($value)) {
                    $query->whereIn($key, $value);
                } else {
                    $query->where($key, 'like', "%$value%");
                }
            }
        }


        $sortField = $request->input('sort_by');
        $sortOrder = $request->input('order', 'asc');

        if (in_array($sortField, $columns)) {
            $query->orderBy($sortField, $sortOrder);
        } else {

            $query->orderBy('created_at', 'desc');
        }

        if (!empty($with)) {
            try {
                $query->with($with);
            } catch (\Throwable $e) {
                Log::warning("Falló carga de relación en {$modelClass}: {$e->getMessage()}");
            }
        }

        return $query->get();
    }
}

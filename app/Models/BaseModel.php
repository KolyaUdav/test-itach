<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    public static function filter(Request $request)
    {
        $query = static::query();
        $columns = Schema::getColumnListing((new static)->getTable());
        $perPage = 10;
        
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->query($column));
            }
        }

        if ($request->filled('perPage')) {
            $perPage = $request->query('perPage');
        }

        return $query->paginate($perPage);
    }

    public static function getById(int $itemId): ?static
    {
        return static::query()->find($itemId);
    }

    public static function apiAdd(array $data): ?static
    {
        try {
            $el = static::create($data);

            if (!$el) {
                Log::warning('Не удалось создать запись', [
                    'model' => static::class,
                    'data' => $data,
                ]);

                return null;
            }

            return $el;
        } catch (QueryException $e) {
            // Ошибки уровня БД
            Log::error('Ошибка при создании записи', [
                'model' => static::class,
                'data' => $data,
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            return null;
        } catch (\Throwable $e) {
            // Другие ошибки
            Log::error('Ошибка при создании модели', [
                'model' => static::class,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}
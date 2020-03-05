<?php
namespace JamesMills\Uuid;

use JamesMills\Uuid\Exception\MissingUuidColumnException;
use Ramsey\Uuid\Uuid;

trait HasUuidTrait
{
    protected static function bootHasUuidTrait()
    {
        static::creating(function ($model) {
            $uuidColumn = static::getUuidColumnName();

            if (!$model->$uuidColumn) {
                $model->$uuidColumn = (string)Uuid::uuid4();
            }
        });
    }

    public static function findByUuidOrFail($uuid)
    {
        return self::whereUuid($uuid)->firstOrFail();
    }

    /**
     * Eloquent scope to look for a given UUID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  String                                $uuid  The UUID to search for
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithUuid($query, $uuid)
    {
        return $query->where(static::getUuidColumnName(), $uuid);
    }

    /**
     * Eloquent scope to look for multiple given UUIDs
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Array                                 $uuids  The UUIDs to search for
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithUuids($query, Array $uuids)
    {
        return $query->whereIn(static::getUuidColumnName(), $uuids);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the column name for the uuid.
     *
     * @return string
     */
    public static function getUuidColumnName()
    {
        return 'uuid';
    }
}

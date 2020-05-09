<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomRole extends Model
{

    protected $fillable = ['name', 'slug'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable('room_roles');

        parent::__construct($attributes);
    }

    /**
     * A role belongs to many users.
     *
     * @return BelongsToMany
     */
    public function rooms(): BelongsToMany
    {
        $pivotTable = 'room_role_rooms';

        $relatedModel = new Room();

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'room_id');
    }


    /**
     * Check user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can(string $permission): bool
    {
        return $this->rooms()->where('slug', $permission)->exists();
    }

    /**
     * Check user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot(string $permission): bool
    {
        return !$this->can($permission);
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->rooms()->detach();
        });
    }
}

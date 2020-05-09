<?php

namespace App\Admin\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserRole extends Model
{

    protected $fillable = ['name', 'slug'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable('user_roles');

        parent::__construct($attributes);
    }

    /**
     * A role belongs to many users.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        $pivotTable = 'user_role_users';

        $relatedModel = new User();

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'user_id');
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
        return $this->users()->where('slug', $permission)->exists();
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
            $model->users()->detach();

        });
    }
}

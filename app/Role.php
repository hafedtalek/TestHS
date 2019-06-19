<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'has_administration_rights', 'has_onboarding_rights', 'has_view_user_rights'
    ];
    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}

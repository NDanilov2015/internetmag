<?php

namespace App\Models\Auth;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $table = 'roles';
	
	protected $fillable = ['name', 'display_name', 'description'];
}

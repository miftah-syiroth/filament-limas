<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DepartmentLocation extends Pivot
{
    use HasUuids;

    protected $table = 'department_locations';
}

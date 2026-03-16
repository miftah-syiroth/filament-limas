<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ActivityLog extends \Spatie\Activitylog\Models\Activity
{
    use HasUuids;
}

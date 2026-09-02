<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected function log(string $action, string $module, string $description): void
    {

        ActivityLog::log($action, $module, $description);
    }
}

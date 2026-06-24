<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditObserver
{
    protected function saveEvent(Model $model, string $event): void
    {
        $user = Auth::user();

        DB::table('audit_logs')->insert([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'route_name' => null,
            'http_method' => request()?->method() ?? 'system',
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'request_payload' => json_encode([
                'event' => $event,
                'model' => get_class($model),
                'model_id' => $model->getKey(),
                'attributes' => $model->getAttributes(),
                'changes' => $model->getChanges(),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function created(Model $model): void
    {
        $this->saveEvent($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->saveEvent($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->saveEvent($model, 'deleted');
    }

    public function restored(Model $model): void
    {
        $this->saveEvent($model, 'restored');
    }
};

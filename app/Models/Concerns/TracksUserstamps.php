<?php

namespace App\Models\Concerns;

use App\Models\User;
use App\Services\DiscordWebhookService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait TracksUserstamps
{
    public static function bootTracksUserstamps(): void
    {
        static::creating(function (Model $model): void {
            if (Auth::id() && empty($model->created_by)) {
                $model->created_by = Auth::id();
            }

            if (Auth::id()) {
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function (Model $model): void {
            if (Auth::id()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function (Model $model): void {
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                return;
            }

            if (Auth::id() && empty($model->deleted_by)) {
                $model->deleted_by = Auth::id();
                $model->saveQuietly();
            }
        });

        foreach (['created', 'updated', 'deleted', 'restored'] as $event) {
            static::{$event}(function (Model $model) use ($event): void {
                app(DiscordWebhookService::class)->sendModelNotification($model, $event);
            });
        }

        static::restored(function (Model $model): void {
            $model->forceFill([
                'deleted_by' => null,
            ])->saveQuietly();
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function auditLabel(): string
    {
        return class_basename($this).' #'.$this->getKey();
    }
}

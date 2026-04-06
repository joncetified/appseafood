<?php

namespace App\Services;

use App\Models\CompanyProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordWebhookService
{
    public function sendModelNotification(Model $model, string $event): void
    {
        if (! in_array($event, ['created', 'updated', 'deleted', 'restored'], true)) {
            return;
        }

        $webhookUrl = $this->resolveWebhookUrl();

        if (! $webhookUrl) {
            return;
        }

        try {
            Http::timeout(5)->post($webhookUrl, [
                'content' => null,
                'embeds' => [[
                    'title' => 'System '.$this->formatEvent($event),
                    'description' => $model->auditLabel(),
                    'color' => $this->resolveColor($event),
                    'fields' => [
                        [
                            'name' => 'Model',
                            'value' => class_basename($model),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Actor',
                            'value' => Auth::user()?->auditLabel() ?? 'System',
                            'inline' => true,
                        ],
                        [
                            'name' => 'Time',
                            'value' => now()->format('d/m/Y H:i:s'),
                            'inline' => true,
                        ],
                    ],
                ]],
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Discord notification failed.', [
                'message' => $exception->getMessage(),
                'model' => class_basename($model),
                'event' => $event,
            ]);
        }
    }

    public function sendMessage(string $title, string $description, array $fields = []): void
    {
        $webhookUrl = $this->resolveWebhookUrl();

        if (! $webhookUrl) {
            return;
        }

        try {
            Http::timeout(5)->post($webhookUrl, [
                'embeds' => [[
                    'title' => $title,
                    'description' => $description,
                    'color' => 3447003,
                    'fields' => array_map(fn (array $field) => [
                        'name' => $field['name'],
                        'value' => (string) $field['value'],
                        'inline' => (bool) ($field['inline'] ?? false),
                    ], $fields),
                ]],
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Discord message failed.', [
                'message' => $exception->getMessage(),
                'title' => $title,
            ]);
        }
    }

    private function resolveWebhookUrl(): ?string
    {
        return CompanyProfile::query()->value('discord_webhook_url') ?: config('services.discord.webhook_url');
    }

    private function formatEvent(string $event): string
    {
        return match ($event) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'restored' => 'Restored',
            default => ucfirst($event),
        };
    }

    private function resolveColor(string $event): int
    {
        return match ($event) {
            'created' => 3066993,
            'updated' => 15844367,
            'deleted' => 15158332,
            'restored' => 3447003,
            default => 9807270,
        };
    }
}

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\Application;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('applications:files-to-private {--dry-run}', function () {
    $dryRun = (bool) $this->option('dry-run');
    $moved = 0;

    Application::query()
        ->whereNotNull('form_answers')
        ->chunkById(100, function ($applications) use ($dryRun, &$moved) {
            foreach ($applications as $application) {
                $answers = $application->form_answers ?: [];
                $changed = false;

                foreach ($answers as &$answer) {
                    if (!is_array($answer)
                        || empty($answer['is_file'])
                        || empty($answer['store_in_system'])
                        || !is_string($answer['value'] ?? null)) {
                        continue;
                    }

                    $path = str_replace('\\', '/', ltrim($answer['value'], '/\\'));

                    if (!str_starts_with($path, 'applications/attachments/')
                        || str_contains($path, '../')
                        || !Storage::disk('public')->exists($path)) {
                        continue;
                    }

                    $moved++;

                    if ($dryRun) {
                        continue;
                    }

                    if (!Storage::disk('local')->exists($path)) {
                        $stored = Storage::disk('local')->put(
                            $path,
                            Storage::disk('public')->get($path)
                        );

                        if (!$stored) {
                            throw new RuntimeException("Unable to move application attachment: {$path}");
                        }
                    }

                    Storage::disk('public')->delete($path);
                    $answer['storage_disk'] = 'local';
                    $answer['original_name'] ??= basename($path);
                    $changed = true;
                }
                unset($answer);

                if ($changed) {
                    $application->update(['form_answers' => $answers]);
                }
            }
        });

    foreach (Storage::disk('public')->allFiles('applications/attachments') as $path) {
        $path = str_replace('\\', '/', ltrim($path, '/\\'));

        if (!str_starts_with($path, 'applications/attachments/')
            || str_contains($path, '../')) {
            continue;
        }

        $moved++;

        if ($dryRun) {
            continue;
        }

        if (!Storage::disk('local')->exists($path)) {
            $stored = Storage::disk('local')->put(
                $path,
                Storage::disk('public')->get($path)
            );

            if (!$stored) {
                throw new RuntimeException("Unable to move orphaned application attachment: {$path}");
            }
        }

        Storage::disk('public')->delete($path);
    }

    $mode = $dryRun ? 'would be moved' : 'moved';
    $this->info("{$moved} application attachment(s) {$mode} to private storage.");
})->purpose('Move legacy application attachments from public to private storage');

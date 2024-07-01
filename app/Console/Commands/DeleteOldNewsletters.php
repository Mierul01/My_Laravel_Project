<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Newsletter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteOldNewsletters extends Command
{
    protected $signature = 'newsletter:delete-old';
    protected $description = 'Soft delete newsletters older than 2 minutes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('DeleteOldNewsletters command started.');

        // Soft delete newsletters older than 2 minutes
        $newsletters = Newsletter::where('created_at', '<', Carbon::now()->subMinutes(2))
                                ->whereNull('deleted_at')
                                ->get();

        foreach ($newsletters as $newsletter) {
            Log::info('Soft deleting newsletter: ' . $newsletter->id);
            $newsletter->delete();
        }

        // Re-soft delete newsletters recovered for more than 2 minutes
        $restoredNewsletters = Newsletter::whereNotNull('restored_at')
                                        ->where('restored_at', '<', Carbon::now()->subMinutes(2))
                                        ->get();

        foreach ($restoredNewsletters as $newsletter) {
            Log::info('Re-soft deleting newsletter: ' . $newsletter->id);
            $newsletter->delete();
            $newsletter->update(['restored_at' => null]);
        }

        Log::info('DeleteOldNewsletters command finished.');
    }
}

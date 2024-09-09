<?php

namespace App\Console\Commands;

use App\Helpers\EmailFormatterHelper;
use App\Models\SuccessfulEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FormatEmailBody extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:format-email-body';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Iterates over the emails stored and get the plain text of her bodies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Getting emails to format');
        $emailsToFormat = SuccessfulEmail::query()
            ->where(fn ($query) => $query->where('raw_text', null)
                ->orWhere('raw_text', '')
            )
            ->get();

        if($emailsToFormat->isEmpty()){
            $this->info('No emails found.');
            return;
        }

        $this->info('Formatting emails...');
        DB::beginTransaction();
        try{
            foreach ($emailsToFormat as $email) {
                $email->raw_text = EmailFormatterHelper::extractPlainTextFromHtml($email->email);
                $email->save();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            error_log($e->getMessage());
            $this->error('An error occurred while formatting emails: '. $e->getMessage());
            return;
        }

        DB::commit();
        $this->info('Emails formatted successfully.');
    }
}

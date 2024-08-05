<?php

namespace App\Console\Commands;

use Soundasleep\Html2Text;
use Illuminate\Console\Command;
use App\Models\SuccessfulEmail;

class ParseEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse raw email content and save the plain text body';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emails = SuccessfulEmail::whereNull('raw_text')->get();

        foreach ($emails as $email) {
            $rawText = Html2Text::convert($email->email);
            $email->raw_text = $rawText;
            $email->save();
        }

        $this->info('Emails parsed successfully.');
    }
}

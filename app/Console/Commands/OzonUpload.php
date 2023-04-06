<?php

namespace App\Console\Commands;

use App\Ozon\OzonApi;
use Illuminate\Console\Command;

class OzonUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ozon-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
         \App\Jobs\OzonUpload::dispatchSync();
    }
}

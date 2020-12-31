<?php

namespace App\Console\Commands;

use App\Feed;
use Illuminate\Console\Command;

class InstallFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install feeds';

    /**
     * @var array
     */
    protected $sites = [
        'byrealty',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->sites as $site) {
            $this->line(
                "Installing feed for site \"{$site}\"..."
            );

            if (! Feed::create(['id' => $site])) {
                $this->error(
                    "Error installing feed for site \"{$site}\"!"
                );
            } else {
                $this->info(
                    "Feed for site \"{$site}\" is successfully installed."
                );
            }
        }
    }
}

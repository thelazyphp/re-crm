<?php

namespace App\Console\Commands;

use App\Bot;
use Illuminate\Console\Command;

class InstallBots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bots:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install bots';

    /**
     * @var array
     */
    protected $sites = [
        'realt',
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
                "Installing bot for site \"{$site}\"..."
            );

            if (! Bot::create(['id' => $site])) {
                $this->error(
                    "Error installing bot for site \"{$site}\"!"
                );
            } else {
                $this->info(
                    "Bot for site \"{$site}\" is successfully installed."
                );
            }
        }
    }
}

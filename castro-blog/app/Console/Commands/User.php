<?php

namespace App\Console\Commands;

use App\Models\User as UserModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class User extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

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
     * @return int
     */
    public function handle()
    {
        do {
            $name = $this->ask('Name');

            if (!$name) {
                $this->error('The name is required!');
            }
        } while (!$name);

        do {
            $email = $this->ask('Email');

            if (!$email) {
                $this->error('The email is required!');
            } elseif (UserModel::where('email', $email)->exists()) {
                $email = '';
                $this->error('The email is already taken!');
            }
        } while (!$email);

        do {
            $password = $this->ask('Password');

            if (!$password) {
                $this->error('The password is required!');
            }
        } while (!$password);

        if (
            ! UserModel::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ])
        ) {
            $this->error('Error creating user!');
        } else {
            $this->info('The user is successfully created.');
        }
    }
}

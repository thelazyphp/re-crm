<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

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

            if (!$name) {
                $this->error('The email is required!');
            } elseif (
                Validator::make([
                    'email' => $email,
                ], [
                    'email' => ['email'],
                ])->fails()
            ) {
                $this->error('The email format is invalid!');
                $email = '';
            } elseif (User::where('email', $email)->exists()) {
                $this->error('The user with such email is already exists!');
                $email = '';
            }
        } while (!$email);

        do {
            $password = $this->ask('Password');

            if (!$name) {
                $this->error('The password is required!');
            }
        } while (!$password);

        if (
            ! User::create([
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

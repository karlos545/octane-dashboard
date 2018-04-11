<?php

namespace Octane\Console;

use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    protected $signature = 'octane:make-user {email}';

    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = app(config('auth.providers.users.model'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');

        $firstName = $this->ask("What is the user's first name?");
        $lastName = $this->ask("What is the user's last name?");

        $password = $this->ask('Please specify a password (defaults to random)', substr(md5(time()), 0, 10));

        $admin = $this->confirm('Do you want this user to be an admin?', true);

        $user = $this->userModel->create([
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'password'   => $password,
        ]);

        $admin ? $user->assignRole('admin') : '';

        $this->info("Success! {$user->fullName} ({$user->email}) was created with password: {$password}");
    }
}

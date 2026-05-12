<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:admin
        {email : Email address of the user}
        {--name=Admin Lamaka : User display name}
        {--password= : Password to set. If omitted, it will be asked securely}
        {--disable : Remove admin access instead of granting it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create, update, or disable a Lamaka admin user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = strtolower((string) $this->argument('email'));

        if ($this->option('disable')) {
            $updated = User::query()
                ->where('email', $email)
                ->update(['is_admin' => false]);

            if (! $updated) {
                $this->warn("No user found for [{$email}].");

                return self::FAILURE;
            }

            $this->info("Admin access disabled for [{$email}].");

            return self::SUCCESS;
        }

        $password = (string) ($this->option('password') ?: $this->secret('Password'));

        if ($password === '') {
            $this->error('Password cannot be empty.');

            return self::FAILURE;
        }

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = (string) $this->option('name');
        $user->password = Hash::make($password);
        $user->is_admin = true;
        $user->save();

        $this->info("Admin user ready: [{$email}].");

        return self::SUCCESS;
    }
}

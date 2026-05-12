<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class AutoDeactivateInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:auto-nonaktif';

    /**
     * The console command description.
     */
    protected $description = 'Menonaktifkan user yang tidak login dalam X hari';

    public function handle()
    {
        $days = 30; // ubah sesuai kebutuhan
        $batas = now()->subDays($days);

        $users = \App\Models\User::where('status', 'Aktif')
            ->where(function ($q) use ($batas) {
                $q->whereNull('last_login_at')
                ->orWhere('last_login_at', '<', $batas);
            })
            ->get();

        if ($users->count() === 0) {
            $this->info('Tidak ada user yang perlu dinonaktifkan.');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            $user->update(['status' => 'Tidak Aktif']);
        }

        $this->info("Berhasil menonaktifkan {$users->count()} user tidak aktif.");

        return Command::SUCCESS;
    }
}

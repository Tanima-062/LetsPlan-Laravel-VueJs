<?php

namespace App\Console\Commands;

use App\Mail\NotifyPgAdminForWhenRegistrationPhaseEndedMail;
use App\Models\Registry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyPgAdminForWhenRegistrationPhaseEnded extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyPgAdmin:registrationPhaseEnds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification E-Mail to PGADMIN after the registration phase ended';

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
     * @return bool
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        $registrationPhasesEnded = Registry::where('registration_phase_end_date', $yesterday)->get();

        foreach ($registrationPhasesEnded as $registry) {
            $user = User::findOrFail($registry->user_id);
            Mail::to($user->email)->send(new NotifyPgAdminForWhenRegistrationPhaseEndedMail($registry, $user));
        }
        return true;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Division;
use Illuminate\Console\Command;

class DivisionStatusChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'division:change-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change division status';

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
        info('division status change command run!');
        // return 0;
        $now = now(env('APP_TIMEZONE', 'Europe/Zurich'));
        dump($now->addHours(2)->toDateTimeString());

        //Set active
        Division::query()
                ->where('registration_start_date', '<=', $now)
                ->where('status_id', 1)
                ->update([
                    'status_id' =>  2
                ])
                ;

        //Set ready for discussion
        $now = now(env('APP_TIMEZONE', 'Europe/Zurich'))->addHours(2)->toDateString();
        dump($now);

        $division = Division::query()
                ->where('registration_end_date', '<=', $now)
                ->where('status_id', 2)
                ->update([
                    'status_id' =>  3
                ])
                // ->get()
                ;

        dump($division);

    }
}

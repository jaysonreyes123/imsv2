<?php

namespace App\Console\Commands;

use App\Models\InsightReport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InsightReportMontly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:insight-report-montly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $model = new InsightReport();
        $model->name = Carbon::now()->format('mY')."-IMS-monthly-report";
        $model->type = 3;
        $model->start_date = Carbon::now()->startOfMonth()->setTime(0,0,0);
        $model->end_date = Carbon::now()->endOfMonth()->setTime(23,59,59);
        $model->save();
    }
}

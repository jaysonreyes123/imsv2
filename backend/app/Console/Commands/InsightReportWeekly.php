<?php

namespace App\Console\Commands;

use App\Models\InsightReport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InsightReportWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:insight-report-weekly';

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
        $model->name = Carbon::now()->format('Ymd')."_IMS_WeeklyReport";
        $model->type = 2;
        $model->start_date = Carbon::now()->startOfWeek()->setTime(0,0,0);
        $model->end_date = Carbon::now()->endOfWeek()->setTime(23,59,59);
        $model->save();
    }
}

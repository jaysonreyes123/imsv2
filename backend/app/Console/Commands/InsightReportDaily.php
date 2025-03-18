<?php

namespace App\Console\Commands;

use App\Models\InsightReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InsightReportDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:insight-report-daily';

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
        $model->name = Carbon::now()->format('mdY')."-IMSdailyreport";
        $model->type = 1;
        $model->start_date = Carbon::now()->setTime(0,0,0);
        $model->end_date = Carbon::now()->endOfDay()->setTime(23,59,59);
        $model->save();
    }
}

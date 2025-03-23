<?php

use App\Console\Commands\InsightReportDaily;
use App\Console\Commands\InsightReportMontly;
use App\Console\Commands\InsightReportWeekly;
use App\Http\Middleware\MobileToken;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias(['mobile.token' => MobileToken::class]);
        $middleware->append(StartSession::class);
    })
    ->withSchedule(function (Schedule $schedule){
        // $schedule->command(InsightReportDaily::class)->dailyAt('08:00')->withoutOverlapping();
        $schedule->command(InsightReportDaily::class)->dailyAt('08:00')->withoutOverlapping();
        //every 1st of week at 8 am
        $schedule->command(InsightReportWeekly::class)->weeklyOn(1,'08:00')->withoutOverlapping();
        //every 1st of month at 8am
        $schedule->command(InsightReportMontly::class)->monthlyOn(1,'08:00')->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

<?php

use App\Jobs\SendWeeklyExpenseReport;
use App\Models\Expense;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SendWeeklyExpenseReport)
            ->weekly()
            ->timezone('UTC')
            ->fridays()
            ->at('00:00');
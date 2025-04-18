<?php

namespace App\Jobs;

use App\Mail\WeeklyExpenseReport;
use App\Models\Company;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendWeeklyExpenseReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    // public function __construct(
    //     public Expense $expense
    // )
    // {
    //     //
    // }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $companies = Company::with(['users' => function($query){
            $query->where('role', 'Admin');
        }])->get();
        
        foreach ($companies as $company) {
            $expenses = $company->expenses()
                ->whereBetween('created_at', [now()->subWeek(), now()])
                ->get();
            if (!empty($expenses)) {
                foreach ($company->users as $admin) {
                    Mail::to($admin->email)->queue(new WeeklyExpenseReport($expenses));
                }
            }
        }
    }
}

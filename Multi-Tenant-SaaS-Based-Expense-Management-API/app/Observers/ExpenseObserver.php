<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Expense;

class ExpenseObserver
{
    public function updated(Expense $expense){
        AuditLog::create([
            'user_id' => auth()->id(),
            'company_id' => $expense->company_id,
            'action' => 'updated',
            'changes' => json_encode([
                'before' => $expense->getOriginal(),
                'after' => $expense->getChanges(),
            ]),
        ]);
    }
    
    public function deleted(Expense $expense)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'company_id' => $expense->company_id,
            'action' => 'delete',
            'changes' => [
                'old' => $expense->getOriginal()
            ]
        ]);
    }
}

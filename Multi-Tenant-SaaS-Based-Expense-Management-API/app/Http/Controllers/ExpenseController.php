<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request) {
        $query = Expense::with('user')
            ->where('company_id', $request->user()->company_id);
            
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('category', 'like', '%'.$request->search.'%');
            });
        }
        
        return $query->paginate(10);
    }
    
    public function register(Request $request) {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
        ]);

        $expense = Expense::create([
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'company_id' => Auth::user()->company_id,
            'user_id' => Auth::user()->id,
        ]);
        
        return response()->json($expense, 201);
    }
    
    public function update(Request $request, $id) {
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
        ]);

        $expense = Expense::where('company_id', Auth::user()->company_id)
                  ->findOrFail($id);

        $expense = $expense->update([
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'company_id' => Auth::user()->company_id,
            'user_id' => Auth::user()->id,
        ]);
        
        return response()->json($expense, 201);
    }

    public function delete($id){

        $expense = Expense::where('company_id', Auth::user()->company_id)
                  ->findOrFail($id);

        $expense->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}

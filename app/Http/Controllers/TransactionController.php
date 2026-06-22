<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Fund;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function create()
    {
        $categories = Category::whereNotNull('parent_id')->get();
        $funds = Fund::all();
        
        return view('transactions.create', compact('categories', 'funds'));
    }

    public function store(StoreTransactionRequest $request)
    {
        // 1. Validation métier stricte (Pas de catégorie parente)
        $category = Category::findOrFail($request->category_id);
        if (is_null($category->parent_id)) {
            return back()
                ->withErrors(['category_id' => 'Kategori ini adalah kategori utama dan tidak dapat dipilih.'])
                ->withInput();
        }

        // 2. Gestion de l'upload du fichier preuve
        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('proofs', 'public');
        }

        // 3. Création de la transaction (forcée à 'pending')
        Transaction::create([
            'category_id' => $request->category_id,
            'fund_id' => $request->fund_id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'proof_file' => $proofPath,
            'validation_status' => 'pending',
        ]);

        // 4. Redirection vers le tableau de bord
        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil dibuat dan menunggu validasi.');
    }

    public function pending()
    {
        $transactions = Transaction::with(['creator', 'category', 'fund'])
            ->where('validation_status', 'pending')
            ->orderBy('date', 'asc')
            ->get();

        return view('transactions.pending', compact('transactions'));
    }

    public function approve(Transaction $transaction)
    {
        $transaction->validation_status = 'validated';
        $transaction->validated_by = Auth::id();
        $transaction->save();

        return back()->with('success', __('Transaction approved successfully.'));
    }

    public function reject(Transaction $transaction)
    {
        $transaction->validation_status = 'rejected';
        $transaction->save();

        return back()->with('success', __('Transaction rejected.'));
    }
}

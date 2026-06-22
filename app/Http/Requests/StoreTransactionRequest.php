<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,category_id',
            'fund_id' => 'required|exists:funds,fund_id',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.numeric' => 'Jumlah harus berupa angka.',
            'amount.min' => 'Jumlah harus lebih besar dari 0.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'fund_id.required' => 'Kas/Cagnotte wajib dipilih.',
            'fund_id.exists' => 'Kas/Cagnotte yang dipilih tidak valid.',
            'type.required' => 'Tipe transaksi wajib dipilih.',
            'type.in' => 'Tipe transaksi tidak valid.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'description.required' => 'Deskripsi wajib diisi.',
            'proof_file.mimes' => 'Bukti harus berupa file gambar (JPG, PNG) atau PDF.',
            'proof_file.max' => 'Ukuran file bukti tidak boleh lebih dari 2MB.',
        ];
    }
}

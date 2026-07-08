<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LpjExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        // Reusing the same standard HTML view used for DOMPDF
        return view('reports.pdf', $this->data);
    }

    public function styles(Worksheet $sheet)
    {
        // Add basic Excel styling if needed, e.g., make row 1 bold
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // Additional styling can be applied here, but the HTML table 
            // structure from the view usually renders quite well automatically.
        ];
    }
}

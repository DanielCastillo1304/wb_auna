<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\System\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JournalistExport;

class RVoucherController extends Controller
{
    public $extend = null;
    public $keyword;
    protected $perPage = 10;
 
    public function __construct()
    {
        $this->middleware('module.permission:listar')->only('index');
        $this->extend = [
            'title' => 'Reporte Vouchers',
            'controller' => 'voucher',
            'totalRecord' => Voucher::count(),
        ];
        $this->keyword = null;
    }

    public function voucher()
    {
        $user = Auth::user();

        return view('report.voucher.view', [
            'extend' => $this->extend
        ]);
    }

    /**
     * Obtener listado de periodistas con filtros y paginación
     */
    public function listVouchers(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Voucher::with(['journalist.person'])->byFilial(Auth::user());

        // Filtro de búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('journalist', function ($subQ) use ($search) {
                    $subQ->where('firstname', 'ILIKE', "%{$search}%")
                        ->orWhere('lastname_father', 'ILIKE', "%{$search}%")
                        ->orWhere('lastname_mom', 'ILIKE', "%{$search}%")
                        ->orWhere('identify_number', 'ILIKE', "%{$search}%");
                });
            });
        }

        // Filtro de rango de fechas
        if ($dateFrom) {
            $query->where('datetime', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('datetime', '<=', $dateTo);
        }

        $vouchers = $query->orderBy('codvoucher', 'DESC')
            ->paginate($perPage);

        return response()->json($vouchers);
    }

    /**
     * Obtener estadísticas de periodistas
     */
    public function statistics()
    {
        $user = Auth::user();

        $total = Voucher::byFilial($user)->count();
        return response()->json([
            'total' => $total,
        ]);
    }

    /**
     * Exportar a PDF con FPDF - Se abre en nueva pestaña
     */
    public function exportPDF(Request $request)
    {
        $user = Auth::user();
        $query = $this->applyFilters($request, Voucher::byFilial($user)->with(['journalist.person']));
        $vouchers = $query->get();

        // Crear instancia de FPDF
        $pdf = new \FPDF('P', 'mm', array(80, 200)); // Formato tipo voucher: 80mm de ancho
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
        
        // Configuración de fuente
        $pdf->SetFont('Arial', 'B', 12);
        
        // Título
        $pdf->Cell(0, 8, 'REPORTE DE VOUCHERS', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, 'Generado: ' . now()->format('d/m/Y H:i'), 0, 1, 'C');
        $pdf->Ln(3);
        
        // Información de filtros
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 5, 'Total de Registros: ' . $vouchers->count(), 0, 1, 'L', true);
        
        if ($request->input('date_from')) {
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(0, 4, 'Desde: ' . \Carbon\Carbon::parse($request->input('date_from'))->format('d/m/Y'), 0, 1, 'L', true);
        }
        
        if ($request->input('date_to')) {
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(0, 4, 'Hasta: ' . \Carbon\Carbon::parse($request->input('date_to'))->format('d/m/Y'), 0, 1, 'L', true);
        }
        
        $pdf->Ln(4);
        
        // Recorrer vouchers
        foreach ($vouchers as $index => $voucher) {
            // Verificar si necesita nueva página
            if ($pdf->GetY() > 180) {
                $pdf->AddPage();
            }
            
            // Separador entre vouchers
            if ($index > 0) {
                $pdf->Ln(2);
                $pdf->SetDrawColor(200, 200, 200);
                $pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());
                $pdf->Ln(2);
            }
            
            // Información del periodista
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(30, 64, 175);
            $periodista = $voucher->journalist->affiliation_code . ' - ' . 
                         $voucher->journalist->person->firstname . ' ' . 
                         $voucher->journalist->person->lastname_father . ' ' . 
                         $voucher->journalist->person->lastname_mother;
            
            $pdf->MultiCell(0, 4, $periodista, 0, 'L');
            
            // Concepto
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(20, 4, 'Concepto:', 0, 0, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 4, $voucher->concepto ?? 'N/A', 0, 'L');
            
            // Monto
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetTextColor(22, 163, 74);
            $pdf->Cell(20, 5, 'Monto:', 0, 0, 'L');
            $pdf->Cell(0, 5, 'S/. ' . number_format($voucher->monto, 2), 0, 1, 'L');
            
            // Fecha
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(20, 4, 'Fecha:', 0, 0, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $fecha = $voucher->datetime ? 
                     \Carbon\Carbon::parse($voucher->datetime)->format('d/m/Y') : 
                     'N/A';
            $pdf->Cell(0, 4, $fecha, 0, 1, 'L');
        }
        
        // Footer
        $pdf->SetY(-15);
        $pdf->SetFont('Arial', 'I', 6);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, env('APP_NAME') . ' - Reporte de Vouchers', 0, 0, 'C');
        
        // Output inline para que se abra en el navegador
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="vouchers_' . time() . '.pdf"');
    }

    /**
     * Exportar a Excel (CSV alternativo)
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $query = $this->applyFilters($request, Voucher::byFilial($user)->with(['journalist.person', 'filial']));
        $vouchers = $query->get();

        $filename = 'reporte_vouchers_' . time() . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($vouchers) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Encabezados
            fputcsv($file, [
                'Código | Periodista',
                'Concepto',
                'Monto (S/.)',
                'Fecha',
                'Filial'
            ], ';');

            // Datos
            foreach ($vouchers as $voucher) {
                fputcsv($file, [
                    $voucher->journalist->affiliation_code . ' | ' . 
                    ($voucher->journalist->person->firstname . ' ' . 
                     $voucher->journalist->person->lastname_father . ' ' . 
                     $voucher->journalist->person->lastname_mom) ?? 'N/A',
                    $voucher->concepto,
                    $voucher->monto,
                    $voucher->datetime ? 
                        \Carbon\Carbon::parse($voucher->datetime)->format('d/m/Y') : 
                        'N/A',
                    $voucher->filial->name_large ?? 'N/A'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Aplicar filtros a la consulta
     */
    private function applyFilters(Request $request, $query)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('concepto', 'ILIKE', "%{$search}%")
                    ->orWhere('firstname', 'ILIKE', "%{$search}%")
                    ->orWhere('monto', 'ILIKE', "%{$search}%");
            });
        }

        if ($dateFrom) {
            $query->where('datetime', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('datetime', '<=', $dateTo);
        }

        return $query->orderBy('codvoucher', 'DESC');
    }
}
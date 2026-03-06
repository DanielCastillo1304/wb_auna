<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\System\Journalist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JournalistExport;
use App\Models\Filial;
use App\Models\System\Periods;

use function Symfony\Component\String\u;

class RJournalistController extends Controller
{
    public $extend = null;
    public $keyword;
    protected $perPage = 10;

    public function __construct()
    {
        $this->middleware('module.permission:listar')->only('index');
        $this->extend = [
            'title' => 'Reporte Periodistas',
            'controller' => 'journalist',
            'totalRecord' => Journalist::count(),
        ];
        $this->keyword = null;
    }

    public function journalist()
    {
        $user = Auth::user();
        $periods = Periods::orderBy('name_year', 'DESC')->get();

        return view('report.journalist.view', [
            'extend' => $this->extend,
            'periods' => $periods,
            'filials' => Filial::orderBy('name_large')->get(),
        ]);
    }

    /**
     * Obtener listado de periodistas con filtros y paginación
     */
    public function listJournalists(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $period = $request->input('period');
        $filial = $request->input('filial');
        // $dateFrom = $request->input('date_from');
        // $dateTo = $request->input('date_to');

        if ($filial == '') {
            $query = Journalist::with(['person', 'media'])->byFilial(Auth::user());
        } else {
            $query = Journalist::with(['person', 'media'])->where('codfilial', $filial);
        }
        // Filtro de búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('person', function ($subQ) use ($search) {
                    $subQ->where('firstname', 'ILIKE', "%{$search}%")
                        ->orWhere('lastname_father', 'ILIKE', "%{$search}%")
                        ->orWhere('lastname_mom', 'ILIKE', "%{$search}%")
                        ->orWhere('identify_number', 'ILIKE', "%{$search}%");
                })
                    ->orWhere('affiliation_code', 'ILIKE', "%{$search}%")
                    ->orWhere('codjournalist', 'ILIKE', "%{$search}%");
            });
        }

        // Filtro de estado
        if ($status) {
            $query->where('affiliation_status', $status);
        }
        // Filtro de periodo
        if ($period) {
            $query->where('codperiod', $period);
        }

        // Filtro de rango de fechas
        // if ($dateFrom) {
        //     $query->where('affiliation_date', '>=', $dateFrom);
        // }

        // if ($dateTo) {
        //     $query->where('affiliation_date', '<=', $dateTo);
        // }

        $journalists = $query->orderBy('codjournalist', 'DESC')
            ->paginate($perPage);

        return response()->json($journalists);
    }

    /**
     * Obtener estadísticas de periodistas
     */
    public function statistics()
    {
        $user = Auth::user();

        $total = Journalist::byFilial($user)->count();
        $active = Journalist::byFilial($user)->where('affiliation_status', 'Y')->count();
        $inactive = Journalist::byFilial($user)->where('affiliation_status', 'N')->count();
        $suspended = Journalist::byFilial($user)->where('affiliation_status', 'S')->count();

        return response()->json([
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'suspended' => $suspended
        ]);
    }


    /**
     * Exportar a PDF con FPDF - Laravel 8 compatible
     */
    public function exportPDF(Request $request)
    {
        $user = Auth::user();

        // Determinar filial
        if ($request->input('filial') != '') {
            $filial = Filial::where('codfilial', $request->input('filial'))->first();
            $filialName = strtoupper($filial->name_large ?? 'N/A');
            $filialShort = strtoupper($filial->name_short ?? 'N/A');
        } else {
            $filialName = $user->is_super === 'Y'
                ? 'TODAS LAS FILIALES'
                : strtoupper($user->filial->name_large ?? 'N/A');
            $filialShort = $user->is_super === 'Y'
                ? 'TODAS'
                : strtoupper($user->filial->name_short ?? 'N/A');
        }

        // Obtener datos filtrados
        $query = $this->applyFilters($request, Journalist::byFilial($user)->with(['person', 'media', 'filial', 'period']));
        $journalists = $query->get();

        // Estadísticas
        $totalActivos = $journalists->where('affiliation_status', 'Y')->count();
        $totalInactivos = $journalists->where('affiliation_status', 'N')->count();
        $totalSuspendidos = $journalists->where('affiliation_status', 'S')->count();
        $totalGeneral = $journalists->count();

        // === PALETA PROFESIONAL (tonos equilibrados, alto contraste) ===
        $colors = [
            'black'   => [0, 0, 0],    // Azul institucional profundo
            'primary'   => [25, 55, 109],    // Azul institucional profundo
            'accent'    => [41, 128, 185],   // Azul destacado
            'success'   => [39, 174, 96],    // Verde profesional
            'warning'   => [241, 196, 15],   // Amarillo suave (mejor legibilidad)
            'danger'    => [231, 76, 60],    // Rojo elegante
            'border'    => [220, 225, 228],  // Gris muy claro
            'text_main' => [40, 45, 50],     // Negro suave para texto
            'text_meta' => [108, 117, 125],  // Gris medio
            'bg_header' => [248, 249, 250],  // Fondo header tabla
            'bg_row'    => [252, 253, 254],  // Fondo filas alternas
        ];

        // === CLASE PDF PERSONALIZADA ===
        $pdf = new class('L', 'mm', 'A4') extends \FPDF {
            public $filialName;
            public $filialShort;
            public $colors;

            function Header()
            {
                // Logo (centrado verticalmente con texto)
                $logoPath = public_path('img/logo.png');
                if (file_exists($logoPath)) {
                    $this->Image($logoPath, 15, 8, 17);
                }

                // Título principal (más grueso)
                $this->SetFont('Arial', 'B', 18);
                $this->SetTextColor(
                    $this->colors['black'][0],
                    $this->colors['black'][1],
                    $this->colors['black'][2]
                );

                $x = 36;
                $y = 13;

                // Dibujo repetido para simular grosor
                $this->SetXY($x, $y);
                $this->Cell(0, 6, utf8_decode('CÍRCULO DE PERIODISTAS DEPORTIVOS DEL PERÚ'), 0, 1, 'L');
                $this->SetXY($x + 0.3, $y);
                $this->Cell(0, 6, utf8_decode('CÍRCULO DE PERIODISTAS DEPORTIVOS DEL PERÚ'), 0, 1, 'L');

                // Subtítulo
                $this->SetFont('Arial', '', 6.9);
                $this->SetTextColor($this->colors['text_meta'][0], $this->colors['text_meta'][1], $this->colors['text_meta'][2]);
                $this->SetX(36.5);
                $this->Cell(0, 5, utf8_decode('ÚNICA ENTIDAD QUE AGRUPA AL PERIODISTA DEPORTIVO PROFESIONAL PERUANO CON RECONOCIMIENTO INTERNACIONAL DE AIPS'), 0, 1, 'L');

                // Badge filial (esquina superior derecha)
                $this->SetFont('Arial', 'B', 8);
                $badgeText = utf8_decode('FILIAL: ' . $this->filialName);

                // Calcular ancho del texto
                $badgeWidth = $this->GetStringWidth($badgeText) + 6;

                // Obtener ancho de la página
                $pageWidth = $this->GetPageWidth();

                // Margen derecho (ajústalo a tu gusto)
                $rightMargin = 15;

                // Posición X dinámica (anclado a la derecha)
                $x = $pageWidth - $rightMargin - $badgeWidth;

                // Posición Y fija
                $this->SetXY($x, 15);

                // Estilos
                $this->SetFillColor(
                    $this->colors['primary'][0],
                    $this->colors['primary'][1],
                    $this->colors['primary'][2]
                );
                $this->SetTextColor(255, 255, 255);

                // Dibujar badge
                $this->Cell($badgeWidth, 5, $badgeText, 0, 0, 'C', true);

                // Separador elegante
                $this->SetDrawColor($this->colors['primary'][0], $this->colors['primary'][1], $this->colors['primary'][2]);
                $this->SetLineWidth(0.6);
                $this->Line(15, 30, 282, 30);
                //  $this->SetDrawColor($this->colors['accent'][0], $this->colors['accent'][1], $this->colors['accent'][2]);
                //  $this->SetLineWidth(0.3);
                //  $this->Line(15, 31, 282, 31);
                $this->Ln(18);
            }

            function Footer()
            {
                $this->SetY(-15);
                $this->SetDrawColor($this->colors['border'][0], $this->colors['border'][1], $this->colors['border'][2]);
                $this->SetLineWidth(0.3);
                $this->Line(15, $this->GetY(), 282, $this->GetY());

                $this->SetFont('Arial', '', 7);
                $this->SetTextColor($this->colors['text_meta'][0], $this->colors['text_meta'][1], $this->colors['text_meta'][2]);
                $this->Cell(90, 6, utf8_decode(config('app.name') . ' © ' . date('Y')), 0, 0, 'L');
                $this->Cell(90, 6, utf8_decode('Documento generado electrónicamente'), 0, 0, 'C');
                $this->Cell(90, 6, utf8_decode('Página ' . $this->PageNo() . ' de {nb}'), 0, 0, 'R');
            }
        };

        // Configuración inicial
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 22);
        $pdf->SetMargins(15, 18, 15);
        $pdf->filialName = $filialName;
        $pdf->filialShort = $filialShort;
        $pdf->colors = $colors;
        $pdf->AddPage();

        // === TÍTULO PRINCIPAL DEL REPORTE ===
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor($colors['black'][0], $colors['black'][1], $colors['black'][2]);
        $pdf->Cell(0, 5, utf8_decode('REPORTE DETALLADO DE PERIODISTAS'), 0, 0, 'L');

        // === METADATOS ===
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor($colors['text_meta'][0], $colors['text_meta'][1], $colors['text_meta'][2]);
        $pdf->Cell(0, 5, utf8_decode("Fecha: " . date('d/m/Y H:i')), 0, 1, 'R');
        $pdf->Ln(3);

        // === RESUMEN EJECUTIVO (una sola línea) ===
        // Configuración de fuente y color para el resumen
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor($colors['text_main'][0], $colors['text_main'][1], $colors['text_main'][2]);

        // Construcción del resumen ejecutivo
        $resumen = sprintf(
            'Resumen ejecutivo: Total registrados %s | Habilitados %s | Inhabilitados %s',
            number_format($totalGeneral, 0, ',', '.'),
            number_format($totalActivos, 0, ',', '.'),
            number_format($totalInactivos, 0, ',', '.')
        );

        // Renderizado del texto
        $pdf->Cell(0, 6, mb_convert_encoding($resumen, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->Ln(4);


        // === ENCABEZADO DE TABLA ===
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetFillColor($colors['primary'][0], $colors['primary'][1], $colors['primary'][2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetDrawColor($colors['border'][0], $colors['border'][1], $colors['border'][2]);

        // Columnas optimizadas (total 267mm)
        $cols = [
            ['w' => 16, 't' => 'CÓDIGO', 'a' => 'C'],
            ['w' => 18, 't' => 'DNI', 'a' => 'C'],
            ['w' => 68, 't' => 'APELLIDOS Y NOMBRES', 'a' => 'L'],
            ['w' => 40, 't' => 'FILIAL', 'a' => 'C'],
            ['w' => 14, 't' => 'PERIODO', 'a' => 'C'],
            ['w' => 61, 't' => 'MEDIO DE COMUNICACIÓN', 'a' => 'C'],
            ['w' => 20, 't' => 'F. AFIL.', 'a' => 'C'],
            ['w' => 30, 't' => 'ESTADO', 'a' => 'C'],
        ];

        foreach ($cols as $col) {
            $pdf->Cell($col['w'], 9, utf8_decode($col['t']), 0, 0, $col['a'], true);
        }
        $pdf->Ln();

        // Línea separadora debajo del header
        // $pdf->SetDrawColor($colors['primary'][0], $colors['primary'][1], $colors['primary'][2]);
        // $pdf->SetLineWidth(0.5);
        // $pdf->Line(15, $pdf->GetY(), 282, $pdf->GetY());
        // $pdf->SetLineWidth(0.2);
        // $pdf->SetDrawColor($colors['border'][0], $colors['border'][1], $colors['border'][2]);
        // $pdf->Ln(1);

        // === CUERPO DE TABLA ===
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor($colors['text_main'][0], $colors['text_main'][1], $colors['text_main'][2]);
        $fill = false;
        $statusConfig = [
            'Y' => ['label' => 'HABILITADO', 'color' => $colors['success']],
            'N' => ['label' => 'INHABILITADO', 'color' => $colors['danger']],
            'S' => ['label' => 'SUSP.', 'color' => $colors['warning']],
        ];

        foreach ($journalists as $j) {
            if ($pdf->GetY() > 185) {
                $pdf->AddPage();
                // Repetir header de tabla (simplificado para brevedad en ejemplo)
                // En implementación real: extraer header a función reutilizable
            }

            // Alternar fondo
            $pdf->SetFillColor(
                $fill ? $colors['bg_row'][0] : 255,
                $fill ? $colors['bg_row'][1] : 255,
                $fill ? $colors['bg_row'][2] : 255
            );

            // Código
            $pdf->Cell(16, 7, utf8_decode($j->affiliation_code ?? '---'), 0, 0, 'C', $fill);
            // DNI
            $pdf->Cell(18, 7, utf8_decode($j->person->identify_number ?? '---'), 0, 0, 'C', $fill);
            // Nombre completo
            $fullName = trim(implode(' ', [
                strtoupper($j->person->lastname_father ?? ''),
                strtoupper($j->person->lastname_mom ?? ''),
                strtoupper($j->person->firstname ?? '')
            ]));
            if (mb_strlen($fullName) > 48) {
                $fullName = mb_substr($fullName, 0, 45) . '...';
            }
            $pdf->Cell(68, 7, utf8_decode($fullName), 0, 0, 'L', $fill);
            // Filial
            $filialTxt = ($user->is_super === 'Y' && $j->filial)
                ? strtoupper($j->filial->name_large ?? 'N/A')
                : $filialShort;
            $pdf->Cell(40, 7, utf8_decode($filialTxt), 0, 0, 'C', $fill);
            $pdf->Cell(14, 7, utf8_decode($j->period->name_year), 0, 0, 'C', $fill);
            // Medio
            $media = $j->media->name_media ?? 'No asignado';
            if (mb_strlen($media) > 42) {
                $media = mb_substr($media, 0, 39) . '...';
            }
            $pdf->Cell(63, 7, utf8_decode($media), 0, 0, 'C', $fill);
            // Fecha afiliación
            $fecha = $j->affiliation_date ? date('d/m/Y', strtotime($j->affiliation_date)) : '---';
            $pdf->Cell(20, 7, $fecha, 0, 0, 'C', $fill);
            // Estado (con color específico)
            $st = $statusConfig[$j->affiliation_status ?? ''] ?? ['label' => 'N/D', 'color' => $colors['text_meta']];
            $pdf->SetTextColor($st['color'][0], $st['color'][1], $st['color'][2]);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 7, utf8_decode($st['label']), 0, 0, 'C', $fill);
            $pdf->SetTextColor($colors['text_main'][0], $colors['text_main'][1], $colors['text_main'][2]);

            $fill = !$fill;
            $pdf->Ln();
        }


        // Generar PDF
        $filename = 'Reporte_Periodistas_' . str_replace(' ', '_', $filialShort) . '_' . date('Ymd_His') . '.pdf';

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate')
            ->header('Pragma', 'public');
    }
    /**
     * Exportar a Excel con formato profesional (PhpSpreadsheet)
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();

        // ─── Determinar filial (mismo criterio que exportPDF) ───
        if ($request->input('filial') != '') {
            $filial = Filial::where('codfilial', $request->input('filial'))->first();
            $filialName  = strtoupper($filial->name_large  ?? 'N/A');
            $filialShort = strtoupper($filial->name_short  ?? 'N/A');
        } else {
            $filialName  = $user->is_super === 'Y' ? 'TODAS LAS FILIALES' : strtoupper($user->filial->name_large  ?? 'N/A');
            $filialShort = $user->is_super === 'Y' ? 'TODAS'              : strtoupper($user->filial->name_short  ?? 'N/A');
        }

        // ─── Datos filtrados ───
        $query = $this->applyFilters(
            $request,
            Journalist::byFilial($user)->with(['person', 'media', 'filial', 'period'])
        );
        $journalists = $query->get();

        // ─── Estadísticas ───
        $totalGeneral    = $journalists->count();
        $totalActivos    = $journalists->where('affiliation_status', 'Y')->count();
        $totalInactivos  = $journalists->where('affiliation_status', 'N')->count();
        $totalSuspendidos = $journalists->where('affiliation_status', 'S')->count();

        // ════════════════════════════════════════════════════════
        // PALETA (misma que exportPDF)
        // ════════════════════════════════════════════════════════
        $C = [
            'primary'        => '19376D',
            'accent'         => '2980B9',
            'success'        => '27AE60',
            'warning'        => 'F39C12',
            'danger'         => 'E74C3C',
            'border'         => 'DCE1E4',
            'text_main'      => '282D32',
            'text_meta'      => '6C7581',
            'bg_summary'     => 'EBF0F7',
            'bg_row_alt'     => 'F4F6F8',
            'white'          => 'FFFFFF',
        ];

        // ════════════════════════════════════════════════════════
        // SPREADSHEET
        // ════════════════════════════════════════════════════════
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('Reporte Periodistas');
        $ws->setShowGridlines(false);

        // ─── Helper: aplicar estilo a un rango ───
        $style = function (string $range, array $styleArray) use ($ws) {
            $ws->getStyle($range)->applyFromArray($styleArray);
        };

        // ─── Helper: borde fino ───
        $thinBorder = [
            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => $C['border']],
        ];

        // ══════════════════════════════════
        // FILA 1 — Título institucional
        // ══════════════════════════════════
        $ws->mergeCells('A1:H1');
        $ws->getCell('A1')->setValue('CÍRCULO DE PERIODISTAS DEPORTIVOS DEL PERÚ');
        $style('A1:H1', [
            'font'      => ['name' => 'Arial', 'size' => 14, 'bold' => true, 'color' => ['rgb' => $C['primary']]],
            'alignment' => ['horizontal' => 'left', 'vertical' => 'center'],
        ]);
        $ws->getRowDimension(1)->setRowHeight(22);

        // ══════════════════════════════════
        // FILA 2 — Subtítulo + fecha
        // ══════════════════════════════════
        $ws->mergeCells('A2:E2');
        $ws->getCell('A2')->setValue('REPORTE DETALLADO DE PERIODISTAS');
        $style('A2:E2', [
            'font'      => ['name' => 'Arial', 'size' => 11, 'bold' => true, 'color' => ['rgb' => $C['text_main']]],
            'alignment' => ['horizontal' => 'left', 'vertical' => 'center'],
        ]);

        $ws->mergeCells('F2:H2');
        $ws->getCell('F2')->setValue('Generado: ' . now()->format('d/m/Y H:i'));
        $style('F2:H2', [
            'font'      => ['name' => 'Arial', 'size' => 9, 'color' => ['rgb' => $C['text_meta']]],
            'alignment' => ['horizontal' => 'right', 'vertical' => 'center'],
        ]);
        $ws->getRowDimension(1)->setRowHeight(18);


        // ══════════════════════════════════
        // FILA 3 — Espacio
        // ══════════════════════════════════
        $ws->getRowDimension(1)->setRowHeight(6);

        // ══════════════════════════════════
        // FILAS 4-5 — Resumen ejecutivo (4 cajas)
        // ══════════════════════════════════
        $summaryData = [
            ['cols' => 'A', 'label' => 'TOTAL REGISTRADOS',  'value' => $totalGeneral,     'color' => $C['primary']],
            ['cols' => 'C', 'label' => 'HABILITADOS',        'value' => $totalActivos,     'color' => $C['success']],
            ['cols' => 'E', 'label' => 'INHABILITADOS',      'value' => $totalInactivos,   'color' => $C['danger']],
            // ['cols' => 'G', 'label' => 'SUSPENDIDOS',        'value' => $totalSuspendidos, 'color' => $C['warning']],
        ];

        foreach ($summaryData as $s) {
            $c1 = $s['cols'];
            $c2 = chr(ord($c1) + 1); // A→B, C→D, E→F, G→H

            // Valor grande (fila 4)
            $ws->mergeCells("{$c1}4:{$c2}4");
            $ws->getCell("{$c1}4")->setValue($s['value']);
            $style("{$c1}4:{$c2}4", [
                'font'      => ['name' => 'Arial', 'size' => 20, 'bold' => true, 'color' => ['rgb' => $s['color']]],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => $C['bg_summary']]],
                'borders'   => ['bottom' => ['style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => $s['color']]]],
            ]);

            // Etiqueta (fila 5)
            $ws->mergeCells("{$c1}5:{$c2}5");
            $ws->getCell("{$c1}5")->setValue($s['label']);
            $style("{$c1}5:{$c2}5", [
                'font'      => ['name' => 'Arial', 'size' => 7.5, 'bold' => true, 'color' => ['rgb' => $C['text_meta']]],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => $C['bg_summary']]],
            ]);
        }
        $ws->getRowDimension(1)->setRowHeight(18);

        $ws->getRowDimension(1)->setRowHeight(14);

        // ══════════════════════════════════
        // FILA 6 — Espacio
        // ══════════════════════════════════
        $ws->getRowDimension(1)->setRowHeight(8);

        // ══════════════════════════════════
        // FILA 7 — Header de tabla
        // ══════════════════════════════════
        $headers = [
            ['col' => 'A', 'txt' => 'CÓDIGO',                 'align' => 'center', 'width' => 13],
            ['col' => 'B', 'txt' => 'DNI',                    'align' => 'center', 'width' => 14],
            ['col' => 'C', 'txt' => 'APELLIDOS Y NOMBRES',   'align' => 'left',   'width' => 42],
            ['col' => 'D', 'txt' => 'FILIAL',                 'align' => 'center', 'width' => 28],
            ['col' => 'E', 'txt' => 'PERIODO',                'align' => 'center', 'width' => 12],
            ['col' => 'F', 'txt' => 'MEDIO DE COMUNICACIÓN', 'align' => 'center', 'width' => 40],
            ['col' => 'G', 'txt' => 'F. AFIL.',               'align' => 'center', 'width' => 14],
            ['col' => 'H', 'txt' => 'ESTADO',                 'align' => 'center', 'width' => 18],
        ];

        foreach ($headers as $h) {
            $ws->getCell("{$h['col']}7")->setValue($h['txt']);
            $ws->getColumnDimension($h['col'])->setWidth($h['width']);
        }
        $style('A7:H7', [
            'font'      => ['name' => 'Arial', 'size' => 8.5, 'bold' => true, 'color' => ['rgb' => $C['white']]],
            'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => $C['primary']]],
            'alignment' => ['vertical' => 'center'],
            'borders'   => [
                'allBorders' => $thinBorder,
            ],
        ]);
        // Alineaciones individuales del header
        foreach ($headers as $h) {
            $ws->getCell("{$h['col']}7")->getStyle()->getAlignment()->setHorizontal($h['align']);
        }
        $ws->getRowDimension(1)->setRowHeight(20);

        // ═══════════════════════════════════════════════════════
        // CUERPO DE TABLA (desde fila 8)
        // ═══════════════════════════════════════════════════════
        $statusLabels = ['Y' => 'HABILITADO', 'N' => 'INHABILITADO'];
        $statusColors = ['Y' => $C['success'], 'N' => $C['danger']];
        $row = 8;

        foreach ($journalists as $j) {
            // Nombre completo (mismo formato que PDF)
            $fullName = trim(implode(' ', [
                strtoupper($j->person->lastname_father ?? ''),
                strtoupper($j->person->lastname_mom    ?? ''),
                strtoupper($j->person->firstname       ?? ''),
            ]));

            // Filial
            $filialTxt = ($user->is_super === 'Y' && $j->filial)
                ? strtoupper($j->filial->name_large ?? 'N/A')
                : $filialShort;

            // Fecha
            $fecha = $j->affiliation_date
                ? \Carbon\Carbon::parse($j->affiliation_date)->format('d/m/Y')
                : '---';

            // Estado
            $st = $j->affiliation_status ?? '';

            // ─── Escribir celdas ───
            $ws->getCell("A{$row}")->setValue($j->affiliation_code ?? '---');
            $ws->getCell("B{$row}")->setValue($j->person->identify_number ?? '---');
            $ws->getCell("C{$row}")->setValue($fullName ?: '---');
            $ws->getCell("D{$row}")->setValue($filialTxt);
            $ws->getCell("E{$row}")->setValue($j->period->name_year ?? '---');
            $ws->getCell("F{$row}")->setValue($j->media->name_media ?? 'No asignado');
            $ws->getCell("G{$row}")->setValue($fecha);
            $ws->getCell("H{$row}")->setValue($statusLabels[$st] ?? 'N/D');

            // ─── Estilo base fila ───
            $isAlt = (($row - 8) % 2 === 1);
            $rowStyle = [
                'font'      => ['name' => 'Arial', 'size' => 8.5, 'color' => ['rgb' => $C['text_main']]],
                'alignment' => ['vertical' => 'center'],
                'borders'   => ['allBorders' => $thinBorder],
            ];
            if ($isAlt) {
                $rowStyle['fill'] = ['fillType' => 'solid', 'startColor' => ['rgb' => $C['bg_row_alt']]];
            }
            $style("A{$row}:H{$row}", $rowStyle);

            // ─── Alineaciones por columna ───
            $ws->getCell("A{$row}")->getStyle()->getAlignment()->setHorizontal('center');
            $ws->getCell("B{$row}")->getStyle()->getAlignment()->setHorizontal('center');
            $ws->getCell("C{$row}")->getStyle()->getAlignment()->setHorizontal('left');
            $ws->getCell("D{$row}")->getStyle()->getAlignment()->setHorizontal('center');
            $ws->getCell("E{$row}")->getStyle()->getAlignment()->setHorizontal('center');
            $ws->getCell("F{$row}")->getStyle()->getAlignment()->setHorizontal('center');
            $ws->getCell("G{$row}")->getStyle()->getAlignment()->setHorizontal('center');
            $ws->getCell("H{$row}")->getStyle()->getAlignment()->setHorizontal('center');

            // ─── Color del estado (columna H) ───
            $ws->getCell("H{$row}")->getStyle()->getFont()->setBold(true);
            $ws->getCell("H{$row}")->getStyle()->getFont()->setColor(
                new \PhpOffice\PhpSpreadsheet\Style\Color($statusColors[$st] ?? $C['text_meta'])
            );

            $ws->getRowDimension(1)->setRowHeight(16);

            $row++;
        }

        // ─── Congelar paneles (header siempre visible) ───
        $ws->freezePane('A8');

        // ─── Generar y devolver ───
        $filename = 'Reporte_Periodistas_' . str_replace(' ', '_', $filialShort) . '_' . date('Ymd_His') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }

    /**
     * Aplicar filtros a la consulta
     */
    private function applyFilters(Request $request, $query)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $period = $request->input('period');
        $filial = $request->input('filial');
        // $dateFrom = $request->input('date_from');
        // $dateTo = $request->input('date_to');

        // $dateFrom = $request->input('date_from');
        // $dateTo = $request->input('date_to');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('person', function ($subQ) use ($search) {
                    $subQ->where('firstname', 'ILIKE', "%{$search}%")
                        ->orWhere('lastname_father', 'ILIKE', "%{$search}%")
                        ->orWhere('lastname_mom', 'ILIKE', "%{$search}%")
                        ->orWhere('identify_number', 'ILIKE', "%{$search}%");
                })
                    ->orWhere('affiliation_code', 'ILIKE', "%{$search}%")
                    ->orWhere('codjournalist', 'ILIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('affiliation_status', $status);
        }
        if ($period) {
            $query->where('codperiod', $period);
        }
        if ($filial == '') {
            $query->byFilial(Auth::user());
        } else {
            $query->where('codfilial', $filial);
        }
        // if ($dateFrom) {
        //     $query->where('affiliation_date', '>=', $dateFrom);
        // }

        // if ($dateTo) {
        //     $query->where('affiliation_date', '<=', $dateTo);
        // }

        return $query->orderBy('codjournalist', 'DESC');
    }
}

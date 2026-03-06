<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class CrudController extends Controller
{
    // Cada módulo define estos valores
    protected string $model;          // App\Models\maintenance\EquipmentType
    protected string $view;           // equipment_type
    protected string $primaryKey;     // codequipment_type
    protected int    $perPage = 10;
    protected array  $extend  = [];

    /**
     * Reglas de validación — cada módulo las sobreescribe
     */
    abstract protected function rules($id = null): array;

    /**
     * Campos a guardar — por defecto todo excepto _token
     * El módulo puede sobreescribir para procesar archivos, etc.
     */
    protected function prepareData(Request $request, $id = null): array
    {
        return $request->except(['_token', '_method']);
    }

    /**
     * Hook post-guardado — el módulo puede sobreescribir
     */
    protected function afterStore($record, bool $isUpdate): void {}

    /**
     * Hook post-eliminación — el módulo puede sobreescribir
     */
    protected function afterDestroy($record): void {}

    /**
     * Campos de búsqueda — el módulo define cuáles columnas buscar
     */
    protected function searchFields(): array
    {
        return ['name'];
    }

    /** LIST **/
    public function index()
    {
        return view("{$this->view}.list", [
            'extend' => $this->extend,
        ]);
    }

    /** FORM **/
    public function form($id = null)
    {
        $record = $id ? $this->model::find($id) : null;
        $viewData = $this->formViewData($record);

        return view("{$this->view}.form", array_merge([
            'extend' => $this->extend,
            $this->getModelKey() => $record,
        ], $viewData));
    }

    /**
     * Datos extra para la vista del form — el módulo puede sobreescribir
     */
    protected function formViewData($record): array
    {
        return [];
    }

    /** STORE **/
    public function store(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), $this->rules($id));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $data = $this->prepareData($request, $id);

            if ($id) {
                $record = $this->model::findOrFail($id);
                $record->update($data);
                $message = 'Registro actualizado correctamente';
            } else {
                $record = $this->model::create($data);
                $message = 'Registro creado correctamente';
            }

            $this->afterStore($record, (bool) $id);

            return response()->json([
                'success'      => true,
                'message'      => $message,
                'data'         => $record,
                'totalRecords' => $this->model::count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /** RECORDS (paginación AJAX) **/
    public function records($from, $to, $keyword = 'null')
    {
        $query = $this->baseQuery();

        if ($keyword && $keyword !== 'null') {
            $this->applySearch($query, $keyword);
        }

        $total = $query->count();
        $data  = $query->skip($from)->take($to - $from)->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
            'total'   => $total,
            'from'    => $from,
            'to'      => $to,
        ]);
    }

    /** SEARCH **/
    public function search(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $query   = $this->baseQuery();

        if (!empty($keyword)) {
            $this->applySearch($query, $keyword);
        }

        $total = $query->count();
        $data  = $query->limit($this->perPage)->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
            'total'   => $total,
            'keyword' => $keyword,
        ]);
    }

    /** DESTROY **/
    public function destroy($id)
    {
        try {
            $record = $this->model::findOrFail($id);
            $this->afterDestroy($record);
            $record->delete();

            return response()->json([
                'success'      => true,
                'message'      => 'Registro eliminado correctamente',
                'totalRecords' => $this->model::count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /** HELPERS INTERNOS **/
    protected function baseQuery()
    {
        return $this->model::orderBy($this->primaryKey, 'DESC');
    }

    protected function applySearch($query, string $keyword): void
    {
        $fields = $this->searchFields();
        $query->where(function ($q) use ($keyword, $fields) {
            foreach ($fields as $i => $field) {
                $method = $i === 0 ? 'where' : 'orWhere';
                $q->$method($field, 'ILIKE', "%{$keyword}%");
            }
        });
    }

    // Convierte "App\Models\maintenance\EquipmentType" → "equipment_type"
    protected function getModelKey(): string
    {
        $class = class_basename($this->model);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class));
    }
}
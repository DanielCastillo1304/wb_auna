<?php

namespace App\Http\Controllers;

use App\Models\Filial;
use App\Models\Person;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public $extend = null;
    public $keyword;
    protected $perPage = 10;

    public function __construct()
    {
        $this->middleware('module.permission:listar')->only('index');
        $this->middleware('module.permission:editar')->only('form');
        $this->middleware('module.permission:crear')->only(['store']);
        $this->middleware('module.permission:eliminar')->only('destroy');

        $this->extend = [
            'title' => 'Usuarios',
            'title_form' => 'Usuario',
            'controller' => 'user',
            'totalRecord' => User::count(),
        ];
        $this->keyword = null;
    }

    public function index()
    {
        $user = Auth::user();

        $query = User::orderBy('coduser', 'DESC');
        if ($user->is_super !== 'Y') {

            $query->where('codprofile', '!=', 1);
            $query->where('codfilial', $user->codfilial);
        }

        $data = $query->limit($this->perPage)->get();

        return view('user.list', [
            'extend' => $this->extend,
            'data' => $data
        ]);
    }

    public function password()
    {

        return view('user.password', [
            'user' => Auth::user(),
            'extend' => $this->extend
        ]);
    }

    public function change_password(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'actually_password' => ['required'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Hash::check($request->actually_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual no es correcta'
            ], 422);
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar contraseña: ' . $e->getMessage()
            ], 500);
        }
    }

    public function form($id = null)
    {
        $user = $id ? User::find($id) : null;
        // Traer todos los perfiles si el usuario loqueado el campo is_super es Y
        $profiles = Auth::user()->is_super == 'Y'
            ? Profile::all()
            : Profile::where('name_short', '!=', 'suadmin')
            ->where('codprofile', '!=', 1)
            ->get();
        $persons = Person::get();
        $filials = Filial::get();

        return view('user.form', [
            'extend' => $this->extend,
            'user' => $user,
            'profiles' => $profiles,
            'persons' => $persons,
            'filials' => $filials
        ]);
    }

    public function store(Request $request, $id = null)
    {
        $rules = [
            'codprofile' => ['required'],
            'codperson' => ['required'],
            'codfilial' => ['required'],
            'username' => [
                'required',
                'string',
                'max:250',
                Rule::unique('user', 'username')->ignore($id, 'coduser')
            ],
        ];

        if ($id) {
            $rules['password'] = ['nullable', 'string', 'confirmed', 'min:8'];
        } else {
            $rules['password'] = ['required', 'string', 'confirmed', 'min:8'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['photo', '_token', 'password', 'password_confirmation']);

            $data['is_super'] = ($request->codprofile == 1) ? 'Y' : 'N';

            if ($id) {
                $user = User::findOrFail($id);

                if ($request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                $user->update($data);
                $message = 'Registro actualizado correctamente';
            } else {
                $data['password'] = Hash::make($request->password);
                $user = User::create($data);
                $message = 'Registro creado correctamente';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $user,
                'totalRecords' => User::count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reset(Request $request)
    {
        $user = Auth::user();
        // Obtener la persona del usuario
        $person = Person::find($user->codperson);

        try {
            $user->update([
                'password' => Hash::make($person->identify_number)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña reseteada exitosamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error interno'], 500);
        }
    }

    public function records($from, $to, $keyword = 'null')
    {
        $user = Auth::user();

        $query = User::with('profile', 'person')->orderBy('coduser', 'DESC');

        // 🔥 Filtro para NO superadmins
        if ($user->is_super !== 'Y') {
            $query->where('codprofile', '!=', 1)
                ->where('codfilial', $user->codfilial);
        }

        // 🔍 Filtro por búsqueda
        if ($keyword && $keyword !== 'null') {
            $query->where(function ($q) use ($keyword) {
                $q->where('username', 'ILIKE', "%{$keyword}%")
                    ->orWhere('created_at', 'ILIKE', "%{$keyword}%");
            });
        }

        $total = $query->count();
        $data = $query->skip($from)->take($to - $from)->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $total,
            'from' => $from,
            'to' => $to
        ]);
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $keyword = $request->input('keyword', '');

        $query = User::with('profile', 'person')->orderBy('coduser', 'DESC');

        // 🔥 Filtro para NO superadmins
        if ($user->is_super !== 'Y') {
            $query->where('codprofile', '!=', 1)
                ->where('codfilial', $user->codfilial);
        }

        // 🔍 Búsqueda
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('username', 'ILIKE', "%{$keyword}%")
                    ->orWhere('created_at', 'ILIKE', "%{$keyword}%");
            });
        }

        $data = $query->limit($this->perPage)->get();
        $total = $query->count();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $total,
            'keyword' => $keyword
        ]);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registro no encontrado'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Impedir que un usuario se borre a sí mismo
            if ($user->coduser === Auth::id()) {
                return response()->json(['success' => false, 'message' => 'No puedes eliminar tu propio usuario'], 403);
            }

            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Eliminado',
                'totalRecords' => User::count()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar'], 500);
        }
    }
}

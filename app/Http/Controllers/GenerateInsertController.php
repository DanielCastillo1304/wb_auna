<?php

namespace App\Http\Controllers;

use App\Models\Security\Profile;
use App\Models\Security\User;
use App\Models\Security\Module;
use App\Models\Security\Permission;
use App\Models\Security\ProfilePermission;
use App\Models\Maintenance\BusinessUnit;
use App\Models\Maintenance\EquipmentType;
use App\Models\Maintenance\Location;
use App\Models\Maintenance\RamCapacity;
use App\Models\Maintenance\DiskCapacity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GenerateInsertController extends Controller
{
    public function generate()
    {
        try {
            DB::transaction(function () {

                $profile = $this->insertProfile();

                $this->insertModules();
                $this->insertPermissions();
                $this->insertProfilePermissions($profile->codprofile);

                // NUEVOS CATÁLOGOS
                $this->insertEquipmentTypes();
                $this->insertBusinessUnits();
                $this->insertLocations();
                $this->insertDiskCapacities();
                $this->insertRamCapacities();

                $this->insertUser($profile->codprofile);
            });

            return response()->json([
                'success' => true,
                'message' => 'Datos iniciales insertados correctamente'
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500, [], JSON_INVALID_UTF8_SUBSTITUTE);
        }
    }

    private function insertProfile()
    {
        return Profile::updateOrCreate(
            ['codprofile' => 1],
            [
                'name_large' => 'superadmin',
                'name_short' => 'suadmin'
            ]
        );
    }

    private function insertModules()
    {
        // Crear o actualizar módulo padre
        $parent = Module::updateOrCreate(
            ['name_short' => 'maintenance'],
            [
                'codmodule_parent' => null,
                'cod_system' => 1,
                'name_large' => 'Mantenimiento',
                'order' => 1,
                'route' => null,
                'icon' => 'settings'
            ]
        );

        $modules = [

            [
                'name_large' => 'Personal',
                'name_short' => 'personal',
                'order' => 1,
                'route' => 'personal.list'
            ],

            [
                'name_large' => 'Tipos de equipo',
                'name_short' => 'equipment_type',
                'order' => 2,
                'route' => 'equipment_type.list'
            ],

            [
                'name_large' => 'Unidad de negocio',
                'name_short' => 'business_unit',
                'order' => 3,
                'route' => 'business_unit.list'
            ],

            [
                'name_large' => 'Nuestras sedes',
                'name_short' => 'location',
                'order' => 4,
                'route' => 'location.list'
            ],

            [
                'name_large' => 'Capacidad de discos',
                'name_short' => 'disk_capacity',
                'order' => 5,
                'route' => 'disk_capacity.list'
            ],

            [
                'name_large' => 'Memorias RAM',
                'name_short' => 'ram_capacity',
                'order' => 6,
                'route' => 'ram_capacity.list'
            ],

            [
                'name_large' => 'Motivos devolución',
                'name_short' => 'return_reason',
                'order' => 7,
                'route' => 'return_reason.list'
            ],

        ];

        foreach ($modules as $module) {

            Module::updateOrCreate(
                ['name_short' => $module['name_short']],
                array_merge($module, [
                    'codmodule_parent' => $parent->codmodule,
                    'cod_system' => 1,
                    'icon' => 'zone_person_alert'
                ])
            );
        }
    }

    private function insertPermissions()
    {
        $modules = Module::pluck('codmodule', 'name_short');

        // permiso del módulo principal
        Permission::updateOrCreate(
            [
                'name' => 'ver mantenimiento'
            ],
            [
                'codmodule' => $modules['maintenance']
            ]
        );

        $moduleNames = [
            'personal' => 'personal',
            'equipment_type' => 'tipo de equipo',
            'business_unit' => 'unidad de negocio',
            'location' => 'sede',
            'disk_capacity' => 'capacidad de disco',
            'ram_capacity' => 'memoria ram',
            'return_reason' => 'motivo devolucion'
        ];

        $actions = ['listar', 'crear', 'editar', 'eliminar'];

        foreach ($moduleNames as $short => $label) {

            foreach ($actions as $action) {

                Permission::updateOrCreate(
                    [
                        'codmodule' => $modules[$short],
                        'name' => $action . ' ' . $label
                    ],
                    []
                );
            }
        }
    }



    private function insertProfilePermissions($profileId)
    {
        $permissions = Permission::pluck('codpermission');

        foreach ($permissions as $permissionId) {

            ProfilePermission::updateOrCreate([
                'codprofile' => $profileId,
                'codpermission' => $permissionId
            ]);
        }
    }

    private function insertEquipmentTypes()
    {
        $types = [
            'LAPTOP',
            'MICRO PC',
            'MACBOOK',
            'DESKTOP',
            'WORKSTATION',
            'ALL IN ONE',
            'ANEXO'
        ];

        foreach ($types as $name) {

            EquipmentType::updateOrCreate(
                ['name' => $name],
                ['name' => $name]
            );
        }
    }

    private function insertBusinessUnits()
    {
        $units = [

            'AUNA IDEAS',
            'CLINICA MIRAFLORES',
            'CLINICA VALLESUR S.A.',
            'CONSORCIO TRECCA',
            'GSP SERVICIOS COMERCIALES',
            'GSP SERVICIOS GENERALES',
            'GSP TRUJILLO',
            'LABORATORIO CANTELLA',
            'MEDIC SER',
            'ONCOCENTER PERU',
            'ONCOGENOMICS SAC',
            'ONCOSALUD',
            'PATOLOGIA ONCOLOGICA SAC',
            'R Y R PATOLOGOS ASOCIADOS',
            'SERVIMEDICOS',
            'USUARIO EXTERNO'

        ];

        foreach ($units as $name) {

            BusinessUnit::updateOrCreate(
                ['name' => $name],
                ['name' => $name]
            );
        }
    }

    private function insertLocations()
    {
        $locations = [

            'Torre Panamá San Isidro',
            'Oncocenter Cuadra 5',
            'Oncocenter Cuadra 3',
            'Oncosalud Cuadra 2',
            'Casa 6',
            'Casa 5',
            'Clínica Delgado',
            'Casa Independencia',
            'Casa Tarapaca',
            'Casa Angamos',
            'Clínica Bellavista',
            'Casa Bellavista',
            'Benavides',
            'CBA Independencia',
            'Radioncologia',
            'Cantella',
            'Clinica Javier Prado',
            'Parroquia Magdalena',
            'Trecca',
            'Oncogenomic',
            'POSAC',
            'FEBAN- Cercado de Lima',
            'FEBAN-Surquillo',
            'FEBAN-Salamanca',
            'Clinica San Juan de Dios',
            'Clinica Miraflores -Piura',
            'Clinica Camino real - Trujillo',
            'Clinica Chiclayo - Chiclayo',
            'Servimedicos - Chiclayo',
            'Clinica Vallesur - Arequipa'

        ];

        foreach ($locations as $name) {

            Location::updateOrCreate(
                ['name' => $name],
                ['name' => $name]
            );
        }
    }

    private function insertDiskCapacities()
    {
        $disks = [

            ['capacity' => 512, 'disk_type' => 'SSD'],
            ['capacity' => 500, 'disk_type' => 'HDD'],
            ['capacity' => 500, 'disk_type' => 'SSD'],
            ['capacity' => 1024, 'disk_type' => 'HDD'],
            ['capacity' => 240, 'disk_type' => 'SSD'],
            ['capacity' => 1024, 'disk_type' => 'SSD']

        ];

        foreach ($disks as $disk) {

            DiskCapacity::updateOrCreate(
                [
                    'capacity' => $disk['capacity'],
                    'disk_type' => $disk['disk_type']
                ],
                $disk
            );
        }
    }

    private function insertRamCapacities()
    {
        $rams = [4, 8, 12, 16, 32, 64];

        foreach ($rams as $ram) {

            RamCapacity::updateOrCreate(
                ['capacity_gb' => $ram],
                ['capacity_gb' => $ram]
            );
        }
    }

    private function insertUser($profileId)
    {
        User::updateOrCreate(

            ['coduser' => 1],

            [
                'codprofile' => $profileId,
                'username' => 'luchinbot',
                'password' => Hash::make('12345678')
            ]

        );
    }
}

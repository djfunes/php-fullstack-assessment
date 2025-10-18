<?php
require __DIR__ . '/../vendor/autoload.php';

use App\core\Database;

$col = Database::collection('misCV');
$col->drop(); // limpiar si existe
$col->createIndex(['departamento'=>1]);
$col->createIndex(['facademica.nivel'=>1, 'facademica.especialidad'=>1]);
$col->createIndex(['exlaboral.salario'=>1]);

$now = new \MongoDB\BSON\UTCDateTime();

$cvs = [
  [
    'nombre_completo' => 'Juan Pérez',
    'departamento' => 'San Salvador',
    'telefono' => '7777-8888',
    'correo' => 'juanperez@gmail.com',
    'licencia' => 'SI',
    'vehiculo' => 'NO',
    'facademica' => [
      ['nivel' => 'Universitario', 'especialidad' => 'Administración de Empresas']
    ],
    'exlaboral' => [
      ['puesto' => 'Asistente Contable', 'empresa' => 'Finanzas XYZ', 'salario' => '750.00'],
      ['puesto' => 'Auxiliar Administrativo', 'empresa' => 'Distribuidora Alfa', 'salario' => '500.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'María López',
    'departamento' => 'Santa Ana',
    'telefono' => '7788-9911',
    'correo' => 'marialopez@gmail.com',
    'licencia' => 'NO',
    'vehiculo' => 'NO',
    'facademica' => [
      ['nivel' => 'Técnico', 'especialidad' => 'Informática']
    ],
    'exlaboral' => [
      ['puesto' => 'Soporte Técnico', 'empresa' => 'NetSolutions', 'salario' => '600.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'Carlos Martínez',
    'departamento' => 'La Libertad',
    'telefono' => '7000-1122',
    'correo' => 'carlosmartinez@gmail.com',
    'licencia' => 'SI',
    'vehiculo' => 'SI',
    'facademica' => [
      ['nivel' => 'Universitario', 'especialidad' => 'Ingeniería Industrial']
    ],
    'exlaboral' => [
      ['puesto' => 'Supervisor de Producción', 'empresa' => 'Industrias Nova', 'salario' => '1200.00'],
      ['puesto' => 'Jefe de Planta', 'empresa' => 'Empaques S.A.', 'salario' => '1500.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'Ana Gómez',
    'departamento' => 'San Miguel',
    'telefono' => '7654-3322',
    'correo' => 'anagomez@gmail.com',
    'licencia' => 'NO',
    'vehiculo' => 'NO',
    'facademica' => [
      ['nivel' => 'Universitario', 'especialidad' => 'Psicología']
    ],
    'exlaboral' => [
      ['puesto' => 'Asistente de RRHH', 'empresa' => 'Talentum', 'salario' => '850.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'José Rivera',
    'departamento' => 'Chalatenango',
    'telefono' => '7890-5544',
    'correo' => 'joserivera@gmail.com',
    'licencia' => 'SI',
    'vehiculo' => 'SI',
    'facademica' => [
      ['nivel' => 'Técnico', 'especialidad' => 'Mecánica Automotriz']
    ],
    'exlaboral' => [
      ['puesto' => 'Mecánico', 'empresa' => 'AutoCenter', 'salario' => '700.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'Laura Hernández',
    'departamento' => 'Sonsonate',
    'telefono' => '7111-7788',
    'correo' => 'laurahernandez@gmail.com',
    'licencia' => 'NO',
    'vehiculo' => 'NO',
    'facademica' => [
      ['nivel' => 'Universitario', 'especialidad' => 'Contaduría Pública']
    ],
    'exlaboral' => [
      ['puesto' => 'Auditora Junior', 'empresa' => 'Contalux', 'salario' => '950.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'Ricardo Díaz',
    'departamento' => 'San Salvador',
    'telefono' => '7222-9933',
    'correo' => 'ricardodiaz@gmail.com',
    'licencia' => 'SI',
    'vehiculo' => 'NO',
    'facademica' => [
      ['nivel' => 'Universitario', 'especialidad' => 'Ingeniería en Sistemas']
    ],
    'exlaboral' => [
      ['puesto' => 'Desarrollador Web', 'empresa' => 'SoftTech', 'salario' => '1000.00'],
      ['puesto' => 'QA Tester', 'empresa' => 'Apps4U', 'salario' => '850.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'Marta Castillo',
    'departamento' => 'Cuscatlán',
    'telefono' => '7333-8822',
    'correo' => 'martacastillo@gmail.com',
    'licencia' => 'NO',
    'vehiculo' => 'NO',
    'facademica' => [
      ['nivel' => 'Bachillerato', 'especialidad' => 'Administrativo Contable']
    ],
    'exlaboral' => [
      ['puesto' => 'Recepcionista', 'empresa' => 'OfiPlus', 'salario' => '450.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'Pedro Ramírez',
    'departamento' => 'Usulután',
    'telefono' => '7444-2211',
    'correo' => 'pedroramirez@gmail.com',
    'licencia' => 'SI',
    'vehiculo' => 'SI',
    'facademica' => [
      ['nivel' => 'Técnico', 'especialidad' => 'Electricidad Industrial']
    ],
    'exlaboral' => [
      ['puesto' => 'Técnico Electricista', 'empresa' => 'ElectroSal', 'salario' => '850.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
  [
    'nombre_completo' => 'Sofía Torres',
    'departamento' => 'La Paz',
    'telefono' => '7555-7788',
    'correo' => 'sofiatorres@gmail.com',
    'licencia' => 'NO',
    'vehiculo' => 'NO',
    'facademica' => [
      ['nivel' => 'Universitario', 'especialidad' => 'Mercadeo y Publicidad']
    ],
    'exlaboral' => [
      ['puesto' => 'Asistente de Marketing', 'empresa' => 'Creativa SA', 'salario' => '650.00']
    ],
    'created_at' => $now, 'updated_at' => $now
  ],
];

$col->insertMany($cvs);
echo "Insertados " . count($cvs) . " CVs en misCV\n";

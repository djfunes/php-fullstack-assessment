<?php
namespace App\controllers;

use App\core\Auth;
use App\core\Database;
use App\core\Request;
use App\core\Response;
use App\core\View;
use App\core\HttpException;

class CandidateController {
    private \MongoDB\Collection $col;

    public function __construct() {
        $this->col = Database::collection('misCV');
        // Indices para la busqueda
        $this->col->createIndex(['departamento'=>1]);
        $this->col->createIndex(['licencia'=>1, 'vehiculo'=>1]);
        $this->col->createIndex(['facademica.nivel'=>1, 'facademica.especialidad'=>1]);
        $this->col->createIndex(['exlaboral.salario'=>1]);
    }

    public function index(Request $req) {
        Auth::requireLogin();
        return View::render('candidates/search', [
            'authUser' => $_SERVER['AUTH_USER'] ?? 'user'
        ]);
    }

    // Version web
    public function search(Request $req) {
        Auth::requireLogin();
        $incoming = $req->json ?? $_POST ?? [];

        // Si viene clear=1 => 0 resultados y filtros vacíos
        if (($incoming['clear'] ?? '') === '1') {
            return View::render('candidates/search', [
                'results' => [],
                'count'   => 0,
                'filters' => [
                    'nivel' => '', 'especialidad' => '', 'departamento' => '',
                    'licencia' => '', 'vehiculo' => '', 'salarioMax' => ''
                ],
                'authUser'=> Auth::user()['username'] ?? 'user'
            ]);
        }

        [$pipeline, $uiFilters] = $this->buildPipeline($incoming);
        $cursor = $this->col->aggregate($pipeline, [
            'collation' => ['locale' => 'es', 'strength' => 1]
        ]);
        $results = iterator_to_array($cursor, false);

        return View::render('candidates/search', [
            'results' => $results,
            'count'   => count($results),
            'filters' => $uiFilters,
            'authUser'=> Auth::user()['username'] ?? 'user'
        ]);
    }


    // Version API
    public function searchApi(Request $req) {
        Auth::requireLogin();
        $incoming = $req->json ?: $req->query;

        if (($incoming['clear'] ?? '') === '1') {
            return Response::success([
                'count'   => 0,
                'filters' => [
                    'nivel' => '', 'especialidad' => '', 'departamento' => '',
                    'licencia' => '', 'vehiculo' => '', 'salarioMax' => ''
                ],
                'items'   => [],
            ]);
        }

        [$pipeline, $uiFilters] = $this->buildPipeline($incoming);
        $cursor = $this->col->aggregate($pipeline, [
            'collation' => ['locale' => 'es', 'strength' => 1]
        ]);
        $results = iterator_to_array($cursor, false);

        return Response::success([
            'count'   => count($results),
            'filters' => $uiFilters,
            'items'   => $results,
        ]);
    }


    public function show(Request $req, array $params) {
        Auth::requireLogin();

        $id = $params['id'] ?? '';
        if (!preg_match('/^[a-f0-9]{24}$/i', $id)) {
            throw new HttpException(400, 'ID inválido');
        }

        // Pipeline de detalle (permite extender fácilmente)
        $pipeline = [
            ['$match' => ['_id' => new \MongoDB\BSON\ObjectId($id)]],
            // puedes agregar $project aquí si deseas ocultar campos
            // ['$project' => [ ... ]]
            ['$limit' => 1],
        ];

        $cursor = $this->col->aggregate($pipeline, [
            'collation' => ['locale' => 'es', 'strength' => 1]
        ]);

        $docs = iterator_to_array($cursor, false);
        if (count($docs) === 0) {
            throw new HttpException(404, 'CV no encontrado');
        }

        $cv = $docs[0];

        // Normaliza arrays por si vienen null
        $cv['facademica'] = $this->toArray($cv['facademica'] ?? []);
        $cv['exlaboral']  = $this->toArray($cv['exlaboral'] ?? []);

        return View::render('candidates/show', [
            'cv'       => $cv,
            'authUser' => Auth::user()['username'] ?? 'user',
        ]);
    }


    private function buildPipeline(array $in): array {
        // normalizamos filtros
        $nivel         = trim((string)($in['nivel'] ?? ''));
        $especialidad  = trim((string)($in['especialidad'] ?? ''));
        $departamento  = trim((string)($in['departamento'] ?? ''));
        $licencia      = strtoupper(trim((string)($in['licencia'] ?? '')));   // "SI"/"NO"
        $vehiculo      = strtoupper(trim((string)($in['vehiculo'] ?? '')));   // "SI"/"NO"
        $salarioMaxStr = trim((string)($in['salarioMax'] ?? ''));

        $match = [];

        if ($nivel !== '') {
            // array de subdocs: $elemMatch
            $match['facademica'] = ['$elemMatch' => ['nivel' => $nivel]];
        }
        if ($especialidad !== '') {
            $match['facademica'] = $match['facademica'] ?? ['$elemMatch'=>[]];
            // regex case-insensitive
            $match['facademica']['$elemMatch']['especialidad'] = ['$regex' => $especialidad, '$options' => 'i'];
        }
        if ($departamento !== '') {
            $match['departamento'] = ['$regex' => $departamento, '$options' => 'i'];
        }
        if ($licencia === 'SI' || $licencia === 'NO') {
            $match['licencia'] = $licencia;
        }
        if ($vehiculo === 'SI' || $vehiculo === 'NO') {
            $match['vehiculo'] = $vehiculo;
        }

        // pipeline
        $pipeline = [];

        if (!empty($match)) {
            $pipeline[] = ['$match' => $match];
        }

        // convertir salario (string) a número para filtrar (en exlaboral[])
        $pipeline[] = [
            '$addFields' => [
                'exlaboralNumeric' => [
                    '$map' => [
                        'input' => ['$ifNull' => ['$exlaboral', []]],
                        'as'    => 'job',
                        'in'    => [
                            'puesto' => ['\$ifNull' => ['$$job.puesto', '']],
                            'empresa'=> ['\$ifNull' => ['$$job.empresa','']],
                            'salarioNum' => [
                                '$cond' => [
                                    ['\$gt' => [['\$type' => '$$job.salario'], 'missing']],
                                    ['$toDouble' => ['$replaceAll' => ['input'=>'$$job.salario','find'=>',','replacement'=>'']]],
                                    null
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if ($salarioMaxStr !== '' && is_numeric($salarioMaxStr)) {
            $salarioMax = (float)$salarioMaxStr;
            // filtra por al menos un exlaboral con salario < max
            $pipeline[] = [
                '$match' => [
                    'exlaboralNumeric' => [
                        '$elemMatch' => ['salarioNum' => ['$lt' => $salarioMax]]
                    ]
                ]
            ];
        }

        // ordenar recientes
        $pipeline[] = ['$sort' => ['updated_at' => -1, 'created_at' => -1]];
        // limitar (seguridad)
        $pipeline[] = ['$limit' => 200];

        $uiFilters = compact('nivel','especialidad','departamento','licencia','vehiculo');
        $uiFilters['salarioMax'] = $salarioMaxStr;

        return [$pipeline, $uiFilters];
    }

    private function toArray($value): array {
        if ($value instanceof \MongoDB\Model\BSONArray) {
            return $value->getArrayCopy();
        }
        if ($value instanceof \Traversable) {
            return iterator_to_array($value, false);
        }
        return is_array($value) ? $value : [];
    }
}

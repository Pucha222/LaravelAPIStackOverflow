<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Question; // El modelo para almacenar las preguntas en la base de datos
use App\Models\Search; // El modelo para almacenar las búsquedas¡
use Carbon\Carbon;

class StackOverflowController extends Controller
{
    /**
     *
     * @name: getQuestions
     * @author: Pol Pujadó
     *
     * @description: Función que llamaremos via API que recoge la búsqueda pasada
     *              por parámetros en la Request, la identifica y si existe realiza una query
     *              en la base de datos y si no existe genera una petición API
     *              contra Stackoverflow para recoger los datos solicitados.
     * @version: 2
     * @comments:
     *      -En la versión 1 implemento la petición API contra Stackoverflowç
     *      -En la versión 2 implemento el almacenamiento de búsquedas para optimizar las siguientes peticiones.
     *
     * @param: Request $request
     * @url: /api/questions?tagged=php&fromdate=2021-01-01&todate=2024-12-30 (example)
     * @date: 2024-11-08
     *
     */
    public function getQuestions(Request $request)
    {
         // Validación de los filtros de entrada con control de errores
        $validator = \Validator::make($request->all(), [
            'tagged' => 'required|string',
            'todate' => 'nullable|date',
            'fromdate' => 'nullable|date',
        ]);

        // Si la validación falla, devolver un error en formato JSON
        if ($validator->fails()) {
            // return response()->json(['errors' => $validator->errors()], 400); // 400 Bad Request
            return response()->json([
                'message' => $validator->errors(),
                'questions' => []
            ],400);
        }

        //vinculamos parametros a variables para la busqueda aplicando un "no-data" para facilitar las búsquedas futuras en nuestra bdd
        $param_todate = $request->todate ? strtotime($request->todate) : 'no-data';
        $param_fromdate = $request->fromdate ? strtotime($request->fromdate) : 'no-data';

        // Construcción de la URL con los parámetros
        $queryParams = [
            'site' => 'stackoverflow',
            'tagged' => $request->tagged,
            'todate' => $request->todate ? strtotime($request->todate) : null,
            'fromdate' => $request->fromdate ? strtotime($request->fromdate) : null,
        ];

        // Verifica si la búsqueda ya existe en la base de datos
        $search = Search::where('busqueda_tagged', $request->tagged)
                ->where('busqueda_todate',$param_todate)
                ->where('busqueda_fromdate',$param_fromdate)
                ->first();

        if ($search) {
            // Si la búsqueda ya existe, incrementa el contador
            $search->increment('contador');

            // Realiza la consulta a la base de datos con los filtros
            $query = Question::where('tags', 'like', '%' . $request->tagged . '%');

            // Aplicamos el filtro de 'fromdate' si está presente
            if ($param_fromdate !== 'no-data') {
                $query->where('creation_date', '>=', date('Y-m-d', $param_fromdate));
            }

            // Aplicamos el filtro de 'todate' si está presente
            if ($param_todate !== 'no-data') {
                $query->where('creation_date', '<=', date('Y-m-d', $param_todate));
            }

            // Ejecuta la consulta
            $questions = $query->get();

            // Retornamos la consulta local, no de la API
            return response()->json([
                'message' => 'Búsqueda ya realizada, contador incrementado. CONTADOR DE BÚSQUEDAS: ' . $search->contador,
                'questions' => $questions,
            ]);

        } else {
            // Si no existe, guarda la nueva búsqueda
            Search::create([
                'busqueda_tagged' => $request->tagged,
                'busqueda_todate'=> $param_todate,
                'busqueda_fromdate'=> $param_fromdate,
                'contador' => 1,
            ]);

            // Realiza la solicitud a la API de Stack Exchange
            $response = Http::get('https://api.stackexchange.com/2.3/questions', array_filter($queryParams));

            // Si la solicitud es exitosa, guarda las preguntas en la base de datos
            if ($response->successful()) {
                $questions = $response->json()['items'];
                foreach ($questions as $question) {
                    Question::updateOrCreate(
                        ['question_id' => $question['question_id']],
                        [
                            'title' => $question['title'],
                            'link' => $question['link'],
                            'tags' => implode(',', $question['tags']),
                            'creation_date' => date('Y-m-d H:i:s', $question['creation_date']),
                        ]
                    );
                }

                return response()->json([
                    'message' => 'Preguntas obtenidas con éxito desde la API.',
                    'questions' => $questions,
                ]);
            }
        }

        return response()->json([
            'message' => 'Failed to fetch data from Stack Overflow',
            'questions' => []
        ],500);
    }
}

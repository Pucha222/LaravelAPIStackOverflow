<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Question; // El modelo para almacenar las preguntas en la base de datos
use App\Models\Search; // El modelo para almacenar las búsquedas

class StackOverflowController extends Controller
{
    public function getQuestions(Request $request)
    {
        // Validación de los filtros de entrada
        $request->validate([
            'tagged' => 'required|string',
            'todate' => 'nullable|date',
            'fromdate' => 'nullable|date',
        ]);

        //vinculamos parametros a variables para la busqueda
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
        $search = Search::where('busqueda', $request->tagged."|".$param_todate."|".$param_fromdate)->first();

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
                'busqueda' => $request->tagged."|".$param_todate."|".$param_fromdate,
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

        return response()->json(['error' => 'Failed to fetch data from Stack Overflow'], 500);
    }
}

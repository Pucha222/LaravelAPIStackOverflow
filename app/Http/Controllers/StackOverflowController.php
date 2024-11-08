<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Question; // El modelo para almacenar las preguntas en la base de datos

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

        // Construcción de la URL con los parámetros
        $queryParams = [
            'site' => 'stackoverflow',
            'tagged' => $request->tagged,
            'todate' => $request->todate ? strtotime($request->todate) : null,
            'fromdate' => $request->fromdate ? strtotime($request->fromdate) : null,
        ];

        // Realiza la solicitud a la API de Stack Exchange
        $response = Http::get('https://api.stackexchange.com/2.3/questions', array_filter($queryParams));

        // Si la solicitud es exitosa, guarda las preguntas en la base de datos
        if ( $response->successful() ) {
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
            return response()->json($questions);
        }

        return response()->json(['error' => 'Failed to fetch data from Stack Overflow'], 500);
    }
}


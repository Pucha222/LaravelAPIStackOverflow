<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Http;

class QuestionController extends Controller
{
    // Listar preguntas con paginación
    public function index()
    {
        // Ordenamos descendente para ver las últimas primero
        $questions = Question::orderBy('creation_date', 'desc')->paginate(10);

        return view('questions.index', compact('questions'));
    }

    // Mostrar pregunta específica
    public function show($id)
    {
        $question = Question::findOrFail($id);
        return view('questions.show', compact('question'));
    }

    // Botón para actualizar datos desde la API de Stack Overflow
    public function updateQuestionsAPI()
    {
        $tag = 'php'; // Puedes modificar o hacer que este valor venga del frontend

        $response = Http::get('https://api.stackexchange.com/2.3/questions', [
            'site' => 'stackoverflow',
            'tagged' => $tag,
        ]);

        if ($response->successful()) {
            $questions = $response->json()['items'];

            foreach ($questions as $data) {
                Question::updateOrCreate(
                    ['question_id' => $data['question_id']],
                    [
                        'title' => $data['title'],
                        'link' => $data['link'],
                        'tags' => implode(',', $data['tags']),
                        'creation_date' => date('Y-m-d H:i:s', $data['creation_date']),
                    ]
                );
            }
            return redirect()->route('questions.index')->with('success', 'Preguntas actualizadas correctamente.');
        }

        return redirect()->route('questions.index')->with('error', 'No se pudo actualizar desde la API.');
    }

    // Actualizar pregunta específica
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->all());
        return redirect()->route('questions.index')->with('success', 'Pregunta actualizada.');
    }

    // Eliminar pregunta específica
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Pregunta eliminada.');
    }
}

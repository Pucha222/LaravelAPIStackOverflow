<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuestionController;

Route::get('/', [QuestionController::class, 'index'])->name('questions.index'); // Listar preguntas con paginación
Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index'); // Listar preguntas con paginación
Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('questions.show'); // Mostrar pregunta específica
Route::get('/questions/api/update', [QuestionController::class, 'updateQuestionsAPI'])->name('questions.api.update'); // Botón para actualizar preguntas desde la API
Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.update.put'); // Actualizar pregunta específica
Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy'); // Eliminar pregunta



// Route::get('/', function () {
//     return view('welcome');
// });

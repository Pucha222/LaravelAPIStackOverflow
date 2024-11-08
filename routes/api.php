<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StackOverflowController;


/**
 * Ruta del endpoint API para las preguntas de Stackoverflow
 *
 * @author: Pol Pujadó
 */
Route::get('/questions', [StackOverflowController::class, 'getQuestions']);


<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Public
Route::get('/', [App\Http\Controllers\PublicController::class, 'index'])->name('home.page');
Route::POST('/signup', [App\Http\Controllers\UsersController::class, 'store'])->name('signup');

Auth::routes();

//GET
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/sekolah', [App\Http\Controllers\AdminController::class, 'sekolah'])->name('admin.sekolah');
Route::get('/admin/survey', [App\Http\Controllers\AdminController::class, 'survey'])->name('admin.survey');
Route::get('/admin/pertanyaan', [App\Http\Controllers\AdminController::class, 'pertanyaan'])->name('admin.pertanyaan');
Route::get('/admin/section', [App\Http\Controllers\AdminController::class, 'section'])->name('admin.section');
Route::get('/admin/jawaban', [App\Http\Controllers\JawabanController::class, 'index'])->name('admin.jawaban');
Route::get('/admin/pengguna', [App\Http\Controllers\AdminController::class, 'pengguna'])->name('admin.pengguna');

//OPERATOR GET
Route::get('/operator/dashboard', [App\Http\Controllers\OperatorController::class, 'index'])->name('operator.dashboard');
Route::get('/operator/sekolah', [App\Http\Controllers\OperatorController::class, 'sekolah'])->name('operator.sekolah');
Route::get('/operator/survey', [App\Http\Controllers\OperatorController::class, 'survey'])->name('operator.survey');
Route::get('/operator/pertanyaan', [App\Http\Controllers\OperatorController::class, 'pertanyaan'])->name('operator.pertanyaan');
Route::get('/operator/jawaban', [App\Http\Controllers\OperatorController::class, 'jawaban'])->name('operator.jawaban');

//POST
Route::POST('/admin/survey/save', [App\Http\Controllers\SurveyController::class, 'store']);
Route::POST('/admin/sekolah/save', [App\Http\Controllers\SekolahController::class, 'store']);
Route::POST('/admin/section/save', [App\Http\Controllers\SectionController::class, 'store']);
Route::POST('/admin/pertanyaan/save', [App\Http\Controllers\PertanyaanController::class, 'store']);
Route::POST('/admin/jawaban/save', [App\Http\Controllers\JawabanController::class, 'store']);
Route::POST('/admin/pengguna/save', [App\Http\Controllers\PenggunaController::class, 'store']);

//UPDATE POST
Route::POST('/admin/survey/update/{id}', [App\Http\Controllers\SurveyController::class, 'update']);
Route::POST('/admin/sekolah/update/{id}', [App\Http\Controllers\SekolahController::class, 'update']);
Route::POST('/admin/section/update/{id}', [App\Http\Controllers\SectionController::class, 'update']);
Route::POST('/admin/pertanyaan/update/{id}', [App\Http\Controllers\PertanyaanController::class, 'update']);
Route::POST('/admin/jawaban/update/{id}', [App\Http\Controllers\JawabanController::class, 'update']);
Route::POST('/admin/pengguna/update/{id}', [App\Http\Controllers\PenggunaController::class, 'update']);
Route::POST('/operator/sekolah/update/{id}', [App\Http\Controllers\OperatorController::class, 'update']);

//DESTROY
Route::GET('/admin/survey/delete/{id}', [App\Http\Controllers\SurveyController::class, 'destroy']);
Route::GET('/admin/sekolah/delete/{id}', [App\Http\Controllers\SekolahController::class, 'destroy']);
Route::GET('/admin/section/delete/{id}', [App\Http\Controllers\SectionController::class, 'destroy']);
Route::GET('/admin/pertanyaan/delete/{id}', [App\Http\Controllers\PertanyaanController::class, 'destroy']);
Route::GET('/admin/jawaban/delete/{id}', [App\Http\Controllers\JawabanController::class, 'destroy']);
Route::GET('/admin/pengguna/delete/{id}', [App\Http\Controllers\PenggunaController::class, 'destroy']);

//JSON
Route::get('/admin/survey/json', [App\Http\Controllers\SurveyController::class, 'json']);
Route::get('/admin/sekolah/json', [App\Http\Controllers\SekolahController::class, 'json']);
Route::get('/admin/section/json', [App\Http\Controllers\SectionController::class, 'json']);
Route::get('/admin/pertanyaan/json', [App\Http\Controllers\PertanyaanController::class, 'json']);
Route::get('/admin/jawaban/json', [App\Http\Controllers\JawabanController::class, 'json']);
Route::get('/admin/pengguna/json', [App\Http\Controllers\PenggunaController::class, 'json']);
Route::get('/admin/jawaban/detail/', [App\Http\Controllers\JawabanController::class, 'detail_jawaban']);
Route::get('/admin/jawaban/gap/{id}', [App\Http\Controllers\JawabanController::class, 'tabel_gap']);
Route::get('/admin/jawaban/reliabel/', [App\Http\Controllers\JawabanController::class, 'reliabel']);

//JSON OPERATOR
Route::get('/operator/survey/json', [App\Http\Controllers\SurveyController::class, 'json']);
Route::get('/operator/sekolah/json', [App\Http\Controllers\SekolahController::class, 'json']);
Route::get('/operator/pertanyaan/json', [App\Http\Controllers\PertanyaanController::class, 'json']);
Route::get('/operator/jawaban/json', [App\Http\Controllers\JawabanController::class, 'json']);

//FIND
Route::get('/admin/survey/find/{id}', [App\Http\Controllers\SurveyController::class, 'find']);
Route::get('/admin/sekolah/find/{id}', [App\Http\Controllers\SekolahController::class, 'find']);
Route::get('/admin/section/find/{id}', [App\Http\Controllers\SectionController::class, 'find']);
Route::get('/admin/pertanyaan/find/{id}', [App\Http\Controllers\PertanyaanController::class, 'find']);
Route::get('/admin/jawaban/find/{id}', [App\Http\Controllers\JawabanController::class, 'find']);
Route::get('/admin/pengguna/find/{id}', [App\Http\Controllers\PenggunaController::class, 'find']);

//SURVEY
Route::get('/robot/survey/mulai/{id}/{opsi}', [App\Http\Controllers\OperatorController::class, 'mulai_survey']);
Route::POST('/robot/survey/update/{id}', [App\Http\Controllers\OperatorController::class, 'live_update']);
Route::get('/robot/sekolah/json', [App\Http\Controllers\PublicController::class, 'json']);
Route::get('/robot/survey/hasil/{opsi}', [App\Http\Controllers\OperatorController::class, 'json']);

Route::get('/kenyataan/input', [App\Http\Controllers\PublicController::class, 'input_kenyataan']);
Route::get('/harapan/input', [App\Http\Controllers\PublicController::class, 'input_harapan']);

Route::get('/admin/jawaban/test/gap', [App\Http\Controllers\AdminController::class, 'tabel_gap']);

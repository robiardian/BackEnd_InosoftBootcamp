use App\Http\Controllers\SiswaController;

Route::get('/list-kelas', [SiswaController::class, 'listKelas']);
Route::get('/detail-kelas/{id}', [SiswaController::class, 'detailKelas']);
Route::post('/tambah-kelas', [SiswaController::class, 'tambahKelas']);
Route::put('/perbarui-kelas/{id}', [SiswaController::class, 'perbaruiKelas']);
// ... 

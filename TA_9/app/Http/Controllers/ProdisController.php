<?php

namespace App\Http\Controllers;

use App\Models\Prodi;

class ProdisController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        $prodi = Prodi::all();
        if (!$prodi) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'berhasil mengambil data',
            'data' => $prodi
        ]);
    }
}

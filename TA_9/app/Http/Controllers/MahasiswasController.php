<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\Key;

class MahasiswasController extends Controller
{
    /**
     * Create a new controller instance.
     * @param int $id
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        $mahasiswa = Mahasiswa::all();
        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'gagal mengambil data'
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'berhasil mengambil data',
            'data' => $mahasiswa
        ]);
    }

    public function deleteByNim(Request $request)
    {
        $headerToken = $request->header('token');
        $jwtDecoded = JWT::decode($headerToken, new Key(env('JWT_SECRET'), 'HS256'));
        $nimUser = $jwtDecoded->sub;

        $mahasiswa = Mahasiswa::find($request->nim)->first();

        if ($nimUser != $request->nim) {
            return response()->json([
                'status' => false,
                'message' => 'Delete tidak sesuai'
            ]);
        }

        $mahasiswa->delete();
        return response()->json([
            'status' => true,
            'message' => 'data berhasil dihapus',
            'jwt' => $jwtDecoded
        ]);
    }

    public function getWithToken(Request $request)
    {
        $mahasiswa = Mahasiswa::find($request->user->NIM);
        return response()->json([
            'status' => true,
            'message' => 'berhasil mengambil data',
            'data' => $mahasiswa
        ]);
    }
}

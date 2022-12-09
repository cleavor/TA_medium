<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function register(Request $request)
    {
        $nim = (integer) $request->nim;
        $nama = $request->nama;
        $password = Hash::make($request->password);
        $angkatan = (integer) $request->angkatan;
        $prodiId = $request->prodiId;

        if (get_debug_type($nim) != 'int') {
            return response()->json([
                'status' => false,
                'message' => $nim . ' must be a integer',
                'type' => get_debug_type($nim)
            ], 400);
        }

        if (get_debug_type($nama) != 'string') {
            return response()->json([
                'status' => false,
                'message' => $nama . ' must be a string',
                'type' => get_debug_type($nama)
            ], 400);
        }

        if (get_debug_type($angkatan) != 'int') {
            return response()->json([
                'status' => false,
                'message' => $angkatan . ' must be a integer',
                'type' => get_debug_type($angkatan)
            ], 400);
        }

        $mahasiswa = Mahasiswa::create([
            'NIM' => $nim,
            'Nama' => $nama,
            'Password' => $password,
            'Angkatan' => $angkatan,
            'prodiId' => $prodiId
        ]);

        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong in the server'
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'data sudah dibuat',
            'data' => $mahasiswa
        ], 201);
    }
    public function login(Request $request)
    {
        $nim = $request->nim;
        $pw = $request->password;

        $mahasiswa = Mahasiswa::where('NIM', $nim)->first();

        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        if (!Hash::check($pw, $mahasiswa->Password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password tidak sesuai'
            ]);
        }

        $mahasiswa->token = $this->jwt($mahasiswa);
        $mahasiswa->save();

        return response()->json([
            'status' => true,
            'message' => 'berhasil login',
            'data' => $mahasiswa
        ], 200);
    }

    protected function jwt(Mahasiswa $mahasiswa)
    {
        $payload = [
            'iss' => 'lumen-jwt',
            'sub' => $mahasiswa->NIM,
            'iat' => time(),
            'exp' => time() + 60 * 60
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LeafController extends Controller
{
    /**
     * Menampilkan halaman analisis
     */
    public function index()
    {
        return Inertia::render('Analysis');
    }

    /**
     * Proses pengecekan gambar ke AI Service (Python)
     */
    public function checkLeaf(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        try {
            $image = $request->file('image');

            // 2. Kirim gambar ke FastAPI Python
            // Pastikan Python Service (main.py) sudah running di port 5000
            $response = Http::attach(
                'file',
                file_get_contents($image),
                $image->getClientOriginalName()
            )->post('http://127.0.0.1:5000/predict');

            // 3. Cek apakah request ke Python berhasil
            if ($response->successful()) {
                $data = $response->json();
                Log::info('AI Prediction Result:', $data);

                return response()->json($data);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'AI Service tidak merespons dengan benar.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Leaf Detection Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}

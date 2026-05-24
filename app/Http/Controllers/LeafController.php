<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        try {

            // ============================================
            // VALIDATION
            // ============================================
            $request->validate([
                'image' => 'required|image|max:5120',
                'model' => 'required|in:resnet,mobilenet,efficientnet',
            ]);

            $model = $request->input('model');

            $image = $request->file('image');

            // ============================================
            // SEND TO FASTAPI
            // ============================================
            $response = Http::timeout(120)
                ->attach(
                    'file',
                    file_get_contents($image->getRealPath()),
                    $image->getClientOriginalName()
                )
                ->post(
                    "http://127.0.0.1:5000/predict/{$model}"
                );

            // ============================================
            // CHECK FAILED RESPONSE
            // ============================================
            if (!$response->successful()) {

                Log::error('FastAPI Error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'AI server failed',
                    'detail' => $response->json()
                ], 500);
            }

            // ============================================
            // SUCCESS
            // ============================================
            return response()->json(
                $response->json()
            );

        } catch (\Exception $e) {

            Log::error('Leaf Detection Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

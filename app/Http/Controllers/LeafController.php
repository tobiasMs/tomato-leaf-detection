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
        $request->validate([
            'image' => 'required|image|max:5120',
            'model' => 'required|in:resnet,mobilenet' // Tambahkan validasi tipe model
        ]);

        $model = $request->input('model');
        $image = $request->file('image');

        // URL dinamis: localhost:5000/predict/resnet atau /predict/mobilenet
        $response = Http::attach(
            'file',
            file_get_contents($image),
            $image->getClientOriginalName()
        )->post("http://127.0.0.1:5000/predict/{$model}");

        return response()->json($response->json());
    }
}

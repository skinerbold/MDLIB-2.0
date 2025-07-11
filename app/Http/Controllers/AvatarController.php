<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    /**
     * Exibe a página do avatar
     */
    public function show()
    {
        return view('avatar');
    }

    /**
     * API para controlar animações do avatar
     */
    public function animate(Request $request)
    {
        $request->validate([
            'animation' => 'required|string|in:idle,talk,wave,gesture'
        ]);

        return response()->json([
            'success' => true,
            'animation' => $request->animation,
            'message' => 'Animação iniciada'
        ]);
    }

    /**
     * API para parar animações
     */
    public function stop()
    {
        return response()->json([
            'success' => true,
            'message' => 'Animação parada'
        ]);
    }
}

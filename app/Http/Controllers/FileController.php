<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|max:10240|mimes:pdf,doc,docx,txt',
        ]);

        $file = $request->file('arquivo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename, 'public');

        $fileDocument = new \App\Models\FileDocument();
        $fileDocument->nome_arquivo = $file->getClientOriginalName();
        $fileDocument->tipo = $file->getClientOriginalExtension();
        $fileDocument->user_id = auth()->id();
        $fileDocument->caminho_arquivo = $path;
        $fileDocument->tipo_mime = $file->getClientMimeType();
        $fileDocument->tamanho_arquivo = $file->getSize();
        $fileDocument->save();

        return redirect()->back()->with('success', 'Arquivo enviado com sucesso!');
    }

    public function download($id)
    {
        $file = \App\Models\FileDocument::findOrFail($id);
        
        if ($file->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->download(storage_path('app/public/' . $file->caminho_arquivo));
    }

    public function destroy($id)
    {
        $file = \App\Models\FileDocument::findOrFail($id);
        
        if ($file->user_id !== auth()->id()) {
            abort(403);
        }

        // Deletar arquivo físico
        if (file_exists(storage_path('app/public/' . $file->caminho_arquivo))) {
            unlink(storage_path('app/public/' . $file->caminho_arquivo));
        }

        $file->delete();

        return redirect()->back()->with('success', 'Arquivo excluído com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $file = \App\Models\FileDocument::findOrFail($id);
        
        if ($file->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'glosa' => 'nullable|string',
            'traducao' => 'nullable|string',
        ]);

        $file->update($request->only(['glosa', 'traducao']));

        return redirect()->back()->with('success', 'Arquivo atualizado com sucesso!');
    }
}

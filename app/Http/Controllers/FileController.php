<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FileDocument;
use App\Services\PdfTextExtractorService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    protected $pdfExtractor;
    
    public function __construct(PdfTextExtractorService $pdfExtractor)
    {
        $this->pdfExtractor = $pdfExtractor;
    }

    public function upload(Request $request)
    {
        Log::info('Upload iniciado', ['files' => $request->allFiles()]);
        
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');
            Log::info('Arquivo recebido', ['name' => $file->getClientOriginalName(), 'size' => $file->getSize()]);
            
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $fullPath = storage_path('app/public/' . $path);
            
            Log::info('Arquivo salvo', ['path' => $fullPath]);

            // Tentar extrair texto do PDF
            $extractedText = $this->pdfExtractor->extractText($fullPath);
            
            // Preparar dados de extração
            if ($extractedText !== null && !empty(trim($extractedText))) {
                $textExtraction = [
                    'success' => true,
                    'text' => $extractedText,
                    'word_count' => str_word_count($extractedText),
                    'char_count' => strlen($extractedText)
                ];
            } else {
                $textExtraction = [
                    'success' => false,
                    'error' => 'Não foi possível extrair texto do PDF',
                    'error_type' => 'extraction_failed'
                ];
            }
            
            // Tentar obter informações básicas do PDF
            $pdfInfo = ['success' => false, 'pages' => null];
            
            Log::info('Extração concluída', ['success' => $textExtraction['success']]);

            // Salvar informações na sessão (temporário, enquanto não temos BD)
            $fileData = [
                'id' => uniqid(),
                'nome_arquivo' => $file->getClientOriginalName(),
                'tipo' => $file->getClientOriginalExtension(),
                'caminho_arquivo' => $path,
                'tamanho_arquivo' => $file->getSize(),
                'tipo_mime' => $file->getClientMimeType(),
                'extracted_text' => $textExtraction['success'] ? $textExtraction['text'] : null,
                'extraction_status' => $textExtraction['success'] ? 'success' : 'failed',
                'extraction_error' => $textExtraction['success'] ? null : $textExtraction['error'],
                'pdf_pages' => $pdfInfo['success'] ? $pdfInfo['pages'] : null,
                'created_at' => now()->toDateTimeString(),
            ];

            // Armazenar na sessão
            $files = session()->get('uploaded_files', []);
            $files[] = $fileData;
            session()->put('uploaded_files', $files);

            // Preparar resposta baseada no resultado da extração
            $response = [
                'success' => true,
                'message' => 'Arquivo enviado com sucesso!',
                'file_id' => $fileData['id'],
                'filename' => $filename,
                'extraction' => $textExtraction
            ];

            // Adicionar informações específicas baseadas no tipo de erro
            if (!$textExtraction['success']) {
                $response['extraction_status'] = 'failed';
                
                switch ($textExtraction['error_type']) {
                    case 'protected_or_scanned':
                        $response['extraction_message'] = 'PDF protegido ou digitalizado. Não foi possível extrair texto automaticamente.';
                        break;
                    case 'no_text':
                        $response['extraction_message'] = 'PDF não contém texto extraível. Pode ser apenas imagens.';
                        break;
                    default:
                        $response['extraction_message'] = 'Erro desconhecido na extração de texto.';
                }
            } else {
                $response['extraction_status'] = 'success';
                $response['extraction_message'] = 'Texto extraído com sucesso!';
                $response['word_count'] = $textExtraction['word_count'];
                $response['char_count'] = $textExtraction['char_count'];
            }

            Log::info('Resposta preparada', $response);
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Erro no upload de arquivo: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar arquivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        $files = session()->get('uploaded_files', []);
        return response()->json($files);
    }

    public function extractText($id)
    {
        try {
            $files = session()->get('uploaded_files', []);
            $fileIndex = array_search($id, array_column($files, 'id'));
            
            if ($fileIndex === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo não encontrado'
                ], 404);
            }
            
            $file = $files[$fileIndex];
            $fullPath = storage_path('app/public/' . $file['caminho_arquivo']);
            
            // Tentar extrair texto novamente
            $extractedText = $this->pdfExtractor->extractText($fullPath);
            
            if ($extractedText !== null && !empty(trim($extractedText))) {
                $textExtraction = [
                    'success' => true,
                    'text' => $extractedText,
                    'word_count' => str_word_count($extractedText),
                    'char_count' => strlen($extractedText)
                ];
                
                // Atualizar dados na sessão
                $files[$fileIndex]['extracted_text'] = $extractedText;
                $files[$fileIndex]['extraction_status'] = 'success';
                $files[$fileIndex]['extraction_error'] = null;
                session()->put('uploaded_files', $files);
            } else {
                $textExtraction = [
                    'success' => false,
                    'error' => 'Não foi possível extrair texto do PDF',
                    'error_type' => 'extraction_failed'
                ];
            }
            
            return response()->json($textExtraction);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao extrair texto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        $files = session()->get('uploaded_files', []);
        $fileIndex = array_search($id, array_column($files, 'id'));
        
        if ($fileIndex === false) {
            abort(404, 'Arquivo não encontrado');
        }
        
        $file = $files[$fileIndex];
        return response()->download(storage_path('app/public/' . $file['caminho_arquivo']));
    }

    public function destroy($id)
    {
        try {
            $files = session()->get('uploaded_files', []);
            $fileIndex = array_search($id, array_column($files, 'id'));
            
            if ($fileIndex === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo não encontrado'
                ], 404);
            }
            
            $file = $files[$fileIndex];
            
            // Deletar arquivo físico
            if (file_exists(storage_path('app/public/' . $file['caminho_arquivo']))) {
                unlink(storage_path('app/public/' . $file['caminho_arquivo']));
            }

            // Remover da sessão
            unset($files[$fileIndex]);
            $files = array_values($files); // Reindexar array
            session()->put('uploaded_files', $files);

            return response()->json([
                'success' => true,
                'message' => 'Arquivo excluído com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir arquivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'glosa' => 'nullable|string',
            'traducao' => 'nullable|string',
        ]);

        $files = session()->get('uploaded_files', []);
        $fileIndex = array_search($id, array_column($files, 'id'));
        
        if ($fileIndex === false) {
            return response()->json([
                'success' => false,
                'message' => 'Arquivo não encontrado'
            ], 404);
        }

        // Atualizar dados na sessão
        if ($request->has('glosa')) {
            $files[$fileIndex]['glosa'] = $request->glosa;
        }
        if ($request->has('traducao')) {
            $files[$fileIndex]['traducao'] = $request->traducao;
        }
        
        session()->put('uploaded_files', $files);

        return response()->json([
            'success' => true,
            'message' => 'Arquivo atualizado com sucesso!'
        ]);
    }
}

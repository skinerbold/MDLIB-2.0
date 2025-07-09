<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PdfTextExtractorService;

class TestPdfExtraction extends Command
{
    protected $signature = 'test:pdf-extraction {file?}';
    protected $description = 'Test PDF text extraction functionality';

    protected $pdfExtractor;

    public function __construct(PdfTextExtractorService $pdfExtractor)
    {
        parent::__construct();
        $this->pdfExtractor = $pdfExtractor;
    }

    public function handle()
    {
        $filePath = $this->argument('file') ?? storage_path('app/public/test/test_document.pdf');
        
        if (!file_exists($filePath)) {
            $this->error("❌ Arquivo PDF não encontrado: " . $filePath);
            $this->info("Execute primeiro: php create_test_pdf.php");
            return 1;
        }

        $this->info("🔍 Testando extração de texto do PDF...");
        $this->info("Arquivo: " . basename($filePath));
        $this->info("Tamanho: " . $this->formatBytes(filesize($filePath)));
        $this->newLine();

        $this->info("🚀 Iniciando extração...");
        $start = microtime(true);

        $extractedText = $this->pdfExtractor->extractText($filePath);

        $end = microtime(true);
        $duration = round(($end - $start) * 1000, 2);

        $this->info("⏱️ Tempo de extração: " . $duration . "ms");
        $this->newLine();

        if ($extractedText !== null && !empty(trim($extractedText))) {
            $this->info("✅ SUCESSO! Texto extraído com sucesso!");
            $this->newLine();
            
            $this->info("📊 Estatísticas:");
            $this->info("- Caracteres: " . strlen($extractedText));
            $this->info("- Palavras: " . str_word_count($extractedText));
            $this->info("- Linhas: " . (substr_count($extractedText, "\n") + 1));
            $this->newLine();
            
            $this->info("📄 Primeiros 500 caracteres do texto extraído:");
            $this->info(str_repeat("=", 60));
            $this->line(substr($extractedText, 0, 500) . (strlen($extractedText) > 500 ? "..." : ""));
            $this->info(str_repeat("=", 60));
            $this->newLine();
            
            // Verificar se contém palavras-chave esperadas
            $keywords = ['MDLIB', 'teste', 'extração', 'funcionalidades', 'Upload'];
            $found = [];
            foreach ($keywords as $keyword) {
                if (stripos($extractedText, $keyword) !== false) {
                    $found[] = $keyword;
                }
            }
            
            $this->info("🔍 Palavras-chave encontradas: " . implode(', ', $found));
            $this->info("Taxa de precisão: " . round((count($found) / count($keywords)) * 100, 1) . "%");
            
        } else {
            $this->error("❌ FALHA! Não foi possível extrair texto do PDF.");
            if (is_string($extractedText)) {
                $this->error("Mensagem retornada: " . $extractedText);
            }
        }

        return 0;
    }

    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');   
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}

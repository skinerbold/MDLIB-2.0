<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PdfTextExtractorService
{
    public function extractText($filePath)
    {
        Log::info("Starting advanced PDF extraction for: " . $filePath);
        
        if (!file_exists($filePath)) {
            Log::error("File not found: " . $filePath);
            return null;
        }

        // Strategy 1: Try Python script with pdfplumber/PyMuPDF
        $extractedText = $this->tryPythonExtraction($filePath);
        if ($extractedText !== null) {
            return $extractedText;
        }

        // Strategy 2: Try system pdftotext if available
        $extractedText = $this->tryPdfToText($filePath);
        if ($extractedText !== null) {
            return $extractedText;
        }

        // Strategy 3: Try spatie/pdf-to-text if available
        $extractedText = $this->trySpatiePdfToText($filePath);
        if ($extractedText !== null) {
            return $extractedText;
        }

        // Strategy 4: Advanced PHP-based extraction
        $extractedText = $this->tryAdvancedPhpExtraction($filePath);
        if ($extractedText !== null) {
            return $extractedText;
        }

        // Strategy 5: Fallback to binary analysis
        $extractedText = $this->tryBinaryAnalysis($filePath);
        if ($extractedText !== null) {
            return $extractedText;
        }

        Log::warning("All extraction strategies failed for: " . $filePath);
        return "⚠️ Não foi possível extrair texto deste PDF. O arquivo pode estar protegido, ser uma imagem digitalizada ou usar codificação complexa.\n\nPara melhor suporte a PDFs, considere instalar o Poppler Utils (pdftotext) ou bibliotecas Python como pdfplumber.";
    }

    private function tryPythonExtraction($filePath)
    {
        try {
            Log::info("Trying Python extraction with pdfplumber/PyMuPDF");
            
            $scriptPath = base_path('extract_pdf_text.py');
            Log::info("Python script path: " . $scriptPath);
            
            if (!file_exists($scriptPath)) {
                Log::warning("Python script not found at: " . $scriptPath);
                return null;
            }

            // **CORREÇÃO 1: Definir o locale para UTF-8 antes de executar o comando**
            // Isso garante que o shell e o PHP se comuniquem corretamente em UTF-8.
            $currentLocale = setlocale(LC_CTYPE, 0);
            setlocale(LC_CTYPE, 'en_US.UTF-8', 'C.UTF-8');
            
            // Executar o script Python
            $command = sprintf('python "%s" "%s" --json', $scriptPath, $filePath);
            Log::info("Executing command: " . $command);
            
            // Usar shell_exec para melhor captura de saída. O 2>&1 redireciona erros para a saída padrão.
            $rawOutput = shell_exec($command . ' 2>&1');

            // **CORREÇÃO 2: Restaurar o locale original**
            setlocale(LC_CTYPE, $currentLocale);
            
            if ($rawOutput === null) {
                Log::error("shell_exec failed or returned null. Check if it's disabled in php.ini.");
                return null;
            }
            
            Log::info("Raw output received from Python script.");

            // **CORREÇÃO 3: Remover a lógica de adivinhação de encoding**
            // Agora confiamos que a saída é UTF-8. Apenas validamos se é um JSON válido.
            $jsonOutput = $rawOutput;
            $data = json_decode($jsonOutput, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Failed to decode JSON from Python script.", [
                    'json_error' => json_last_error_msg(),
                    'raw_output' => substr($rawOutput, 0, 500) // Log dos primeiros 500 caracteres
                ]);
                return null;
            }

            if (isset($data['error'])) {
                Log::error("Python script returned an error.", ['error' => $data['error']]);
                return null;
            }

            if (isset($data['text'])) {
                Log::info("Successfully extracted text using Python.");
                return $data['text'];
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Exception in tryPythonExtraction: " . $e->getMessage());
            return null;
        }
    }

    private function tryPdfToText($filePath)
    {
        try {
            Log::info("Trying system pdftotext");
            
            // Try different possible locations for pdftotext
            $pdftotext_paths = [
                'pdftotext',
                'C:\Program Files\poppler\bin\pdftotext.exe',
                'C:\poppler\bin\pdftotext.exe',
                '/usr/bin/pdftotext',
                '/usr/local/bin/pdftotext'
            ];
            
            foreach ($pdftotext_paths as $pdftotext) {
                $command = sprintf('"%s" "%s" -', $pdftotext, $filePath);
                $output = null;
                $return_var = null;
                
                exec($command . ' 2>&1', $output, $return_var);
                
                if ($return_var === 0 && !empty($output)) {
                    $text = implode("\n", $output);
                    if (strlen(trim($text)) > 10) {
                        Log::info("Success with pdftotext, extracted " . strlen($text) . " characters");
                        return $this->cleanText($text);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("pdftotext failed: " . $e->getMessage());
        }
        
        return null;
    }

    private function trySpatiePdfToText($filePath)
    {
        try {
            if (class_exists('\Spatie\PdfToText\Pdf')) {
                Log::info("Trying spatie/pdf-to-text extraction");
                $text = \Spatie\PdfToText\Pdf::getText($filePath);
                if (!empty(trim($text))) {
                    Log::info("Success with spatie/pdf-to-text, extracted " . strlen($text) . " characters");
                    return $this->cleanText($text);
                }
            }
        } catch (\Exception $e) {
            Log::error("spatie/pdf-to-text failed: " . $e->getMessage());
        }
        
        return null;
    }

    private function tryAdvancedPhpExtraction($filePath)
    {
        try {
            Log::info("Trying advanced PHP extraction");
            
            $content = file_get_contents($filePath);
            
            // Check if PDF is linearized or has xref table
            if (!$this->isValidPdf($content)) {
                Log::warning("Invalid or corrupted PDF structure");
                return null;
            }
            
            // Try to extract text from uncompressed streams first
            $text = $this->extractFromUncompressedStreams($content);
            if (!empty($text)) {
                Log::info("Success with uncompressed streams, extracted " . strlen($text) . " characters");
                return $this->cleanText($text);
            }
            
            // Try to decompress FlateDecode streams
            $text = $this->extractFromCompressedStreams($content);
            if (!empty($text)) {
                Log::info("Success with compressed streams, extracted " . strlen($text) . " characters");
                return $this->cleanText($text);
            }
            
            // Try to extract from text objects
            $text = $this->extractFromTextObjects($content);
            if (!empty($text)) {
                Log::info("Success with text objects, extracted " . strlen($text) . " characters");
                return $this->cleanText($text);
            }
            
        } catch (\Exception $e) {
            Log::error("Advanced PHP extraction failed: " . $e->getMessage());
        }
        
        return null;
    }

    private function isValidPdf($content)
    {
        return strpos($content, '%PDF-') === 0 && 
               (strpos($content, '%%EOF') !== false || strpos($content, 'xref') !== false);
    }

    private function extractFromUncompressedStreams($content)
    {
        // Look for uncompressed text streams
        if (preg_match_all('/stream\s*\n(.*?)\nendstream/s', $content, $matches)) {
            $text = '';
            foreach ($matches[1] as $stream) {
                // Skip if this looks like a compressed stream
                if (strpos($stream, 'FlateDecode') === false && 
                    strpos($stream, 'ASCIIHexDecode') === false) {
                    $text .= $this->extractTextFromStream($stream);
                }
            }
            return $text;
        }
        return '';
    }

    private function extractFromCompressedStreams($content)
    {
        $text = '';
        
        // Find FlateDecode streams and try to decompress them
        if (preg_match_all('/\/Filter\s*\/FlateDecode.*?stream\s*\n(.*?)\nendstream/s', $content, $matches)) {
            foreach ($matches[1] as $compressedData) {
                try {
                    $decompressed = @gzuncompress($compressedData);
                    if ($decompressed === false) {
                        // Try with different decompression methods
                        $decompressed = @gzinflate($compressedData);
                    }
                    if ($decompressed === false) {
                        $decompressed = @gzdecode($compressedData);
                    }
                    
                    if ($decompressed !== false) {
                        $text .= $this->extractTextFromStream($decompressed);
                    }
                } catch (\Exception $e) {
                    // Continue with next stream
                    continue;
                }
            }
        }
        
        return $text;
    }

    private function extractFromTextObjects($content)
    {
        $text = '';
        
        // Extract text from BT/ET blocks (text objects)
        if (preg_match_all('/BT\s+(.*?)\s+ET/s', $content, $matches)) {
            foreach ($matches[1] as $textObject) {
                $text .= $this->parseTextObject($textObject);
            }
        }
        
        return $text;
    }

    private function extractTextFromStream($stream)
    {
        $text = '';
        
        // Look for text showing operators
        $patterns = [
            '/\(((?:[^()\\\\]|\\\\.|\\([^)]*\\))*)\)\s*Tj/s',  // (text) Tj
            '/\(((?:[^()\\\\]|\\\\.|\\([^)]*\\))*)\)\s*\'/s',   // (text) '
            '/\(((?:[^()\\\\]|\\\\.|\\([^)]*\\))*)\)\s*"/s',    // (text) "
            '/\[((?:[^\[\]\\\\]|\\\\.)*)\]\s*TJ/s',             // [text] TJ
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $stream, $matches)) {
                foreach ($matches[1] as $match) {
                    $decoded = $this->decodePdfString($match);
                    if (!empty($decoded)) {
                        $text .= $decoded . ' ';
                    }
                }
            }
        }
        
        return $text;
    }

    private function parseTextObject($textObject)
    {
        $text = '';
        
        // Remove positioning and font commands, keep only text
        $lines = explode("\n", $textObject);
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip font, positioning, and other non-text commands
            if (preg_match('/^\/F\d+|^Tf|^Td|^TD|^Tm|^T\*|^\d+/', $line)) {
                continue;
            }
            
            // Extract text from this line
            if (preg_match('/\((.*?)\)\s*Tj/', $line, $matches)) {
                $text .= $this->decodePdfString($matches[1]) . ' ';
            } elseif (preg_match('/\[(.*?)\]\s*TJ/', $line, $matches)) {
                $text .= $this->decodePdfString($matches[1]) . ' ';
            }
        }
        
        return $text;
    }

    private function decodePdfString($string)
    {
        // Handle PDF string encoding
        $string = str_replace(['\\n', '\\r', '\\t', '\\\\', '\\(', '\\)'], ["\n", "\r", "\t", "\\", "(", ")"], $string);
        
        // Remove octal escape sequences
        $string = preg_replace_callback('/\\\\(\d{1,3})/', function($matches) {
            return chr(octdec($matches[1]));
        }, $string);
        
        return $string;
    }

    private function tryBinaryAnalysis($filePath)
    {
        try {
            Log::info("Trying binary analysis fallback");
            
            $content = file_get_contents($filePath);
            $text = '';
            
            // Extract readable ASCII text from binary content
            for ($i = 0; $i < strlen($content); $i++) {
                $char = $content[$i];
                $ascii = ord($char);
                
                // Include printable ASCII characters and common whitespace
                if (($ascii >= 32 && $ascii <= 126) || $ascii == 9 || $ascii == 10 || $ascii == 13) {
                    $text .= $char;
                } else {
                    $text .= ' ';
                }
            }
            
            // Clean up the extracted text
            $text = preg_replace('/\s+/', ' ', $text);
            $words = explode(' ', $text);
            
            // Filter out PDF commands and keep likely text content
            $filteredWords = [];
            foreach ($words as $word) {
                $word = trim($word);
                if (strlen($word) >= 2 && 
                    !preg_match('/^[0-9.]+$/', $word) && 
                    !preg_match('/^\/[A-Za-z]+/', $word) &&
                    !in_array($word, ['obj', 'endobj', 'stream', 'endstream', 'xref', 'trailer'])) {
                    $filteredWords[] = $word;
                }
            }
            
            $result = implode(' ', $filteredWords);
            
            if (strlen($result) > 50) {
                Log::info("Success with binary analysis, extracted " . strlen($result) . " characters");
                return $this->cleanText($result);
            }
            
        } catch (\Exception $e) {
            Log::error("Binary analysis failed: " . $e->getMessage());
        }
        
        return null;
    }

    private function cleanText($text)
    {
        if (empty($text)) {
            return null;
        }

        // Remove null bytes and control characters but preserve printable characters
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        // Ensure proper UTF-8 encoding and fix common issues
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        }
        
        // Fix double-encoded UTF-8 (common issue with PDFs)
        $text = $this->fixDoubleEncodedUtf8($text);
        
        // Normalize line breaks
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        
        // Clean up excessive whitespace while preserving paragraph structure
        $text = preg_replace('/[ \t]+/', ' ', $text); // Multiple spaces/tabs to single space
        $text = preg_replace('/\n\s*\n\s*\n/', "\n\n", $text); // Multiple line breaks to double
        
        // Remove common PDF artifacts but keep the content readable
        $artifacts = [
            '/\b\d+\s+\d+\s+obj\b/',
            '/\bendobj\b/',
            '/\bstream\b/',
            '/\bendstream\b/',
            '/\bxref\b/',
            '/\btrailer\b/',
            '/\bstartxref\b/',
            '/%%EOF/',
            '/\/[A-Za-z]+\d*\s+\d+/',
            '/\b[0-9.]+\s+[0-9.]+\s+[0-9.]+\s+[A-Za-z]+\b/'
        ];
        
        foreach ($artifacts as $pattern) {
            $text = preg_replace($pattern, '', $text);
        }
        
        // Remove repetitive content (headers/footers) but preserve important repeated content
        $lines = explode("\n", $text);
        $filteredLines = [];
        
        if (count($lines) > 10) {
            $lineCounts = array_count_values(array_map('trim', $lines));
            
            foreach ($lines as $line) {
                $trimmedLine = trim($line);
                // Keep line if it's not empty and either appears <= 3 times or is longer than 50 chars
                if (!empty($trimmedLine) && 
                    ($lineCounts[$trimmedLine] <= 3 || strlen($trimmedLine) > 50)) {
                    $filteredLines[] = $line;
                }
            }
            
            $text = implode("\n", $filteredLines);
        }
        
        // Final cleanup
        $text = trim($text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text); // Max 2 consecutive line breaks
        
        // Return null if we don't have enough meaningful content
        if (strlen($text) < 20) {
            return null;
        }
        
        return $text;
    }

    private function fixDoubleEncodedUtf8($text)
    {
        // Fix common double-encoding issues
        $fixes = [
            // Common Portuguese characters that get double-encoded
            'Ã¡' => 'á', 'Ã ' => 'à', 'Ã¢' => 'â', 'Ã£' => 'ã',
            'Ã©' => 'é', 'Ã¨' => 'è', 'Ãª' => 'ê',
            'Ã­' => 'í', 'Ã¬' => 'ì', 'Ã®' => 'î',
            'Ã³' => 'ó', 'Ã²' => 'ò', 'Ã´' => 'ô', 'Ãµ' => 'õ',
            'Ãº' => 'ú', 'Ã¹' => 'ù', 'Ã»' => 'û',
            'Ã§' => 'ç', 'Ã±' => 'ñ',
            'Ã‰' => 'É', 'Ãˆ' => 'È', 'ÃŠ' => 'Ê',
            'ÃŒ' => 'Ì', 'ÃŽ' => 'Î',
            'Ãš' => 'Ú', 'Ã™' => 'Ù', 'Ã›' => 'Û',
            'Ã‡' => 'Ç',
        ];
        
        foreach ($fixes as $wrong => $correct) {
            $text = str_replace($wrong, $correct, $text);
        }
        
        return $text;
    }
}

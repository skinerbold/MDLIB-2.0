import sys
import argparse
import json
from pathlib import Path

def extract_with_pymupdf(pdf_path):
    try:
        import fitz
        doc = fitz.open(pdf_path)
        text = ''
        for page in doc:
            page_text = page.get_text()
            if page_text:
                text += page_text + '\n'
        doc.close()
        return text.strip() if text.strip() else None
    except ImportError:
        return None
    except Exception:
        return None

def extract_with_pdfplumber(pdf_path):
    try:
        import pdfplumber
        text = ''
        with pdfplumber.open(pdf_path) as pdf:
            for page in pdf.pages:
                page_text = page.extract_text()
                if page_text:
                    text += page_text + '\n'
        return text.strip() if text.strip() else None
    except ImportError:
        return None
    except Exception:
        return None

def extract_with_pypdf2(pdf_path):
    try:
        import PyPDF2
        with open(pdf_path, 'rb') as file:
            reader = PyPDF2.PdfReader(file)
            text = ''
            for page in reader.pages:
                page_text = page.extract_text()
                if page_text:
                    text += page_text + '\n'
            return text.strip() if text.strip() else None
    except ImportError:
        return None
    except Exception:
        return None

def extract_text(pdf_path):
    if not Path(pdf_path).exists():
        return {
            'success': False,
            'error': f'Arquivo nao encontrado: {pdf_path}',
            'method': None
        }
    
    methods = [
        ('PyMuPDF', extract_with_pymupdf),
        ('pdfplumber', extract_with_pdfplumber),
        ('PyPDF2', extract_with_pypdf2),
    ]
    
    for method_name, method_func in methods:
        try:
            text = method_func(pdf_path)
            if text and len(text.strip()) > 0:
                return {
                    'success': True,
                    'text': text,
                    'method': method_name,
                    'char_count': len(text),
                    'word_count': len(text.split())
                }
        except Exception:
            continue
    
    return {
        'success': False,
        'error': 'Nenhum metodo de extracao funcionou',
        'method': None
    }

def main():
    parser = argparse.ArgumentParser(description='Extrai texto de arquivos PDF')
    parser.add_argument('pdf_path', help='Caminho para o arquivo PDF')
    parser.add_argument('--json', action='store_true', help='Saida em formato JSON')
    
    args = parser.parse_args()
    
    result = extract_text(args.pdf_path)
    
    if args.json:
        print(json.dumps(result, ensure_ascii=False, indent=2))
    else:
        if result['success']:
            print(f"Sucesso usando {result['method']}")
            print(f"Caracteres: {result['char_count']}, Palavras: {result['word_count']}")
            print("Texto extraido:")
            print("-" * 60)
            print(result['text'])
        else:
            print(f"Falha: {result['error']}")

if __name__ == '__main__':
    main()

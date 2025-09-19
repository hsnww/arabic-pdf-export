# Arabic PDF Export Package

A comprehensive Laravel package for generating PDFs with proper Arabic text support, including RTL (Right-to-Left) text direction and Arabic font rendering.

## Features

- **Full Arabic Text Support**: Proper RTL text direction and Arabic font rendering
- **Multiple PDF Engines**: Support for both TCPDF and DomPDF engines
- **Arabic Fonts Included**: Amiri and Cairo font families with multiple weights
- **Quran Text Support**: Special formatting for Quranic text
- **Laravel Integration**: Easy integration with Laravel applications
- **Blade Directives**: Custom Blade directives for PDF generation
- **Flexible Configuration**: Extensive configuration options
- **Console Commands**: Artisan commands for font installation

## Installation

1. Install the package via Composer:

```bash
composer require arabic-pdf-export/arabic-pdf-export
```

2. Publish the configuration file:

```bash
php artisan vendor:publish --provider="ArabicPdfExport\ServiceProvider" --tag="config"
```

3. Install Arabic fonts to public directory:

```bash
php artisan arabic-pdf:install-fonts
```

## Configuration

The package configuration is located in `config/arabic-pdf.php`. You can customize:

- PDF engine (TCPDF or DomPDF)
- Page format and orientation
- Margins
- Font settings
- Default CSS classes

## Usage

### Basic Usage

```php
use ArabicPdfExport\Facades\ArabicPdf;

// Generate a simple Arabic document
$html = '<div class="arabic-title">عنوان المستند</div>
         <div class="arabic-paragraph">هذا نص باللغة العربية</div>';

$pdf = ArabicPdf::generate($html);

// Download the PDF
ArabicPdf::download($html, 'document.pdf');

// Save to file
ArabicPdf::save($html, storage_path('app/document.pdf'));
```

### Using Service Container

```php
use ArabicPdfExport\ArabicPdfService;

$arabicPdf = app('arabic-pdf');

$html = '<div class="arabic-text">مرحباً بالعالم</div>';
$pdf = $arabicPdf->generate($html);
```

### Blade Directives

```blade
{{-- Generate PDF content --}}
@arabicPdf('<div class="arabic-title">عنوان</div><div class="arabic-text">محتوى</div>')

{{-- Download PDF --}}
@arabicPdfDownload('<div class="arabic-text">محتوى للتحميل</div>', 'document.pdf')

{{-- Save PDF --}}
@arabicPdfSave('<div class="arabic-text">محتوى للحفظ</div>', storage_path('app/saved.pdf'))
```

### Creating Documents

#### Simple Document

```php
$pdf = ArabicPdf::createSimpleDocument(
    'عنوان المستند',
    'هذا محتوى المستند باللغة العربية',
    ['title' => 'My Document']
);
```

#### Quran Document

```php
$pdf = ArabicPdf::createQuranDocument(
    'البقرة',
    '255',
    'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ',
    ['title' => 'آية الكرسي']
);
```

### Custom HTML with CSS Classes

```php
$html = '
<div class="arabic-title">عنوان رئيسي</div>
<div class="arabic-subtitle">عنوان فرعي</div>
<div class="arabic-paragraph">هذه فقرة عربية مع محاذاة صحيحة للنص.</div>
<div class="quran-text">نص قرآني بخط خاص</div>
<div class="arabic-text">نص عادي باللغة العربية</div>
';

$pdf = ArabicPdf::generate($html);
```

### Configuration Options

```php
// Set custom configuration
ArabicPdf::setConfig([
    'engine' => 'tcpdf',
    'page_format' => 'A4',
    'orientation' => 'P',
    'margin_top' => 20,
    'default_font' => 'Cairo-Regular'
]);

// Get current configuration
$config = ArabicPdf::getConfig();

// Set default font
ArabicPdf::setDefaultFont('Amiri-Bold');

// Get available fonts
$fonts = ArabicPdf::getAvailableFonts();
```

## Available Fonts

The package includes the following Arabic fonts:

- **Amiri Family**:
  - Amiri-Regular
  - Amiri-Bold
  - Amiri-Italic
  - Amiri-BoldItalic
  - AmiriQuran (for Quranic text)
  - AmiriQuranColored (colored Quranic text)

- **Cairo Family**:
  - Cairo-Regular
  - Cairo-Bold

## CSS Classes

The package provides predefined CSS classes for Arabic text:

- `.arabic-title`: For main titles
- `.arabic-subtitle`: For subtitles
- `.arabic-paragraph`: For regular paragraphs
- `.arabic-text`: For general Arabic text
- `.quran-text`: For Quranic text

## Advanced Usage

### Custom PDF Engine

```php
// Use TCPDF engine
$pdf = ArabicPdf::generateWithTcpdf($html);

// Use DomPDF engine
$pdf = ArabicPdf::generateWithDompdf($html);
```

### Custom Configuration

```php
$arabicPdf = new ArabicPdfService([
    'engine' => 'tcpdf',
    'page_format' => 'A4',
    'orientation' => 'L', // Landscape
    'margin_top' => 25,
    'margin_right' => 20,
    'margin_bottom' => 25,
    'margin_left' => 20,
    'default_font' => 'Cairo-Bold'
]);
```

## Requirements

- PHP 8.0 or higher
- Laravel 9.0 or higher
- TCPDF 6.4 or higher
- DomPDF 2.0 or higher

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For support, please open an issue on the GitHub repository.

## Changelog

### Version 1.0.0
- Initial release
- Arabic text support with RTL direction
- Multiple PDF engines (TCPDF, DomPDF)
- Arabic fonts included
- Laravel integration
- Blade directives
- Console commands

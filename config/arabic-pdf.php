<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PDF Engine
    |--------------------------------------------------------------------------
    |
    | Choose the PDF engine to use. Available options:
    | - tcpdf: More reliable for Arabic text, better font support
    | - dompdf: Faster rendering, good for simple documents
    | - laravel-dompdf: Use existing Laravel DomPDF package
    |
    */
    'engine' => env('ARABIC_PDF_ENGINE', 'laravel-dompdf'),

    /*
    |--------------------------------------------------------------------------
    | Page Settings
    |--------------------------------------------------------------------------
    |
    | Default page format and orientation settings
    |
    */
    'page_format' => env('ARABIC_PDF_PAGE_FORMAT', 'A4'),
    'orientation' => env('ARABIC_PDF_ORIENTATION', 'P'), // P for Portrait, L for Landscape

    /*
    |--------------------------------------------------------------------------
    | Margins
    |--------------------------------------------------------------------------
    |
    | Page margins in millimeters
    |
    */
    'margin_top' => env('ARABIC_PDF_MARGIN_TOP', 15),
    'margin_right' => env('ARABIC_PDF_MARGIN_RIGHT', 15),
    'margin_bottom' => env('ARABIC_PDF_MARGIN_BOTTOM', 15),
    'margin_left' => env('ARABIC_PDF_MARGIN_LEFT', 15),

    /*
    |--------------------------------------------------------------------------
    | Font Settings
    |--------------------------------------------------------------------------
    |
    | Path to Arabic fonts and default font
    |
    */
    'font_path' => __DIR__ . '/../src/fonts/',
    'default_font' => 'Amiri-Regular',

    /*
    |--------------------------------------------------------------------------
    | Temporary Path
    |--------------------------------------------------------------------------
    |
    | Path for temporary files
    |
    */
    'temp_path' => storage_path('app/temp/'),

    /*
    |--------------------------------------------------------------------------
    | Available Fonts
    |--------------------------------------------------------------------------
    |
    | List of available Arabic fonts
    |
    */
    'fonts' => [
        'Amiri-Regular' => 'Amiri-Regular.ttf',
        'Amiri-Bold' => 'Amiri-Bold.ttf',
        'Amiri-Italic' => 'Amiri-Italic.ttf',
        'Amiri-BoldItalic' => 'Amiri-BoldItalic.ttf',
        'AmiriQuran' => 'AmiriQuran.ttf',
        'AmiriQuranColored' => 'AmiriQuranColored.ttf',
        'Cairo-Regular' => 'Cairo-Regular.ttf',
        'Cairo-Bold' => 'Cairo-Bold.ttf',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default CSS Classes
    |--------------------------------------------------------------------------
    |
    | Default CSS classes for Arabic text styling
    |
    */
    'css_classes' => [
        'title' => 'arabic-title',
        'subtitle' => 'arabic-subtitle',
        'paragraph' => 'arabic-paragraph',
        'quran' => 'quran-text',
        'text' => 'arabic-text',
    ],
];

<?php

namespace ArabicPdfExport;

use Illuminate\Support\Facades\Storage;

// Check if TCPDF is available
if (class_exists('TCPDF')) {
    class_alias('TCPDF', 'ArabicPdfExport\TCPDF');
}

// Check if DomPDF is available
if (class_exists('Dompdf\Dompdf')) {
    class_alias('Dompdf\Dompdf', 'ArabicPdfExport\Dompdf');
    class_alias('Dompdf\Options', 'ArabicPdfExport\DompdfOptions');
}

// Check if Laravel DomPDF is available
if (class_exists('Barryvdh\DomPDF\PDF')) {
    class_alias('Barryvdh\DomPDF\PDF', 'ArabicPdfExport\LaravelDompdf');
}

class ArabicPdfService
{
    protected $config;
    protected $fonts = [];
    protected $defaultFont = 'Amiri-Regular';

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'engine' => 'tcpdf', // 'tcpdf', 'dompdf', or 'laravel-dompdf'
            'page_format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 15,
            'margin_right' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'font_path' => __DIR__ . '/fonts/',
            'temp_path' => storage_path('app/temp/'),
        ], $config);

        $this->registerFonts();
        
        // Auto-detect and set recommended engine if not specified
        if (!isset($config['engine'])) {
            $recommendedEngine = $this->getRecommendedEngine();
            if ($recommendedEngine) {
                $this->config['engine'] = $recommendedEngine;
            }
        }
    }

    /**
     * Register available Arabic fonts
     */
    protected function registerFonts()
    {
        $fontPath = $this->config['font_path'];
        
        $this->fonts = [
            'Amiri-Regular' => $fontPath . 'Amiri-Regular.ttf',
            'Amiri-Bold' => $fontPath . 'Amiri-Bold.ttf',
            'Amiri-Italic' => $fontPath . 'Amiri-Italic.ttf',
            'Amiri-BoldItalic' => $fontPath . 'Amiri-BoldItalic.ttf',
            'AmiriQuran' => $fontPath . 'AmiriQuran.ttf',
            'AmiriQuranColored' => $fontPath . 'AmiriQuranColored.ttf',
            'Cairo-Regular' => $fontPath . 'Cairo-Regular.ttf',
            'Cairo-Bold' => $fontPath . 'Cairo-Bold.ttf',
        ];
    }

    /**
     * Generate PDF using TCPDF engine
     */
    public function generateWithTcpdf(string $html, array $options = [])
    {
        if (!class_exists('TCPDF')) {
            throw new \Exception('TCPDF is not installed. Please install it: composer require tecnickcom/tcpdf');
        }

        $pdf = new \TCPDF(
            $this->config['orientation'],
            'mm',
            $this->config['page_format'],
            true,
            'UTF-8',
            false
        );

        // Set document information
        $pdf->SetCreator('Arabic PDF Export');
        $pdf->SetAuthor('Arabic PDF Export');
        $pdf->SetTitle($options['title'] ?? 'Arabic Document');
        $pdf->SetSubject($options['subject'] ?? 'Arabic PDF Document');

        // Set margins
        $pdf->SetMargins(
            $this->config['margin_left'],
            $this->config['margin_top'],
            $this->config['margin_right']
        );
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, $this->config['margin_bottom']);

        // Add a page
        $pdf->AddPage();

        // Register Arabic fonts
        foreach ($this->fonts as $fontName => $fontPath) {
            if (file_exists($fontPath)) {
                $pdf->addTTFfont($fontPath, 'TrueTypeUnicode', '', 32);
            }
        }

        // Set default font
        $pdf->SetFont($this->defaultFont, '', 12);

        // Write HTML content
        $pdf->writeHTML($this->prepareArabicHtml($html), true, false, true, false, '');

        return $pdf;
    }

    /**
     * Generate PDF using DomPDF engine
     */
    public function generateWithDompdf(string $html, array $options = [])
    {
        if (!class_exists('Dompdf\Dompdf')) {
            throw new \Exception('DomPDF is not installed. Please install it: composer require dompdf/dompdf');
        }

        $options_dompdf = new \Dompdf\Options();
        $options_dompdf->set('defaultFont', $this->defaultFont);
        $options_dompdf->set('isRemoteEnabled', true);
        $options_dompdf->set('isHtml5ParserEnabled', true);
        $options_dompdf->set('defaultMediaType', 'print');
        $options_dompdf->set('isFontSubsettingEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options_dompdf);

        // Load HTML content
        $dompdf->loadHtml($this->prepareArabicHtml($html));

        // Set paper size and orientation
        $dompdf->setPaper($this->config['page_format'], $this->config['orientation']);

        // Render the HTML as PDF
        $dompdf->render();

        return $dompdf;
    }

    /**
     * Generate PDF using Laravel DomPDF engine
     */
    public function generateWithLaravelDompdf(string $html, array $options = [])
    {
        if (!class_exists('Barryvdh\DomPDF\PDF')) {
            throw new \Exception('Laravel DomPDF is not installed. Please install it: composer require barryvdh/laravel-dompdf');
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($this->prepareArabicHtml($html));
        $pdf->setPaper($this->config['page_format'], $this->config['orientation']);
        
        return $pdf;
    }

    /**
     * Generate PDF with the configured engine
     */
    public function generate(string $html, array $options = [])
    {
        if ($this->config['engine'] === 'tcpdf') {
            return $this->generateWithTcpdf($html, $options);
        } elseif ($this->config['engine'] === 'laravel-dompdf') {
            return $this->generateWithLaravelDompdf($html, $options);
        } else {
            return $this->generateWithDompdf($html, $options);
        }
    }

    /**
     * Save PDF to file
     */
    public function save(string $html, string $filename, array $options = [])
    {
        $pdf = $this->generate($html, $options);
        
        if ($this->config['engine'] === 'tcpdf') {
            $pdf->Output($filename, 'F');
        } elseif ($this->config['engine'] === 'laravel-dompdf') {
            $pdf->save($filename);
        } else {
            file_put_contents($filename, $pdf->output());
        }

        return $filename;
    }

    /**
     * Download PDF
     */
    public function download(string $html, string $filename = 'arabic-document.pdf', array $options = [])
    {
        $pdf = $this->generate($html, $options);
        
        if ($this->config['engine'] === 'tcpdf') {
            $pdf->Output($filename, 'D');
        } elseif ($this->config['engine'] === 'laravel-dompdf') {
            return $pdf->download($filename);
        } else {
            $pdf->stream($filename);
        }
    }

    /**
     * Get PDF as string
     */
    public function output(string $html, array $options = [])
    {
        $pdf = $this->generate($html, $options);
        
        if ($this->config['engine'] === 'tcpdf') {
            return $pdf->Output('', 'S');
        } elseif ($this->config['engine'] === 'laravel-dompdf') {
            return $pdf->output();
        } else {
            return $pdf->output();
        }
    }

    /**
     * Prepare HTML for proper Arabic rendering
     */
    protected function prepareArabicHtml(string $html)
    {
        // Add CSS for Arabic text direction and font
        $css = '
        <style>
            @font-face {
                font-family: "Amiri";
                src: url("' . $this->fonts['Amiri-Regular'] . '") format("truetype");
            }
            @font-face {
                font-family: "Amiri";
                src: url("' . $this->fonts['Amiri-Bold'] . '") format("truetype");
                font-weight: bold;
            }
            @font-face {
                font-family: "Cairo";
                src: url("' . $this->fonts['Cairo-Regular'] . '") format("truetype");
            }
            @font-face {
                font-family: "Cairo";
                src: url("' . $this->fonts['Cairo-Bold'] . '") format("truetype");
                font-weight: bold;
            }
            body {
                font-family: "Amiri", "Cairo", Arial, sans-serif;
                direction: rtl;
                text-align: right;
                line-height: 1.6;
            }
            .arabic-text {
                direction: rtl;
                text-align: right;
                font-family: "Amiri", "Cairo", Arial, sans-serif;
            }
            .arabic-title {
                direction: rtl;
                text-align: center;
                font-family: "Amiri", "Cairo", Arial, sans-serif;
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 20px;
            }
            .arabic-subtitle {
                direction: rtl;
                text-align: right;
                font-family: "Amiri", "Cairo", Arial, sans-serif;
                font-size: 14px;
                font-weight: bold;
                margin-top: 15px;
                margin-bottom: 10px;
            }
            .arabic-paragraph {
                direction: rtl;
                text-align: justify;
                font-family: "Amiri", "Cairo", Arial, sans-serif;
                margin-bottom: 10px;
                text-indent: 20px;
            }
            .quran-text {
                direction: rtl;
                text-align: center;
                font-family: "AmiriQuran", "Amiri", Arial, sans-serif;
                font-size: 16px;
                line-height: 2;
                margin: 20px 0;
            }
        </style>';

        return $css . $html;
    }

    /**
     * Set configuration
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * Get configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set default font
     */
    public function setDefaultFont(string $fontName)
    {
        if (isset($this->fonts[$fontName])) {
            $this->defaultFont = $fontName;
        }
        return $this;
    }

    /**
     * Get available fonts
     */
    public function getAvailableFonts()
    {
        return array_keys($this->fonts);
    }

    /**
     * Get available PDF engines
     */
    public function getAvailableEngines()
    {
        $engines = [];
        
        if (class_exists('TCPDF')) {
            $engines['tcpdf'] = 'TCPDF (Recommended for Arabic)';
        }
        
        if (class_exists('Dompdf\Dompdf')) {
            $engines['dompdf'] = 'DomPDF (Fast rendering)';
        }
        
        if (class_exists('Barryvdh\DomPDF\PDF')) {
            $engines['laravel-dompdf'] = 'Laravel DomPDF (Laravel integration)';
        }
        
        return $engines;
    }

    /**
     * Check if PDF engine is available
     */
    public function isEngineAvailable($engine)
    {
        $availableEngines = $this->getAvailableEngines();
        return isset($availableEngines[$engine]);
    }

    /**
     * Get recommended engine based on available packages
     */
    public function getRecommendedEngine()
    {
        $engines = $this->getAvailableEngines();
        
        // Priority order
        $priority = ['tcpdf', 'laravel-dompdf', 'dompdf'];
        
        foreach ($priority as $engine) {
            if (isset($engines[$engine])) {
                return $engine;
            }
        }
        
        return null;
    }

    /**
     * Check system requirements
     */
    public function checkRequirements()
    {
        $requirements = [
            'php_version' => version_compare(PHP_VERSION, '7.4.0', '>='),
            'laravel_support' => class_exists('Illuminate\Support\ServiceProvider'),
            'pdf_engines' => $this->getAvailableEngines(),
        ];

        $requirements['status'] = !empty($requirements['pdf_engines']) && $requirements['php_version'] && $requirements['laravel_support'];
        
        return $requirements;
    }

    /**
     * Get installation instructions for missing packages
     */
    public function getInstallationInstructions()
    {
        $instructions = [];
        $availableEngines = $this->getAvailableEngines();
        
        if (empty($availableEngines)) {
            $instructions[] = "No PDF engines found. Please install one of the following:";
            $instructions[] = "• TCPDF (Recommended): composer require tecnickcom/tcpdf";
            $instructions[] = "• DomPDF: composer require dompdf/dompdf";
            $instructions[] = "• Laravel DomPDF: composer require barryvdh/laravel-dompdf";
        }
        
        return $instructions;
    }

    /**
     * Create a simple Arabic document
     */
    public function createSimpleDocument(string $title, string $content, array $options = [])
    {
        $html = '
        <div class="arabic-title">' . htmlspecialchars($title) . '</div>
        <div class="arabic-paragraph">' . nl2br(htmlspecialchars($content)) . '</div>
        ';

        return $this->generate($html, $options);
    }

    /**
     * Create a Quran verse document
     */
    public function createQuranDocument(string $surah, string $ayah, string $text, array $options = [])
    {
        $html = '
        <div class="arabic-title">سورة ' . htmlspecialchars($surah) . ' - الآية ' . htmlspecialchars($ayah) . '</div>
        <div class="quran-text">' . htmlspecialchars($text) . '</div>
        ';

        return $this->generate($html, $options);
    }
}

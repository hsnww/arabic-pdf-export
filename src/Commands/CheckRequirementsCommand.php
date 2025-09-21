<?php

namespace ArabicPdfExport\Commands;

use Illuminate\Console\Command;
use ArabicPdfExport\ArabicPdfService;

class CheckRequirementsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'arabic-pdf:check-requirements';

    /**
     * The console command description.
     */
    protected $description = 'Check Arabic PDF Export requirements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking Arabic PDF Export requirements...');
        $this->newLine();

        $arabicPdf = new ArabicPdfService();
        $requirements = $arabicPdf->checkRequirements();

        // Check PHP version
        if ($requirements['php_version']) {
            $this->info('✅ PHP Version: ' . PHP_VERSION . ' (Compatible)');
        } else {
            $this->error('❌ PHP Version: ' . PHP_VERSION . ' (Requires PHP 7.4+)');
        }

        // Check Laravel Support
        if ($requirements['laravel_support']) {
            $this->info('✅ Laravel Support: Available');
        } else {
            $this->error('❌ Laravel Support: Not Available');
        }

        // Check PDF Engines
        $engines = $requirements['pdf_engines'];
        if (!empty($engines)) {
            $this->info('✅ PDF Engines Available:');
            foreach ($engines as $engine => $description) {
                $this->line("   • {$engine}: {$description}");
            }
        } else {
            $this->error('❌ No PDF Engines Found');
            $this->newLine();
            $this->warn('Installation Instructions:');
            $instructions = $arabicPdf->getInstallationInstructions();
            foreach ($instructions as $instruction) {
                $this->line($instruction);
            }
        }

        $this->newLine();

        // Overall status
        if ($requirements['status']) {
            $this->info('🎉 All requirements met! Arabic PDF Export is ready to use.');
            
            // Show recommended engine
            $recommendedEngine = $arabicPdf->getRecommendedEngine();
            if ($recommendedEngine) {
                $this->info("📋 Recommended Engine: {$recommendedEngine}");
            }
        } else {
            $this->error('❌ Some requirements are missing. Please install the required packages.');
            return 1;
        }

        return 0;
    }
}

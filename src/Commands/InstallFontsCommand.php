<?php

namespace ArabicPdfExport\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallFontsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'arabic-pdf:install-fonts {--force : Force overwrite existing fonts}';

    /**
     * The console command description.
     */
    protected $description = 'Install Arabic fonts to the public directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing Arabic fonts...');

        // Get the package root directory
        $packageRoot = dirname(dirname(__DIR__));
        $sourcePath = $packageRoot . '/src/fonts/';
        $destinationPath = public_path('fonts/arabic/');

        // Check if source fonts directory exists
        if (!File::exists($sourcePath)) {
            $this->error("Source fonts directory not found: {$sourcePath}");
            $this->error("Please make sure the package is properly installed.");
            return 1;
        }

        // Create destination directory if it doesn't exist
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
            $this->info('Created fonts directory: ' . $destinationPath);
        }

        // Copy font files
        $fontFiles = File::files($sourcePath);
        $copiedCount = 0;

        foreach ($fontFiles as $file) {
            $filename = $file->getFilename();
            $destination = $destinationPath . $filename;

            if (File::exists($destination) && !$this->option('force')) {
                $this->warn("Font already exists: {$filename}");
                continue;
            }

            if (File::copy($file->getPathname(), $destination)) {
                $this->info("Copied: {$filename}");
                $copiedCount++;
            } else {
                $this->error("Failed to copy: {$filename}");
            }
        }

        $this->info("Successfully installed {$copiedCount} font files to: {$destinationPath}");
        $this->info('Fonts are now available for use in your Arabic PDF documents.');
    }
}

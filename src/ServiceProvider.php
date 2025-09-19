<?php

namespace ArabicPdfExport;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/arabic-pdf.php', 'arabic-pdf');

        $this->app->singleton('arabic-pdf', function ($app) {
            return new ArabicPdfService(config('arabic-pdf'));
        });

        $this->app->alias('arabic-pdf', ArabicPdfService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/arabic-pdf.php' => config_path('arabic-pdf.php'),
        ], 'config');

        // Publish fonts
        $this->publishes([
            __DIR__ . '/fonts/' => public_path('fonts/arabic/'),
        ], 'fonts');

        // Register Blade directives
        $this->registerBladeDirectives();

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallFontsCommand::class,
            ]);
        }
    }

    /**
     * Register Blade directives for Arabic PDF
     */
    protected function registerBladeDirectives()
    {
        // @arabicPdf directive
        Blade::directive('arabicPdf', function ($expression) {
            return "<?php echo app('arabic-pdf')->output($expression); ?>";
        });

        // @arabicPdfDownload directive
        Blade::directive('arabicPdfDownload', function ($expression) {
            return "<?php app('arabic-pdf')->download($expression); ?>";
        });

        // @arabicPdfSave directive
        Blade::directive('arabicPdfSave', function ($expression) {
            return "<?php echo app('arabic-pdf')->save($expression); ?>";
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides()
    {
        return ['arabic-pdf'];
    }
}

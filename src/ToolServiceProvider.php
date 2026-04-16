<?php

namespace CodencoDev\NovaGridSystem;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/nova-grid-system.php' => config_path('nova-grid-system.php'),
        ], 'nova-grid-system');

        Nova::serving(function (ServingNova $event) {

            /**
             * HELPERS MACROS
             */
            NovaRequest::macro('getRequestType', function () {
                if ($this->isCreateOrAttachRequest()) {
                    return 'creating';
                } elseif ($this->isUpdateOrUpdateAttachedRequest()) {
                    return 'updating';
                }
                return 'detail';
            });

            NovaRequest::macro('isActivate', function (string $meta, string $context, bool $scope = false) {
                $requestType = $this->getRequestType();

                return
                    ($context == 'all'
                        || ($context == 'forms' ? collect(['creating', 'updating'])->contains($requestType) : $requestType == $context)
                    )
                    && config('nova-grid-system.nova_grid_system_enabled', true)
                    && config('nova-grid-system.'.$requestType.'.'.$meta, true)
                    && ($scope ? $this->isActivateGlobally($meta) : true);
            });

            NovaRequest::macro('isActivateGlobally', function (string $meta) {
                $requestType = $this->getRequestType();
                return config('nova-grid-system.'.$meta.'_scope.'.$requestType, true);
            });

            /** @var NovaRequest $novaRequest */
            $novaRequest = app(NovaRequest::class);

            Field::macro('withSize', function (string $size = 'w-full') use ($novaRequest) {
                if ($novaRequest->isActivateGlobally('stacked')) {
                    $this->stacked();
                }
                return $this->withMeta(['size' => $size]);
            });

            Field::macro('size', function ($size = 'w-full') use ($novaRequest) {
                if ($novaRequest->isActivate('size', 'all', true)) {
                    $this->withSize($size);
                }
                return $this;
            });

            Field::macro('removeBottomBorder', function ($remove = true) use ($novaRequest) {
                if ($novaRequest->isActivate('remove_bottom_border', 'all', true)) {
                    $this->withMeta(['removeBottomBorder' => $remove]);
                }
                return $this;
            });

            Field::macro('sizeOnCreating', function ($size = 'w-full') use ($novaRequest) {
                if ($novaRequest->isActivate('size', 'creating')) {
                    if (config('nova-grid-system.stacked_auto', true) && config('nova-grid-system.creating.stacked', true)) {
                        $this->stacked();
                    }
                    $this->withMeta(['size' => $size]);
                }
                return $this;
            });

            Field::macro('stackedOnCreating', function ($stacked = true) use ($novaRequest) {
                if ($novaRequest->isActivate('stacked', 'creating')) {
                    $this->stacked($stacked);
                }
                return $this;
            });

            Field::macro('removeBottomBorderOnCreating', function ($remove = true) use ($novaRequest) {
                if ($novaRequest->isActivate('remove_bottom_border', 'creating')) {
                    $this->withMeta(['removeBottomBorder' => $remove]);
                }
                return $this;
            });

            Field::macro('sizeOnUpdating', function ($size = 'w-full') use ($novaRequest) {
                if ($novaRequest->isActivate('size', 'updating')) {
                    $this->withSize($size);
                }
                return $this;
            });

            Field::macro('stackedOnUpdating', function ($stacked = true) use ($novaRequest) {
                if ($novaRequest->isActivate('stacked', 'updating')) {
                    $this->stacked($stacked);
                }
                return $this;
            });

            Field::macro('removeBottomBorderOnUpdating', function ($remove = true) use ($novaRequest) {
                if ($novaRequest->isActivate('remove_bottom_border', 'updating')) {
                    $this->withMeta(['removeBottomBorder' => $remove]);
                }
                return $this;
            });

            Field::macro('sizeOnForms', function ($size = 'w-full') use ($novaRequest) {
                if ($novaRequest->isActivate('size', 'forms')) {
                    $this->withSize($size);
                }
                return $this;
            });

            Field::macro('stackedOnForms', function ($stacked = true) use ($novaRequest) {
                if ($novaRequest->isActivate('stacked', 'forms')) {
                    $this->stacked($stacked);
                }
                return $this;
            });

            Field::macro('removeBottomBorderOnForms', function ($remove = true) use ($novaRequest) {
                if ($novaRequest->isActivate('remove_bottom_border', 'forms')) {
                    $this->withMeta(['removeBottomBorder' => $remove]);
                }
                return $this;
            });

            Field::macro('sizeOnDetail', function ($size = 'w-full') use ($novaRequest) {
                if ($novaRequest->isActivate('size', 'detail')) {
                    $this->withSize($size);
                }
                return $this;
            });

            Field::macro('stackedOnDetail', function ($stacked = true) use ($novaRequest) {
                if ($novaRequest->isActivate('stacked', 'detail')) {
                    $this->stacked($stacked);
                }
                return $this;
            });

            Field::macro('removeBottomBorderOnDetail', function ($remove = true) use ($novaRequest) {
                if ($novaRequest->isActivate('remove_bottom_border', 'detail')) {
                    $this->withMeta(['removeBottomBorder' => $remove]);
                }
                return $this;
            });
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

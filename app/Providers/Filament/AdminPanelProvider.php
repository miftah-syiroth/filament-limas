<?php

namespace App\Providers\Filament;

use App\Enums\NavigationGroup;
use App\Filament\Pages\Auth\EditProfile;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make(__('navigation.items.documentation'))
                    ->url(fn(): string => url('/docs'), shouldOpenInNewTab: true)
                    ->icon(Heroicon::OutlinedQuestionMarkCircle)
                    ->group(NavigationGroup::Help->getLabel()),
            ])
            ->navigationGroups([
                NavigationGroup::Reference->getLabel(),
                NavigationGroup::Reports->getLabel(),
                NavigationGroup::Administration->getLabel(),
                NavigationGroup::MasterData->getLabel(),
                NavigationGroup::Help->getLabel(),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup(NavigationGroup::Administration)
                    ->navigationSort(7)
                    ->globallySearchable(false),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->profile(isSimple: false, page: EditProfile::class)
            ->globalSearchResourceOptIn()
            ->globalSearchDebounce('750ms')
            ->spa()
            ->maxContentWidth(Width::Full)
            ->databaseNotifications()
            ->favicon(asset('logo.webp'))
            ->sidebarCollapsibleOnDesktop();
    }
}

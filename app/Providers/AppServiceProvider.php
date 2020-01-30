<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use \App\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add('MAIN NAVIGATION');

            $event->menu->add(['header' => 'Invoicing TOOLS']);
            $event->menu->add([
                'text' => 'Invoices',
                'url'  => 'admin/settings',
                'icon' => 'fas fa-fw fa-file-invoice',
            ]);

            $event->menu->add([
                'text' => 'Lawyers',
                'url'  => 'admin/settings',
                'icon' => 'fas fa-fw fa-gavel',
            ]);

            $event->menu->add([
                'text' => 'Clients',
                'url'  => 'admin/settings',
                'icon' => 'fas fa-fw fa-user-friends',
            ]);

            $event->menu->add(['header' => 'Account TOOLS']);

            $event->menu->add([
                'text'        => 'Users',
                'url'         => 'admin/users',
                'icon'        => 'fas fa-fw fa-user-friends',
                'label'       => User::count(),
                'label_color' => 'success',
            ]);

            $event->menu->add([
                'text' => 'profile',
                'url'  => 'admin/settings',
                'icon' => 'fas fa-fw fa-user',
            ]);

            $event->menu->add([
                'text' => 'change_password',
                'url'  => 'admin/settings',
                'icon' => 'fas fa-fw fa-lock',
            ]);

        });
    }
}

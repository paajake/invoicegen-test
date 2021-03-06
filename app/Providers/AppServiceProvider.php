<?php

namespace App\Providers;

use App\Client;
use App\Invoice;
use App\Lawyer;
use App\Rank;
use App\Timesheet;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Cache;
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
                'text' => 'Dashboard',
                'url'  => route('dashboard'),
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ]);

            $event->menu->add([
                'text' => 'Invoicing',
                'icon' => 'fas fa-fw fa-print',
                'active'      => ["invoices/*", "tmesheets/*"],
                'submenu' => [
                    [
                        'text' => 'TimeSheets',
                        'url'  => route("timesheets.index"),
                        'icon' => 'fas fa-user-clock',
                        'label' => Cache::remember('timesheets_count', config('constants.time.half_day'),
                            function (){
                                return Timesheet::count();
                            }),
                    ],
                    [
                        'text' => 'Invoices',
                        'icon' => 'fas fa-file-invoice-dollar',
                        'url'  => route("invoices.index"),
                        'label' => Cache::remember('invoices_count', config('constants.time.half_day'),
                            function (){
                                return Invoice::count();
                            }),
                    ],
                ]
            ]);

            $event->menu->add([
                'text' => 'Staff',
                'icon' => 'fas fa-fw fa-user-friends',
                'active'      => ["ranks/*","lawyers/*"],
                'submenu' => [
                    [
                        'text' => 'Lawyers',
                        'url'  => route("lawyers.index"),
                        'icon' => 'fas fa-fw fa-gavel',
                        'label' => Cache::remember('lawyers_count', config('constants.time.half_day'),
                                                                        function (){
                                                                            return Lawyer::count();
                                                                        }),
                    ],
                    [
                        'text' => 'Ranks',
                        'icon' => 'fas fa-fw fa-university',
                        'url'  => route("ranks.index"),
                        'label' => Cache::remember('ranks_count', config('constants.time.half_day'),
                                                                    function (){
                                                                        return Rank::count();
                                                                    }),
                    ],
                ]
            ]);

            $event->menu->add([
                'text' => 'Clients',
                'url'  => route("clients.index"),
                'icon' => 'fas fa-business-time',
                'label'       => Cache::remember('clients_count', config('constants.time.half_day'),
                                                                    function (){
                                                                        return Client::count();
                                                                    }),
            ]);

            $event->menu->add(['header' => 'Account TOOLS']);

            $event->menu->add([
                'text'        => 'Users',
                'url'         => route('users.index'),
                'active'      => [route('users.index'),route('users.create')],
                'icon'        => 'fas fa-fw fa-lock-open',
                'label'       => Cache::remember('users_count', config('constants.time.half_day'),
                                                                    function (){
                                                                        return User::count();
                                                                    }),
//                'label_color' => 'success',
            ]);

            $event->menu->add([
                'text' => 'profile',
                'url'  => route("users.edit", ["user" => auth()->user()->id]),
                'icon' => 'fas fa-fw fa-user',
            ]);

//            $event->menu->add([
//                'text' => 'change_password',
//                'url'  => 'admin/settings',
//                'icon' => 'fas fa-fw fa-lock',
//            ]);

        });
    }
}

<?php

namespace App\Providers;

use App\Repositories\CaregiverRepository;
use App\Repositories\Interfaces\CaregiverRepositoryInterface;
use App\Repositories\Interfaces\MemoryRepositoryInterface;
use App\Repositories\Interfaces\PatientRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\MemoryRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CaregiverRepositoryInterface::class,CaregiverRepository::class);
        $this->app->bind(PatientRepositoryInterface::class,PatientRepository::class);
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
        $this->app->bind(MemoryRepositoryInterface::class,MemoryRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

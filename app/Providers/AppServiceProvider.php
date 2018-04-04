<?php

namespace App\Providers;

use App\Reference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Category;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Schema::defaultStringLength(191);
        view() -> composer('layouts.menu', function($view){
            $user_id = session('user_id');
            $notifications = [];
            $unread_notifications = 0;
            if($user_id){
                $notifications  = DB::select('SELECT * FROM notifications WHERE recipient_id = ? order by created_at desc', [$user_id]);
                $unread_notifications  = DB::select('SELECT * FROM notifications WHERE recipient_id = ? and seen = ? order by created_at desc', [$user_id, false]);
                $unread_notifications = count($unread_notifications);
                $reference_type = [];
                $sender_names   = [];
                for($i = 0; $i < count($notifications); $i++){
                    $sender_names[$i] = User::where('id', '=', $notifications[$i] -> sender_id) -> first() -> username;
                    if($notifications[$i] -> reference_id){
                        $reference_type[$i] = Reference::where('id', '=', $notifications[$i] -> reference_id) -> first() -> type;
                    }
                }
                $view -> with('sender_names', $sender_names);
                $view -> with('reference_type', $reference_type);
            }
            $view -> with('notifications', $notifications);
            $view -> with('unread_notifications', $unread_notifications);
            $view -> with('categories', Category::where('approved_name','!=', '') -> get());
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

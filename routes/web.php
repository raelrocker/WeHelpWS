<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/queue', function () {
    if (file_exists(__DIR__ . '/queue.pid')) {
        $pid = file_get_contents(__DIR__ . '/queue.pid');
        $result = exec('ps | grep ' . $pid);
        if ($result == '') {
            $command = 'php artisan queue:listen > /dev/null & echo $!';
            $number = exec($command);
            file_put_contents(__DIR__ . '/queue.pid', $number);
        }
    } else {
        $command = 'php artisan queue:listen > /dev/null & echo $!';
        $number = exec($command);
        file_put_contents(__DIR__ . '/queue.pid', $number);
    }
    return 'ok';
});
<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
=======
abstract class Controller
{
    //
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
=======
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
>>>>>>> 736396e76a39659a5d91e88e123606e6322654ba
}

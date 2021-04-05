<?php


namespace Kopokopo\SDK\Facades;


use Illuminate\Support\Facades\Facade;

class KopokopoFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return "Kopokopo";
    }

}

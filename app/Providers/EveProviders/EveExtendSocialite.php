<?php


namespace App\Providers\EveProviders;

use SocialiteProviders\Manager\SocialiteWasCalled;

class EveExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled){
        $socialiteWasCalled->extendSocialite('eveOnline', Provider::class);
    }
}

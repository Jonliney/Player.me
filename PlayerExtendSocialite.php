<?php

namespace SocialiteProviders\Player;

use SocialiteProviders\Manager\SocialiteWasCalled;

class PlayerExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'player', __NAMESPACE__.'\Provider'
        );
    }
}

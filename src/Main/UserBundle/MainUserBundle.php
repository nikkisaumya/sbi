<?php

namespace Main\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MainUserBundle extends Bundle
{
    public function getParent(){
        return 'FOSUserBundle';
    }
}

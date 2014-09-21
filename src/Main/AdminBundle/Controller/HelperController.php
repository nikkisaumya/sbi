<?php

namespace Main\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Main\AdminBundle\Helpers\fakeJsonGenerator;
use Symfony\Component\HttpFoundation\Response;

class HelperController extends Controller {
   public function getFakeJsonAction(){
       $generator = new fakeJsonGenerator();
       return new Response($generator->jsonGenerator());
   }
}
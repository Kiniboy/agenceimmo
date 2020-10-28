<?php
namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route ("/", name="home")
     * @param PropertyRepository $propertyRepository
     * @return Response
     */
    public function index (PropertyRepository $propertyRepository) : Response
    {
        $properties = $propertyRepository->findLatest();
        return new Response($this->render('home/index.html.twig', [
            'properties' => $properties
        ]));
    }

}
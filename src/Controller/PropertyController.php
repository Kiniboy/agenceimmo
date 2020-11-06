<?php
namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;

class PropertyController extends AbstractController
{


    /**
     * @Route("/biens", name="properties")
     * @param PropertyRepository $propertyRepository
     * @return Response
     */
    public function index (PropertyRepository $propertyRepository) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $properties = $propertyRepository->findAllVisible();
        return new Response($this->render('property/index.html.twig', [
            'current_menu' => 'properties',
                'properties' => $properties
            ]
        ));
    }

    /**
     * @Route("/biens/{slug}-{id}", name="show", requirements={"slug": "[a-z0-9\-]*"})
     * @param PropertyRepository $propertyRepository
     * @param Property $property
     * @param string $slug
     * @param $id
     * @return Response
     */
    public function show(PropertyRepository $propertyRepository, Property $property, string $slug, $id): Response
    {
        if($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('show',[
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
        $em = $this->getDoctrine()->getManager();
        $property = $propertyRepository->find($id);
        return $this->render('property/show.html.twig', [
            'property'=>$property,
            'current_menu' => 'properties'
        ]);
    }
}
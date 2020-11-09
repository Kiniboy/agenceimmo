<?php
namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearcher;
use App\Form\PropertySearcherType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;

class PropertyController extends AbstractController
{


    /**
     * @Route("/biens", name="properties")
     * @param PropertyRepository $propertyRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index (PropertyRepository $propertyRepository, PaginatorInterface $paginator, Request $request) : Response
    {
        // Gerer le traitement du formulaire de filtrage

        $search = new PropertySearcher();
        $form = $this->createForm(PropertySearcherType::class, $search);
        $form->handleRequest($request);

        //////////

        $em = $this->getDoctrine()->getManager();

        // Gérer la pagination

        $properties = $paginator->paginate(
            $properties = $propertyRepository->findAllVisibleQuery($search),
            $request->query->getInt('page',1),12
        );
        ////////



        return new Response($this->render('property/index.html.twig', [
            'current_menu' => 'properties',
                'properties' => $properties,
                'form' => $form->createView()
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
        // Mise en place des slugs

        if($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('show',[
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }

        /////////////////////////////
        ///
        $em = $this->getDoctrine()->getManager();
        $property = $propertyRepository->find($id);
        return $this->render('property/show.html.twig', [
            'property'=>$property,
            'current_menu' => 'properties'
        ]);
    }
}
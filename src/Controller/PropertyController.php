<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearcher;
use App\Form\ContactType;
use App\Notification\ContactNotification;
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
        $form = $this->createForm(ContactType::class, $search);
        $form->handleRequest($request);


        $em = $this->getDoctrine()->getManager();

        // Gérer la pagination

        $properties = $paginator->paginate(
            $properties = $propertyRepository->findAllVisibleQuery($search),
            $request->query->getInt('page',1),12
        );

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
     * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
    public function show(PropertyRepository $propertyRepository, Property $property, string $slug, $id, Request $request, ContactNotification $notification): Response
    {
        // Mise en place des slugs

        if($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('show',[
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }

        ///////// Import Contact et Form de Contact //////////////

        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('show', [
               'id' => $property->getId(),
               'slug' => $property->getSlug()
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $property = $propertyRepository->find($id);
        return $this->render('property/show.html.twig', [
            'property'=>$property,
            'current_menu' => 'properties',
            'form' => $form->createView()
        ]);
    }
}
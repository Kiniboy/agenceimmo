<?php
namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class AdminPropertiesController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @param PropertyRepository $propertyRepository
     * @return Response
     */
    public function index(PropertyRepository $propertyRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $this->getDoctrine()->getManager();
        $properties = $propertyRepository->findAll();
        return $this->render('admin/properties/index.html.twig', compact('properties'));
    }


    /**
     * @Route ("/admin/property/new", name="admin_property_new")
     * @param PropertyRepository $propertyRepository
     * @param Request $request
     * @return Response
     */
    public function new(PropertyRepository $propertyRepository, Request $request, PropertyType $form)
    {
        $em = $this->getDoctrine()->getManager();
        $property = new Property();

        $form = $this->createForm(PropertyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($property);
            $em->flush();
            return new Response($this->redirectToRoute('admin'));

        }
        return $this->render('admin/properties/new.html.twig',[

        ]);




    }


    /**
     * @Route("/admin/property/{id}", name="admin_property_edit")
     * @param PropertyRepository $propertyRepository
     * @param Property $property
     * @param int $id
     * @param PropertyType $form
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(PropertyRepository $propertyRepository, Property $property, int $id, PropertyType $form, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $property = $propertyRepository->find($id);

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return new Response($this->redirectToRoute('admin'));

        }

        return $this->render('admin/properties/edit.html.twig',[
            'property'=> $property,
            'form' => $form->createView()
        ]);
    }

}
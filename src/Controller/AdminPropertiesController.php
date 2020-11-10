<?php
namespace App\Controller;

use App\Entity\Option;
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

    //////// CREER UN BIEN //////////

    /**
     * @Route ("/admin/property/new", name="admin_property_new")
     * @param PropertyRepository $propertyRepository
     * @param Request $request
     * @param PropertyType $form
     * @return Response
     */
    public function new(PropertyRepository $propertyRepository, Request $request, PropertyType $form)
    {
        $em = $this->getDoctrine()->getManager();
        $property = new Property();

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($property);
            $em->flush();
            $this->addFlash('success','Bien créé avec succès !');
            return new Response($this->redirectToRoute('admin'));

        }
        return $this->render('admin/properties/new.html.twig',[
            'property'=> $property,
            'form' => $form->createView()
        ]);
    }

    //////// EDITER UN BIEN //////////


    /**
     * @Route("/admin/property/{id}", name="admin_property_edit", methods={"GET", "POST"})
     * @param PropertyRepository $propertyRepository
     * @param Property $property
     * @param int $id
     * @param PropertyType $form
     * @param Request $request
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
            $this->addFlash('success','Bien modifié avec succès !');
            return new Response($this->redirectToRoute('admin'));

        }

        return $this->render('admin/properties/edit.html.twig',[
            'property'=> $property,
            'form' => $form->createView()
        ]);
    }

    //////// SUPPRIMER UN BIEN //////////

    /**
     * @Route("/admin/property/{id}", name="admin_property_delete", methods={"DELETE"})
     * @param PropertyRepository $propertyRepository
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function delete (PropertyRepository $propertyRepository, Property $property, Request $request): Response
    {

        if($this->isCsrfTokenValid('delete', $request->get('_token')))
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($property);
            $em->flush();
            $this->addFlash('success','Bien supprimé avec succès !');
            return $this->redirectToRoute('admin');
        }
    }

}
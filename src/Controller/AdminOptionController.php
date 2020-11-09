<?php

namespace App\Controller;

use App\Entity\Option;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/option")
 */
class AdminOptionController extends AbstractController
{
    /**
     * @Route("/", name="admin_option_index", methods={"GET"})
     * @param OptionRepository $optionRepository
     * @return Response
     */
    public function index(OptionRepository $optionRepository): Response
    {
        return $this->render('admin/option/index.html.twig', [
            'options' => $optionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_option_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $option = new Option();
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($option);
            $entityManager->flush();
            $this->addFlash('success','Option créée avec succès !');

            return $this->redirectToRoute('admin_option_index');
        }

        return $this->render('admin/option/new.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="option_show", methods={"GET"})
//     */
//    public function show(Option $option): Response
//    {
//        return $this->render('admin/option/show.html.twig', [
//            'option' => $option,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="admin_option_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Option $option
     * @return Response
     */
    public function edit(Request $request, Option $option): Response
    {
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Option modifiée avec succès !');

            return $this->redirectToRoute('admin_option_index');
        }

        return $this->render('admin/option/edit.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_option_delete", methods={"DELETE"})
     * @param OptionRepository $optionRepository
     * @param Request $request
     * @param Option $option
     * @return Response
     */
    public function delete(OptionRepository $optionRepository, Request $request, Option $option): Response
    {
        if ($this->isCsrfTokenValid('delete'.$option->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($option);
            $entityManager->flush();
        }
        $this->addFlash('success','Option supprimée avec succès !');

        return $this->redirectToRoute('admin_option_index');
    }
}

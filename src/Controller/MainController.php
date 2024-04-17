<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudTypType;
use App\Repository\CrudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    // The index() method displays the main page of the application
    #[Route('/', name: 'app_main')]
    public function index(CrudRepository $repo ): Response
    {
        $datas = $repo ->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'datas' => $datas
        ]);
    }

    // The create() method handles the creation of a new Crud entity
    #[Route('/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $entityManager ): Response
    {
        // Instantiate a new Crud entity
        $crud = new Crud();
        // Create a form for the Crud entity using the CrudTypType form class
        $form = $this->createForm(CrudTypType::class,$crud);
        // Handle the request for the form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid()){
            $entityManager->persist($crud);
            $entityManager->flush();

            $this->addFlash('notice','Soumission réussi');

            // Redirect the user to the main page
            return $this->redirectToRoute('app_main');
            

        }


        // Render the createForm.html.twig template with the form and controller name
        return $this->render('main/createForm.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView()
            
        ]);
    }


    #[Route('/update/{id}', name: 'app_update')]
    public function update($id, Request $request, EntityManagerInterface $entityManager ): Response
    {
        // Create a form for the Crud entity using the CrudTypType form class
        $crudRepository =$entityManager ->getRepository(Crud::class);
        $crud= $crudRepository->find($id);
        $form = $this->createForm(CrudTypType::class,$crud);
        // Handle the request for the form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid()){
            $entityManager->persist($crud);
            $entityManager->flush();

            $this->addFlash('notice','Modification réussi');

            // Redirect the user to the main page
            return $this->redirectToRoute('app_main');
            

        }
        // Render the createForm.html.twig template with the form and controller name
        return $this->render('main/updateForm.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView()
            
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id, EntityManagerInterface $entityManager ): Response
    {
        // Create a form for the Crud entity using the CrudTypType form class
        $crudRepository =$entityManager ->getRepository(Crud::class);
        $crud= $crudRepository->find($id);
       
        // Handle the request for the form
      
            $entityManager->remove($crud);
            $entityManager->flush();

            $this->addFlash('notice','Suppretion  réussi');

            // Redirect the user to the main page
            return $this->redirectToRoute('app_main');
            

        
    
    }
}

<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Form\UserFormType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * PRODUCT
     */


    /**
     * @Route("/admin-produit", name="admin_produit")
     */
    public function index(CategoryRepository $repo, ProductRepository $products): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $products->findAll(),
            'categories' => $repo->findAllCategories()
        ]);
    }

    /**
     * @Route("/admin-new", name="admin_edit")
     */
    public function new(Request $request, ProductRepository $repo, CategoryRepository $cat): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('admin_produit');
        }

        
        return $this->render('admin/product/edit.html.twig', [
            'categories' => $cat->findAllCategories(),
            'formProduct' => $form->createView(),
            'products' => $product
        ]);
    }
    /**
     * @Route("/admin/{id}/modify", name="admin_modify")
     */
    public function edit(CategoryRepository $repo, Request $request, Product $product, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('admin_produit');
        }

        // $products = $products_list->findAll();
        return $this->render('admin/product/modify.html.twig', [
            'categories' => $repo->findAllCategories(),
            'categories_products' => $repo->findAllCategoriesWithProducts(),
            'formProduct' => $form->createView(),
            // 'products' => $products
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function delete(Product $product, EntityManagerInterface $em): Response
    {
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('admin_produit'); 
    }


    /**
     * USER
     */


    /**
     * @Route("/admin-user", name="admin_user")
     */
    public function indexUser(CategoryRepository $repo, UserRepository $user): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $user->findAll(),
            'categories' => $repo->findAllCategories()
        ]);
    }

    /**
     * @Route("/admin/{id}/modify-user", name="admin_modify_user")
     */
    public function editUser(UserRepository $repo, CategoryRepository $cat, Request $request, EntityManagerInterface $em, User $user): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/modify.html.twig', [
            'categories' => $cat->findAllCategories(),
            'formUser' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete-user/{id}", name="admin_delete_user")
     */
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_user'); 
    }
    
}
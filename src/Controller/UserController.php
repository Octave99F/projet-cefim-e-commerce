<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\GeneratePdfService;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user")
     */
    public function index( CategoryRepository $repo): Response
    {
        return $this->render('user/index.html.twig', [

            'categories' => $repo->findAllCategories(),
            'categories_products' => $repo->findAllCategoriesWithProducts(),
        ]);
    }

    /**
     * @Route("/category/{url}", name="user_categ")
     */
    public function categ(CategoryRepository $repo, $url): Response
    {
        return $this->render('user/category.html.twig', [

            'categories' => $repo->findAllCategories(),
            'category' => $repo->findCategoryByUrl($url),
            'categories_products' => $repo->findAllCategoriesWithProducts(),
        ]);
    }

    /**
     * @Route("/product/{url}", name="user_product")
     */
    public function product(ProductRepository $repo, $url, CategoryRepository $cat): Response
    {
        return $this->render('user/product.html.twig', [

            'categories' => $cat->findAllCategories(),
            'product' => $repo->findProductByUrl($url),
        ]);
    }

    /**
     * @Route("/product/telecharger/{url}", name="user_telecharger")
     */
    function download(GeneratePdfService $pdfService, ProductRepository $repo, $url, Product $product){

        // convertir image en Base64
        $img_path = 'https://anto182.go.zd.fr/public/image/produit/' . $product->getImg();

        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $data = file_get_contents($img_path, false);
        $img_base_64 = base64_encode($data);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $product->setImg($path_img);
        
        return $pdfService->renderPDF("fiche_produit.pdf","pdf/product.html.twig",[
            
            'product' => $repo->findProductByUrl($url),
        ]);
    }
}


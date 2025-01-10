<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/api/categories-data', name: 'categories_data', methods: ['GET'])]
    public function getCategoriesData(CategoryRepository $categoryRepository): JsonResponse
    {
        $categoriesData = $categoryRepository->countBooksReadByCategory();

        $data = [
            'categories' => array_column($categoriesData, 'category'),
            'series' => array_column($categoriesData, 'bookReadCount'),
        ];

        return $this->json($data);
    }
}

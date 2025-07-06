<?php

namespace App\Controller;

use App\Document\YourDocument; // À remplacer par un vrai document si besoin
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestMongoController extends AbstractController
{
    #[Route('/mongo-test', name: 'mongo_test')]
    public function testMongo(DocumentManager $dm): JsonResponse
    {
        try {
            $collectionsIterator = $dm->getDocumentDatabase(YourDocument::class)->listCollections();

            // Convertir l’itérateur en tableau
            $collections = iterator_to_array($collectionsIterator);

            // Extraire uniquement les noms des collections
            $collectionNames = array_map(fn($col) => $col->getName(), $collections);

            return $this->json([
                'success' => true,
                'collections' => $collectionNames,
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

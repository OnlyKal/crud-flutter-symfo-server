<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/api', name: '_api')]
class ClientController extends AbstractController
{

    #[Route('/client/get', name: 'get_client', methods: ['GET'])]
    public function getClient(ManagerRegistry $doctrine): JsonResponse
    {
        $clients = $doctrine
            ->getRepository(Client::class)
            ->findAll();


        $data = [];
        foreach ($clients as $client) {
            $data[] = [
                'id' => $client->getId(),
                'name' => $client->getName(),
                'email' => $client->getEmail(),
                'phone' => $client->getPhone(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/client/add', name: 'add_client', methods: ['POST'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $client = new Client();
        $client->setName($request->request->get('name'));
        $client->setEmail($request->request->get('email'));
        $client->setPhone($request->request->get('phone'));

        $entityManager->persist($client);
        $entityManager->flush();

        $data =  [
            'id' => $client->getId(),
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),

        ];

        return $this->json($data);
    }


    #[Route('/client/delete/{id}', name: 'client_delete', methods: ['delete'])]
    public function deleteClient(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $client = $entityManager->getRepository(Client::class)->find($id);

        if (!$client) {
            return $this->json('Aucun client trouve pour id' . $id, 404);
        }

        $entityManager->remove($client);
        $entityManager->flush();

        return $this->json('Client supprime pour id ' . $id);
    }

    #[Route("/client/getby/{id}", name: 'client_getby', methods: ['GET'])]
    public function getClientById(ManagerRegistry $doctrine, Request $request, int  $id): JsonResponse
    {
        $client = $doctrine->getRepository(Client::class)->find($id);
        if (!$client) {

            return $this->json('Aucun client trouvé pour id ' . $id, 404);
        }
        $data =  [
            'id' => $client->getId(),
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),

        ];

        return $this->json($data);
    }
    #[Route("/client/update/{id}", name: 'client_update', methods: ['PUT','PATCH','POST'])]
    public function updateClient(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $client = $entityManager->getRepository(Client::class)->find($id);

        if (!$client) {
            return $this->json('No client found for id ' . $id, 404);
        }

       
        // echo($request->request);

        $client->setName($request->request->get('name'));
        $client->setEmail($request->request->get('email'));
        $client->setPhone($request->request->get('phone'));

        $entityManager->flush();

        $data = [
            'id' => $client->getId(),
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),
        ];

        return $this->json($data);
    }
}

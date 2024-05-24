<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_orders')]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            $orders = $orderRepository->findAll();
        } else {
            $orders = $user->getOrders();
        }

        return $this->render('oreder/list.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    #[Route('/order', name: 'app_order', methods: ['GET', 'POST'])]
    public function order(Request $request, ProductRepository $productRepository, EntityManagerInterface $manager): Response
    {
        $order = new Order();
        $products = $productRepository->findAll();

        $form = $this->createForm(OrderType::class, [
            'products' => $products
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $order->setUser($this->getUser());
            $order->setEmail($data['email']);
            $order->setProduct($data['product']);

            $manager->persist($order);
            $manager->flush();

            return $this->redirectToRoute('app_orders');
        }

        return $this->render('order/create.html.twig', [
            'order' => $form,
        ]);
    }
}

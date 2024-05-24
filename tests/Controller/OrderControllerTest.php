<?php

namespace App\Tests;

use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testOrderUnauthorized(): void
    {
        $client = static::createClient();
        $client->request('GET', '/order');
        $this->assertResponseRedirects('/login', 302);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div', 'You have to login in order to access this page.');
    }

    public function testOrderAuthorized(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@shop.local');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/order');
        $this->assertResponseIsSuccessful();
        $this->assertInputValueSame('order[email]', '');
        $this->assertEquals($crawler->filterXPath(".//select[@id='order_product']//option[@value='']")->text(), 'Select Product');
        $this->assertSelectorExists('#order_create');
    }

    public function testOrderCreate(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@shop.local');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/order');
        $submitButton = $crawler->selectButton('order[create]');
        $form = $submitButton->form();
        $form['order[email]'] = 'test@shop.local';
        $values = $form['order[product]']->availableOptionValues();
        $form['order[product]']->select($values[1]);
        $client->submit($form);
        $this->assertResponseRedirects('/orders', 302);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $orderRepository = static::getContainer()->get(OrderRepository::class);
        $order = $orderRepository->findOneByEmail('test@shop.local');
        $this->assertEquals('test@shop.local', $order->getEmail());
        $this->assertEquals('Movable property valuation.', $order->getProduct()->getName());
    }

    public function testOrderInvalidEmail(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@shop.local');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/order');
        $submitButton = $crawler->selectButton('order[create]');
        $form = $submitButton->form();
        $values = $form['order[product]']->availableOptionValues();
        $form['order[product]']->select($values[1]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(422);
        $this->assertAnySelectorTextContains('.form-error', 'Email should not be blank.');
    }

    public function testOrderInvalidProduct(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@shop.local');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/order');
        $submitButton = $crawler->selectButton('order[create]');
        $form = $submitButton->form();
        $form['order[email]'] = 'test@shop.local';
        $client->submit($form);
        $this->assertResponseStatusCodeSame(422);
        $this->assertAnySelectorTextContains('.form-error', 'Product should not be blank.');
    }
}

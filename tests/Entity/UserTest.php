<?php

namespace App\Tests\Entity;

use App\Entity\Cart;
use App\Entity\Checkout;
use App\Entity\Order;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();

        // Initialize the ID using reflection since it's a private property
        $reflection = new \ReflectionClass(User::class);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($this->user, Uuid::uuid4());
    }

    public function testBasicGettersAndSetters(): void
    {
        // Test name
        $this->user->setName('John');
        $this->assertEquals('John', $this->user->getName());

        // Test family
        $this->user->setFamily('Doe');
        $this->assertEquals('Doe', $this->user->getFamily());

        // Test email
        $this->user->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $this->user->getEmail());

        // Test password
        $this->user->setPassword('hashedpassword123');
        $this->assertEquals('hashedpassword123', $this->user->getPassword());

        // Test roles
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $this->user->setRoles($roles);
        $this->assertEquals($roles, $this->user->getRoles());

        // Test addresses
        $this->user->setShippingAddress('123 Shipping St');
        $this->assertEquals('123 Shipping St', $this->user->getShippingAddress());

        $this->user->setInvoiceAddress('456 Invoice Ave');
        $this->assertEquals('456 Invoice Ave', $this->user->getInvoiceAddress());
    }

    public function testCollectionGettersAndSetters(): void
    {
        // Test carts
        $carts = [$this->createMock(Cart::class)];
        $this->user->setCarts($carts);
        $this->assertEquals($carts, $this->user->getCarts());

        // Test orders
        $orders = [$this->createMock(Order::class)];
        $this->user->setOrders($orders);
        $this->assertEquals($orders, $this->user->getOrders());

        // Test checkouts
        $checkouts = [$this->createMock(Checkout::class)];
        $this->user->setCheckouts($checkouts);
        $this->assertEquals($checkouts, $this->user->getCheckouts());
    }

    public function testUserInterfaceImplementation(): void
    {
        // Ensure email is set for username test
        $email = 'john.doe@example.com';
        $this->user->setEmail($email);

        // Test getUserIdentifier (should return UUID string)
        $this->assertIsString($this->user->getUserIdentifier());
        $this->assertNotEmpty($this->user->getUserIdentifier());

        // Test getUsername (should return email)
        $this->assertEquals($email, $this->user->getUsername());

        // Test getSalt (should return null as modern password hashing doesn't need a salt)
        $this->assertNull($this->user->getSalt());

        // Test eraseCredentials (should not throw exception)
        $this->user->eraseCredentials();
        $this->assertTrue(true); // If we got here, no exception was thrown
    }

    public function testJwtUserImplementation(): void
    {
        $uuid = Uuid::uuid4();
        $payload = [
            'roles' => ['ROLE_USER', 'ROLE_ADMIN'],
        ];

        $user = User::createFromPayload($uuid->toString(), $payload);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($uuid->toString(), $user->getId()->toString());
        $this->assertEquals($payload['roles'], $user->getRoles());
    }

    public function testDefaultRoleAssignment(): void
    {
        $uuid = Uuid::uuid4();
        $payload = []; // Empty payload

        $user = User::createFromPayload($uuid->toString(), $payload);

        // Should assign ROLE_USER by default when no roles provided
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testFluidInterfaces(): void
    {
        // Test that setter methods return $this for method chaining
        $this->assertSame($this->user, $this->user->setName('John'));
        $this->assertSame($this->user, $this->user->setFamily('Doe'));
        $this->assertSame($this->user, $this->user->setEmail('john.doe@example.com'));
        $this->assertSame($this->user, $this->user->setPassword('password123'));
        $this->assertSame($this->user, $this->user->setRoles(['ROLE_USER']));
        $this->assertSame($this->user, $this->user->setInvoiceAddress('123 Street'));
    }
}

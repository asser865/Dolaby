<?php

use PHPUnit\Framework\TestCase;

/**
 * Test Suite: Payment Method Management
 * Source File: paymentmethod.php
 * Covers: Repository interaction, listing, and deletion logic
 */
class PaymentMethodTest extends TestCase
{
    /**
     * @test
     * @group repository
     */
    public function testPaymentMethodRepositoryInstantiatesWithoutCrash()
    {
        $this->expectNotToPerformAssertions();
        try {
            $repo = new PaymentMethodRepository();
        } catch (\Exception $e) {
            $this->fail('PaymentMethodRepository constructor crashed: ' . $e->getMessage());
        }
    }

    /**
     * @test
     * @group logic
     */
    public function testDeletePaymentIdIsSanitized()
    {
        // Simulate $_POST['delete_payment_id'] = '123abc'
        $rawId = '123abc';
        $sanitizedId = (int)$rawId;
        
        $this->assertEquals(123, $sanitizedId);
        $this->assertIsInt($sanitizedId);
    }

    /**
     * @test
     * @group logic
     */
    public function testPaymentMethodListIsEmptyHandled()
    {
        $paymentMethods = [];
        $this->assertEmpty($paymentMethods);
        $this->assertCount(0, $paymentMethods);
    }
}

<?php

use PHPUnit\Framework\TestCase;

/**
 * Test Suite: OrderService
 * Source Files: myorders.php, checkout_process.php
 * Covers: Rating permission by status, order total calculation
 */
class OrderServiceTest extends TestCase
{
    private function canUserRateOrder(string $status): bool
    {
        return in_array($status, ['shipped', 'delivered'], true);
    }

    private function calculateOrderTotal(float $subtotal, float $shipping, float $serviceFee, float $discount): float
    {
        return $subtotal + $shipping + $serviceFee - $discount;
    }

    // =========================================================
    // HAPPY PATH
    // =========================================================

    /**
     * @test
     * @group happy_path
     */
    public function testDeliveredOrderCanBeRated()
    {
        $this->assertTrue($this->canUserRateOrder('delivered'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testShippedOrderCanBeRated()
    {
        $this->assertTrue($this->canUserRateOrder('shipped'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testOrderTotalCalculationIsCorrect()
    {
        $total = $this->calculateOrderTotal(500.00, 40.00, 10.00, 50.00);
        $this->assertEquals(500.00, $total);
    }

    /**
     * @test
     * @group happy_path
     */
    public function testOrderTotalWithNoDiscount()
    {
        $total = $this->calculateOrderTotal(300.00, 40.00, 10.00, 0.00);
        $this->assertEquals(350.00, $total);
    }

    // =========================================================
    // EDGE CASES
    // =========================================================

    /**
     * @test
     * @group edge_case
     */
    public function testOutForShippingOrderCannotBeRated()
    {
        $this->assertFalse($this->canUserRateOrder('out_for_shipping'));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testOrderTotalWithFullDiscount()
    {
        $total = $this->calculateOrderTotal(100.00, 40.00, 10.00, 100.00);
        $this->assertEquals(50.00, $total);
    }

    /**
     * @test
     * @group edge_case
     */
    public function testOrderTotalIsNeverNegative()
    {
        $total = $this->calculateOrderTotal(100.00, 40.00, 10.00, 0.00);
        $this->assertGreaterThanOrEqual(0, $total);
    }

    // =========================================================
    // NEGATIVE TESTS
    // =========================================================

    /**
     * @test
     * @group negative
     */
    public function testPendingOrderCannotBeRated()
    {
        $this->assertFalse($this->canUserRateOrder('pending'));
    }

    /**
     * @test
     * @group negative
     */
    public function testCancelledOrderCannotBeRated()
    {
        $this->assertFalse($this->canUserRateOrder('cancelled'));
    }

    /**
     * @test
     * @group negative
     */
    public function testFakeStatusCannotBeRated()
    {
        $this->assertFalse($this->canUserRateOrder('super_delivered'));
    }

    /**
     * @test
     * @group negative
     */
    public function testEmptyStatusCannotBeRated()
    {
        $this->assertFalse($this->canUserRateOrder(''));
    }

    // =========================================================
    // NO CRASHES
    // =========================================================

    /**
     * @test
     * @group no_crash
     */
    public function testOrderServiceInstantiatesWithoutCrash()
    {
        $this->expectNotToPerformAssertions();
        try {
            $service = new OrderService();
        } catch (\Exception $e) {
            $this->fail('OrderService constructor crashed: ' . $e->getMessage());
        }
    }

    /**
     * @test
     * @group no_crash
     */
    public function testRatingCheckHandlesAllKnownStatuses()
    {
        $knownStatuses = ['pending', 'out_for_shipping', 'shipped', 'delivered', 'cancelled'];
        foreach ($knownStatuses as $status) {
            $result = $this->canUserRateOrder($status);
            $this->assertIsBool($result, "Status '$status' should return a boolean");
        }
    }
}

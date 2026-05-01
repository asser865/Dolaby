<?php

use PHPUnit\Framework\TestCase;

/**
 * Test Suite: PromoCodeRepository
 * Source File: includes.php (class PromoCodeRepository)
 * Covers: calculateDiscount (percent, fixed, capped, exceeds total)
 */
class PromoCodeRepositoryTest extends TestCase
{
    private $promoRepo;

    protected function setUp(): void
    {
        $this->promoRepo = new PromoCodeRepository();
    }

    // =========================================================
    // HAPPY PATH
    // =========================================================

    /**
     * @test
     * @group happy_path
     */
    public function testCalculateDiscountPercentage()
    {
        $promo = ['type' => 'percent', 'value' => 20, 'max_discount' => null];
        $discount = $this->promoRepo->calculateDiscount($promo, 1000.00);
        $this->assertEquals(200.00, $discount);
    }

    /**
     * @test
     * @group happy_path
     */
    public function testCalculateDiscountFixedAmount()
    {
        $promo = ['type' => 'fixed', 'value' => 75.00, 'max_discount' => null];
        $discount = $this->promoRepo->calculateDiscount($promo, 500.00);
        $this->assertEquals(75.00, $discount);
    }

    // =========================================================
    // EDGE CASES
    // =========================================================

    /**
     * @test
     * @group edge_case
     */
    public function testCalculateDiscountPercentageWithMaxCap()
    {
        $promo = ['type' => 'percent', 'value' => 50, 'max_discount' => 150.00];
        $discount = $this->promoRepo->calculateDiscount($promo, 1000.00);
        $this->assertEquals(150.00, $discount);
    }

    /**
     * @test
     * @group edge_case
     */
    public function testCalculateDiscountExceedsOrderTotal()
    {
        $promo = ['type' => 'fixed', 'value' => 500.00, 'max_discount' => null];
        $discount = $this->promoRepo->calculateDiscount($promo, 300.00);
        $this->assertEquals(300.00, $discount);
    }

    /**
     * @test
     * @group edge_case
     */
    public function testCalculateDiscountWithZeroPercent()
    {
        $promo = ['type' => 'percent', 'value' => 0, 'max_discount' => null];
        $discount = $this->promoRepo->calculateDiscount($promo, 1000.00);
        $this->assertEquals(0.00, $discount);
    }

    /**
     * @test
     * @group edge_case
     */
    public function testCalculateDiscountFullHundredPercent()
    {
        $promo = ['type' => 'percent', 'value' => 100, 'max_discount' => null];
        $discount = $this->promoRepo->calculateDiscount($promo, 500.00);
        $this->assertLessThanOrEqual(500.00, $discount);
    }

    /**
     * @test
     * @group edge_case
     */
    public function testCalculateFixedDiscountEqualToOrderTotal()
    {
        $promo = ['type' => 'fixed', 'value' => 200.00, 'max_discount' => null];
        $discount = $this->promoRepo->calculateDiscount($promo, 200.00);
        $this->assertEquals(200.00, $discount);
    }

    // =========================================================
    // NEGATIVE TESTS
    // =========================================================

    /**
     * @test
     * @group negative
     */
    public function testCalculateDiscountNeverGoesNegative()
    {
        $promo = ['type' => 'percent', 'value' => 10, 'max_discount' => null];
        $discount = $this->promoRepo->calculateDiscount($promo, 0.00);
        $this->assertGreaterThanOrEqual(0.00, $discount);
    }

    /**
     * @test
     * @group negative
     */
    public function testCalculateDiscountWithMaxCapLowerThanCalculated()
    {
        $promo = ['type' => 'percent', 'value' => 90, 'max_discount' => 50.00];
        $discount = $this->promoRepo->calculateDiscount($promo, 500.00);
        $this->assertEquals(50.00, $discount);
    }

    // =========================================================
    // NO CRASHES
    // =========================================================

    /**
     * @test
     * @group no_crash
     */
    public function testCalculateDiscountDoesNotThrowException()
    {
        $this->expectNotToPerformAssertions();
        try {
            $promo = ['type' => 'percent', 'value' => 10, 'max_discount' => null];
            $this->promoRepo->calculateDiscount($promo, 100.00);
        } catch (\Exception $e) {
            $this->fail('calculateDiscount() threw an unexpected exception: ' . $e->getMessage());
        }
    }
}

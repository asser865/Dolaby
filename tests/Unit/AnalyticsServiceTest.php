<?php

use PHPUnit\Framework\TestCase;

/**
 * Test Suite: AnalyticsService
 * Source File: includes.php (class AnalyticsService)
 * Covers: getTotalSales, getUniqueCustomers, getReturningCustomerRate, getCategoryDemographics, getTopProducts
 */
class AnalyticsServiceTest extends TestCase
{
    private $analyticsService;

    protected function setUp(): void
    {
        $this->analyticsService = new AnalyticsService();
    }

    // =========================================================
    // HAPPY PATH
    // =========================================================

    /**
     * @test
     * @group happy_path
     */
    public function testGetTotalSalesReturnsFloat()
    {
        $total = $this->analyticsService->getTotalSales();
        $this->assertIsFloat($total);
        $this->assertGreaterThanOrEqual(0, $total);
    }

    /**
     * @test
     * @group happy_path
     */
    public function testGetUniqueCustomersReturnsInteger()
    {
        $customers = $this->analyticsService->getUniqueCustomers();
        $this->assertIsInt($customers);
        $this->assertGreaterThanOrEqual(0, $customers);
    }

    /**
     * @test
     * @group happy_path
     */
    public function testGetReturningCustomerRateIsValidPercentage()
    {
        $rate = $this->analyticsService->getReturningCustomerRate();
        $this->assertIsInt($rate);
        $this->assertGreaterThanOrEqual(0, $rate);
        $this->assertLessThanOrEqual(100, $rate);
    }

    /**
     * @test
     * @group happy_path
     */
    public function testGetCategoryDemographicsHasRequiredKeys()
    {
        $demographics = $this->analyticsService->getCategoryDemographics();
        $this->assertIsArray($demographics);
        $this->assertArrayHasKey('Men', $demographics);
        $this->assertArrayHasKey('Women', $demographics);
        $this->assertArrayHasKey('Kids', $demographics);
    }

    /**
     * @test
     * @group happy_path
     */
    public function testGetTopProductsReturnsArray()
    {
        $topProducts = $this->analyticsService->getTopProducts(3);
        $this->assertIsArray($topProducts);
        $this->assertLessThanOrEqual(3, count($topProducts));
    }

    // =========================================================
    // EDGE CASES
    // =========================================================

    /**
     * @test
     * @group edge_case
     */
    public function testGetTopProductsWithLimitOfOne()
    {
        $topProducts = $this->analyticsService->getTopProducts(1);
        $this->assertIsArray($topProducts);
        $this->assertLessThanOrEqual(1, count($topProducts));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testGetTopProductsWithLargeLimit()
    {
        $topProducts = $this->analyticsService->getTopProducts(9999);
        $this->assertIsArray($topProducts);
    }

    /**
     * @test
     * @group edge_case
     */
    public function testCategoryDemographicsValuesAreNonNegative()
    {
        $demographics = $this->analyticsService->getCategoryDemographics();
        foreach ($demographics as $key => $value) {
            $this->assertGreaterThanOrEqual(0, $value, "Category '$key' should not have negative quantity");
        }
    }

    // =========================================================
    // NEGATIVE TESTS
    // =========================================================

    /**
     * @test
     * @group negative
     */
    public function testGetTopProductsWithZeroLimitReturnsEmpty()
    {
        $topProducts = $this->analyticsService->getTopProducts(0);
        $this->assertIsArray($topProducts);
        $this->assertCount(0, $topProducts);
    }

    // =========================================================
    // NO CRASHES
    // =========================================================

    /**
     * @test
     * @group no_crash
     */
    public function testGetTotalSalesDoesNotThrowException()
    {
        $this->expectNotToPerformAssertions();
        try {
            $this->analyticsService->getTotalSales();
        } catch (\Exception $e) {
            $this->fail('getTotalSales() threw an unexpected exception: ' . $e->getMessage());
        }
    }

    /**
     * @test
     * @group no_crash
     */
    public function testAllAnalyticsMethodsReturnWithoutCrashing()
    {
        $this->expectNotToPerformAssertions();
        try {
            $this->analyticsService->getTotalSales();
            $this->analyticsService->getUniqueCustomers();
            $this->analyticsService->getReturningCustomerRate();
            $this->analyticsService->getCategoryDemographics();
            $this->analyticsService->getTopProducts(5);
        } catch (\Exception $e) {
            $this->fail('An analytics method crashed: ' . $e->getMessage());
        }
    }
}

<?php

use PHPUnit\Framework\TestCase;

class AnalyticsServiceTest extends TestCase
{
    private $analyticsService;

    protected function setUp(): void
    {
        $this->analyticsService = new AnalyticsService();
    }

    public function testGetTotalSalesReturnsFloat()
    {
        $total = $this->analyticsService->getTotalSales();
        $this->assertIsFloat($total);
        $this->assertGreaterThanOrEqual(0, $total);
    }

    public function testGetUniqueCustomersReturnsInteger()
    {
        $customers = $this->analyticsService->getUniqueCustomers();
        $this->assertIsInt($customers);
        $this->assertGreaterThanOrEqual(0, $customers);
    }

    public function testGetReturningCustomerRateIsPercentage()
    {
        $rate = $this->analyticsService->getReturningCustomerRate();
        $this->assertIsInt($rate);
        $this->assertGreaterThanOrEqual(0, $rate);
        $this->assertLessThanOrEqual(100, $rate);
    }

    public function testGetCategoryDemographicsHasRequiredKeys()
    {
        $demographics = $this->analyticsService->getCategoryDemographics();
        $this->assertIsArray($demographics);
        $this->assertArrayHasKey('Men', $demographics);
        $this->assertArrayHasKey('Women', $demographics);
        $this->assertArrayHasKey('Kids', $demographics);
    }

    public function testGetTopProductsReturnsArray()
    {
        // Request top 3
        $topProducts = $this->analyticsService->getTopProducts(3);
        $this->assertIsArray($topProducts);
        // It should return at most 3 items
        $this->assertLessThanOrEqual(3, count($topProducts));
    }
}

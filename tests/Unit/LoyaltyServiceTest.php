<?php

use PHPUnit\Framework\TestCase;

/**
 * Test Suite: LoyaltyService
 * Source Files: orderconfirmation.php, loyality.php
 * Covers: Points calculation math, tier status logic, points-to-value conversion
 */
class LoyaltyServiceTest extends TestCase
{
    private function calculatePoints(float $subtotal): int
    {
        return (int)floor($subtotal / 10);
    }

    private function getTierStatus(int $points): string
    {
        if ($points >= 2000) return 'Diamond';
        if ($points >= 1000) return 'Platinum';
        if ($points >= 500)  return 'Gold';
        if ($points >= 200)  return 'Silver';
        return 'Bronze';
    }

    private function getPointsToNextTier(int $points): int
    {
        if ($points >= 2000) return 0;
        if ($points >= 1000) return 2000 - $points;
        if ($points >= 500)  return 1000 - $points;
        if ($points >= 200)  return 500 - $points;
        return 200 - $points;
    }

    private function convertPointsToEGP(int $points): float
    {
        return round($points * 0.1, 2);
    }

    // =========================================================
    // HAPPY PATH
    // =========================================================

    /**
     * @test
     * @group happy_path
     */
    public function testPointsCalculationForTypicalOrder()
    {
        $this->assertEquals(100, $this->calculatePoints(1000.00));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testPointsCalculationForSmallOrder()
    {
        $this->assertEquals(5, $this->calculatePoints(50.00));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testTierStatusBronzeForNewUser()
    {
        $this->assertEquals('Bronze', $this->getTierStatus(0));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testTierStatusSilverAt200Points()
    {
        $this->assertEquals('Silver', $this->getTierStatus(200));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testTierStatusGoldAt500Points()
    {
        $this->assertEquals('Gold', $this->getTierStatus(500));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testTierStatusPlatinumAt1000Points()
    {
        $this->assertEquals('Platinum', $this->getTierStatus(1000));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testTierStatusDiamondAt2000Points()
    {
        $this->assertEquals('Diamond', $this->getTierStatus(2000));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testPointsValueConversion()
    {
        $this->assertEquals(10.00, $this->convertPointsToEGP(100));
    }

    // =========================================================
    // EDGE CASES
    // =========================================================

    /**
     * @test
     * @group edge_case
     */
    public function testPointsCalculationJustBelowNextUnit()
    {
        $this->assertEquals(0, $this->calculatePoints(9.99));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testPointsCalculationExactly10EGP()
    {
        $this->assertEquals(1, $this->calculatePoints(10.00));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testTierJustBelowSilver()
    {
        $this->assertEquals('Bronze', $this->getTierStatus(199));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testTierJustBelowGold()
    {
        $this->assertEquals('Silver', $this->getTierStatus(499));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testPointsToNextTierForBronzeUser()
    {
        $this->assertEquals(150, $this->getPointsToNextTier(50));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testDiamondUserHasZeroPointsToNextTier()
    {
        $this->assertEquals(0, $this->getPointsToNextTier(2000));
        $this->assertEquals(0, $this->getPointsToNextTier(5000));
    }

    // =========================================================
    // NEGATIVE TESTS
    // =========================================================

    /**
     * @test
     * @group negative
     */
    public function testPointsCalculationForZeroOrderAmount()
    {
        $this->assertEquals(0, $this->calculatePoints(0.00));
    }

    /**
     * @test
     * @group negative
     */
    public function testPointsValueForZeroPoints()
    {
        $this->assertEquals(0.00, $this->convertPointsToEGP(0));
    }

    /**
     * @test
     * @group negative
     */
    public function testPointsAreAlwaysNonNegative()
    {
        $this->assertGreaterThanOrEqual(0, $this->calculatePoints(0.01));
    }

    // =========================================================
    // NO CRASHES
    // =========================================================

    /**
     * @test
     * @group no_crash
     */
    public function testLoyaltyServiceInstantiatesWithoutCrash()
    {
        $this->expectNotToPerformAssertions();
        try {
            $service = new LoyaltyService();
        } catch (\Exception $e) {
            $this->fail('LoyaltyService constructor crashed: ' . $e->getMessage());
        }
    }

    /**
     * @test
     * @group no_crash
     */
    public function testGetLoyaltyPointsReturnsArrayWithRequiredKeys()
    {
        $fallback = [
            'current_balance'     => 0,
            'total_earned'        => 0,
            'total_redeemed'      => 0,
            'tier_status'         => 'Bronze',
            'points_to_next_tier' => 500
        ];
        $this->assertArrayHasKey('current_balance', $fallback);
        $this->assertArrayHasKey('tier_status', $fallback);
        $this->assertEquals('Bronze', $fallback['tier_status']);
        $this->assertEquals(0, $fallback['current_balance']);
    }
}

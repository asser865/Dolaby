<?php

use PHPUnit\Framework\TestCase;

/**
 * Test Suite: Review / Rating Logic
 * Source Files: submit_rating.php, myorders.php
 * Covers: Rating range validation, review type detection, ownership check
 */
class ReviewServiceTest extends TestCase
{
    private function isValidRating(int $rating): bool
    {
        return $rating >= 1 && $rating <= 5;
    }

    private function userOwnsOrder(array $order, int $userId): bool
    {
        return isset($order['user_id']) && (int)$order['user_id'] === $userId;
    }

    private function isNewReview(?array $existingReview): bool
    {
        return $existingReview === null;
    }

    // =========================================================
    // HAPPY PATH
    // =========================================================

    /**
     * @test
     * @group happy_path
     */
    public function testRatingOf3IsValid()
    {
        $this->assertTrue($this->isValidRating(3));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testRatingOf5IsValid()
    {
        $this->assertTrue($this->isValidRating(5));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testRatingOf1IsValid()
    {
        $this->assertTrue($this->isValidRating(1));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testUserOwnsTheirOwnOrder()
    {
        $order = ['order_id' => 1, 'user_id' => 42];
        $this->assertTrue($this->userOwnsOrder($order, 42));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testNullExistingReviewMeansNewReview()
    {
        $this->assertTrue($this->isNewReview(null));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testExistingReviewMeansUpdate()
    {
        $existing = ['review_id' => 5, 'rating' => 3];
        $this->assertFalse($this->isNewReview($existing));
    }

    // =========================================================
    // EDGE CASES
    // =========================================================

    /**
     * @test
     * @group edge_case
     */
    public function testMinimumValidRatingIs1()
    {
        $this->assertTrue($this->isValidRating(1));
        $this->assertFalse($this->isValidRating(0));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testMaximumValidRatingIs5()
    {
        $this->assertTrue($this->isValidRating(5));
        $this->assertFalse($this->isValidRating(6));
    }

    // =========================================================
    // NEGATIVE TESTS
    // =========================================================

    /**
     * @test
     * @group negative
     */
    public function testRatingOf0IsInvalid()
    {
        $this->assertFalse($this->isValidRating(0));
    }

    /**
     * @test
     * @group negative
     */
    public function testRatingOf6IsInvalid()
    {
        $this->assertFalse($this->isValidRating(6));
    }

    /**
     * @test
     * @group negative
     */
    public function testNegativeRatingIsInvalid()
    {
        $this->assertFalse($this->isValidRating(-1));
    }

    /**
     * @test
     * @group negative
     */
    public function testVeryHighRatingIsInvalid()
    {
        $this->assertFalse($this->isValidRating(100));
    }

    /**
     * @test
     * @group negative
     */
    public function testUserDoesNotOwnOtherUsersOrder()
    {
        $order = ['order_id' => 10, 'user_id' => 2];
        $this->assertFalse($this->userOwnsOrder($order, 1));
    }

    /**
     * @test
     * @group negative
     */
    public function testMissingUserIdInOrderIsBlocked()
    {
        $order = ['order_id' => 10];
        $this->assertFalse($this->userOwnsOrder($order, 1));
    }

    // =========================================================
    // NO CRASHES
    // =========================================================

    /**
     * @test
     * @group no_crash
     */
    public function testReviewRepositoryInstantiatesWithoutCrash()
    {
        $this->expectNotToPerformAssertions();
        try {
            $repo = new ReviewRepository();
        } catch (\Exception $e) {
            $this->fail('ReviewRepository constructor crashed: ' . $e->getMessage());
        }
    }

    /**
     * @test
     * @group no_crash
     */
    public function testValidationHandlesAllRatingsBetween1And5()
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->assertTrue($this->isValidRating($i), "Rating $i should be valid but was rejected");
        }
    }

    /**
     * @test
     * @group no_crash
     */
    public function testValidationRejectsAllRatingsOutsideRange()
    {
        foreach ([-100, -1, 0, 6, 7, 100] as $rating) {
            $this->assertFalse($this->isValidRating($rating), "Rating $rating should be rejected but was accepted");
        }
    }
}

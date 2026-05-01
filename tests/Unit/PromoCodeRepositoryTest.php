<?php

use PHPUnit\Framework\TestCase;

class PromoCodeRepositoryTest extends TestCase
{
    private $promoRepo;

    protected function setUp(): void
    {
        // This will connect to the database since the constructor does it
        $this->promoRepo = new PromoCodeRepository();
    }

    public function testCalculateDiscountPercentage()
    {
        $promo = [
            'type' => 'percent',
            'value' => 20, // 20%
            'max_discount' => null
        ];
        
        $orderAmount = 1000.00;
        
        // 20% of 1000 is 200
        $discount = $this->promoRepo->calculateDiscount($promo, $orderAmount);
        
        $this->assertEquals(200.00, $discount);
    }

    public function testCalculateDiscountPercentageWithMaxCap()
    {
        $promo = [
            'type' => 'percent',
            'value' => 50, // 50%
            'max_discount' => 150.00 // Cap at 150
        ];
        
        $orderAmount = 1000.00;
        
        // 50% of 1000 is 500, but capped at 150
        $discount = $this->promoRepo->calculateDiscount($promo, $orderAmount);
        
        $this->assertEquals(150.00, $discount);
    }

    public function testCalculateDiscountFixedAmount()
    {
        $promo = [
            'type' => 'fixed',
            'value' => 75.00,
            'max_discount' => null
        ];
        
        $orderAmount = 500.00;
        
        // Fixed 75
        $discount = $this->promoRepo->calculateDiscount($promo, $orderAmount);
        
        $this->assertEquals(75.00, $discount);
    }

    public function testCalculateDiscountExceedsOrderTotal()
    {
        $promo = [
            'type' => 'fixed',
            'value' => 500.00, // 500 fixed discount
            'max_discount' => null
        ];
        
        $orderAmount = 300.00; // Order is only 300
        
        // Discount should never exceed the order amount
        $discount = $this->promoRepo->calculateDiscount($promo, $orderAmount);
        
        $this->assertEquals(300.00, $discount);
    }
}

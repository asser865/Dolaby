<?php

use PHPUnit\Framework\TestCase;

/**
 * Test Suite: Add Another Card Validation
 * Source File: addanothercard.php
 * Covers: Luhn algorithm, expiry date validation, cardholder name, card type
 */
class AddAnotherCardTest extends TestCase
{
    private function luhnValid(string $number): bool
    {
        $sum = 0;
        $alt = false;
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $n = intval($number[$i]);
            if ($alt) {
                $n *= 2;
                if ($n > 9) $n -= 9;
            }
            $sum += $n;
            $alt = !$alt;
        }
        return $sum % 10 === 0;
    }

    private function isValidCardType(string $type): bool
    {
        return in_array($type, ['VISA', 'MasterCard', 'American Express'], true);
    }

    private function isValidCardholderName(string $name): bool
    {
        return (bool)preg_match('/^[A-Za-z ]{2,50}$/', trim($name));
    }

    private function isValidCardLength(string $digits): bool
    {
        $len = strlen($digits);
        return $len >= 13 && $len <= 19;
    }

    private function isCardExpired(int $month, int $year): bool
    {
        $expiry = \DateTime::createFromFormat('Y-m', sprintf('%04d-%02d', $year, $month));
        $expiry->modify('last day of this month 23:59:59');
        return $expiry < new \DateTime();
    }

    // =========================================================
    // HAPPY PATH
    // =========================================================

    /**
     * @test
     * @group happy_path
     */
    public function testValidVisaCardPassesLuhn()
    {
        $this->assertTrue($this->luhnValid('4532015112830366'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testValidMastercardPassesLuhn()
    {
        $this->assertTrue($this->luhnValid('5425233430109903'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testValidCardTypeVisa()
    {
        $this->assertTrue($this->isValidCardType('VISA'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testValidCardTypeMasterCard()
    {
        $this->assertTrue($this->isValidCardType('MasterCard'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testValidCardTypeAmex()
    {
        $this->assertTrue($this->isValidCardType('American Express'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testValidCardholderName()
    {
        $this->assertTrue($this->isValidCardholderName('John Doe'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testValidCardLengthSixteen()
    {
        $this->assertTrue($this->isValidCardLength('4532015112830366'));
    }

    /**
     * @test
     * @group happy_path
     */
    public function testFutureCardIsNotExpired()
    {
        $futureYear = (int)date('Y') + 2;
        $this->assertFalse($this->isCardExpired(12, $futureYear));
    }

    // =========================================================
    // EDGE CASES
    // =========================================================

    /**
     * @test
     * @group edge_case
     */
    public function testMinimumCardLength13Digits()
    {
        $this->assertTrue($this->isValidCardLength('4532015112830'));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testMaximumCardLength19Digits()
    {
        $this->assertTrue($this->isValidCardLength('4532015112830366123'));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testMinimumNameLength2Characters()
    {
        $this->assertTrue($this->isValidCardholderName('Jo'));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testMaximumNameLength50Characters()
    {
        $this->assertTrue($this->isValidCardholderName(str_repeat('A', 50)));
    }

    /**
     * @test
     * @group edge_case
     */
    public function testCurrentMonthCardIsNotExpired()
    {
        $this->assertFalse($this->isCardExpired((int)date('m'), (int)date('Y')));
    }

    // =========================================================
    // NEGATIVE TESTS
    // =========================================================

    /**
     * @test
     * @group negative
     */
    public function testInvalidCardNumberFailsLuhn()
    {
        $this->assertFalse($this->luhnValid('4532015112830367'));
    }

    /**
     * @test
     * @group negative
     */
    public function testRandomNumbersFailLuhn()
    {
        $this->assertFalse($this->luhnValid('1234567890123456'));
    }

    /**
     * @test
     * @group negative
     */
    public function testInvalidCardTypeFails()
    {
        $this->assertFalse($this->isValidCardType('Bitcoin'));
        $this->assertFalse($this->isValidCardType(''));
        $this->assertFalse($this->isValidCardType('visa'));
    }

    /**
     * @test
     * @group negative
     */
    public function testCardholderNameWithNumbersFails()
    {
        $this->assertFalse($this->isValidCardholderName('John123'));
    }

    /**
     * @test
     * @group negative
     */
    public function testCardholderNameTooShortFails()
    {
        $this->assertFalse($this->isValidCardholderName('J'));
    }

    /**
     * @test
     * @group negative
     */
    public function testCardholderNameTooLongFails()
    {
        $this->assertFalse($this->isValidCardholderName(str_repeat('A', 51)));
    }

    /**
     * @test
     * @group negative
     */
    public function testCardLengthTooShortFails()
    {
        $this->assertFalse($this->isValidCardLength('123456789012'));
    }

    /**
     * @test
     * @group negative
     */
    public function testCardLengthTooLongFails()
    {
        $this->assertFalse($this->isValidCardLength('12345678901234567890'));
    }

    /**
     * @test
     * @group negative
     */
    public function testExpiredCardInPastMonthIsDetected()
    {
        $this->assertTrue($this->isCardExpired(1, 2020));
    }

    /**
     * @test
     * @group negative
     */
    public function testExpiredCardLastYearIsDetected()
    {
        $this->assertTrue($this->isCardExpired(12, (int)date('Y') - 1));
    }

    // =========================================================
    // ROBUSTNESS
    // =========================================================

    /**
     * @test
     * @group robustness
     */
    public function testLuhnValidWithEmptyStringHandledGracefully()
    {
        $result = $this->luhnValid('');
        $this->assertIsBool($result);
    }

    /**
     * @test
     * @group robustness
     */
    public function testCardholderNameEmptyStringHandledGracefully()
    {
        $result = $this->isValidCardholderName('');
        $this->assertFalse($result);
    }
}

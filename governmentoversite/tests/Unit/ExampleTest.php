<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Parsing\TokenBase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     */
    public function test_public_Date_token(): void
    {
        $token = TokenBase::classFactory("DATE", "2023-02-01");
        $testValue = $token->Output();

        $tok = new \App\Parsing\QuoteToken();
        
        $this->assertEquals($testValue, "App\Parsing\DateToken : 2023-02-01 : Line # ");
    }
}

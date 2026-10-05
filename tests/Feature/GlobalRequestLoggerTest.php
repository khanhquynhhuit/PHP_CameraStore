<?php

namespace Tests\Feature;

use App\Support\DataMasker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class GlobalRequestLoggerTest extends TestCase
{
    /** @test */
    public function it_masks_exact_sensitive_fields_in_array()
    {
        $input = [
            'name'                  => 'Nguyen Van A',
            'email'                 => 'nguyenvana@example.com',
            'password'              => 'SuperSecret123!',
            'password_confirmation' => 'SuperSecret123!',
            'token'                 => 'secret_api_token_abc',
            'cvv'                   => '123',
            'pin'                   => '9999',
            'ssn'                   => '0123456789',
        ];

        $masked = DataMasker::mask($input);

        $this->assertEquals('Nguyen Van A', $masked['name']);
        $this->assertEquals('nguyenvana@example.com', $masked['email']);
        $this->assertEquals(DataMasker::MASK, $masked['password']);
        $this->assertEquals(DataMasker::MASK, $masked['password_confirmation']);
        $this->assertEquals(DataMasker::MASK, $masked['token']);
        $this->assertEquals(DataMasker::MASK, $masked['cvv']);
        $this->assertEquals(DataMasker::MASK, $masked['pin']);
        $this->assertEquals(DataMasker::MASK, $masked['ssn']);
    }

    /** @test */
    public function it_masks_globally_and_dynamically_any_unknown_sensitive_keys()
    {
        $input = [
            'user_password_hash'   => '$2y$10$abcdef123456',
            'my_custom_secret_key' => 'secret_xyz',
            'payment_card_number'  => '4111222233334444',
            'device_auth_token'    => 'auth_99999',
            'user_otp_code'        => '654321',
            'product_name'         => 'Sony A7 IV',
            'product_price'        => 45000000,
        ];

        $masked = DataMasker::mask($input);

        // Unknown sensitive fields automatically masked
        $this->assertEquals(DataMasker::MASK, $masked['user_password_hash']);
        $this->assertEquals(DataMasker::MASK, $masked['my_custom_secret_key']);
        $this->assertEquals(DataMasker::MASK, $masked['payment_card_number']);
        $this->assertEquals(DataMasker::MASK, $masked['device_auth_token']);
        $this->assertEquals(DataMasker::MASK, $masked['user_otp_code']);

        // Normal fields retained
        $this->assertEquals('Sony A7 IV', $masked['product_name']);
        $this->assertEquals(45000000, $masked['product_price']);
    }

    /** @test */
    public function it_masks_deeply_nested_arrays_recursively()
    {
        $input = [
            'order' => [
                'customer' => [
                    'name' => 'Tran B',
                    'credentials' => [
                        'password' => 'secret',
                        'api_key'  => 'xyz123',
                    ],
                ],
                'items' => [
                    ['id' => 1, 'price' => 1000],
                ],
            ],
        ];

        $masked = DataMasker::mask($input);

        $this->assertEquals('Tran B', $masked['order']['customer']['name']);
        $this->assertEquals(DataMasker::MASK, $masked['order']['customer']['credentials']['password']);
        $this->assertEquals(DataMasker::MASK, $masked['order']['customer']['credentials']['api_key']);
        $this->assertEquals(1000, $masked['order']['items'][0]['price']);
    }

    /** @test */
    public function it_masks_bearer_tokens_and_credit_cards_in_strings()
    {
        $headerValue = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.e30.t-IDcSemACt8x4iTMCda8Yhe3iZaWbvV5XKSTbuAn0M';
        $masked = DataMasker::maskStringValue($headerValue);

        $this->assertStringStartsWith('Bearer ' . DataMasker::MASK, $masked);
        $this->assertStringNotContainsString('eyJhbGciOiJIUzI1Ni', $masked);
    }

    /** @test */
    public function it_attaches_trace_headers_and_logs_request_automatically()
    {
        $response = $this->get('/');

        $this->assertTrue($response->headers->has('X-Request-ID'));
        $this->assertTrue($response->headers->has('X-Response-Time'));
    }
}

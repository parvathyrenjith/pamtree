<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndCouponTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_user_with_phone_number()
    {
        $response = $this->postJson('/api/auth/register', [
            'full_name' => 'Test User',
            'phone_number' => '91965848985'           
        ]);
        

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'data'                         
                 ]);

        $this->assertDatabaseHas('users', ['phone_number' => '91965848985']);
    }

    /** @test */
    public function it_logs_in_with_phone_number()
    {
        // Create user manually
        $user = User::create([
            'phone_number' => '91965848988'  ,
            'name' => 'Test User',
                     
        ]);

        $response = $this->postJson('/api/login', [
            'phone_number' => '91965848988'            
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token',                     
                 ]);
    }

    /** @test */
    public function it_redeems_a_valid_coupon_code()
    {
        $user = User::factory()->create();      

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/coupon/redeem', [
                             'coupon_code' => 'DISCOUNT20',
                             'coupon_value' => 20.00
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Coupon redeemed successfully']);
    }

    /** @test */
    public function it_fails_redeem_with_invalid_coupon()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/coupon/redeem', [
                             'coupon_code' => 'INVALIDCODE'
                         ]);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Invalid coupon code']);
    }
}

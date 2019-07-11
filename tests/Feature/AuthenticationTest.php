<?php

namespace Tests\Feature;



use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function it_will_register_a_user()
    {
        $response = $this->post('api/register', ['name'=>'Fake 2','email'=>'fake2@email.com', 'phone_number' => '08190876542', 'address'=>'21 Long Island', 'date_of_birth' => '1999-07-13', 'password'=>'secret']);
        $response->assertJsonStructure(['success','user']);
        $response->assertStatus(200);
    }

    /** @test **/
    public function it_will_log_a_user_in()
    {

        $response = $this
        ->post('api/login', ['email'=>'fake2@email.com','password'=>'secret']);
        $response->assertJsonStructure(['success','user', ]);
        $response->assertStatus(200);
    }

    /** @test **/
    public function it_will_not_log_in_a_non_validated_user()
    {
        $response = $this
        ->post('api/login', ['email'=>'fake2@email.com','password'=>'decret']);
        $response->assertJsonStructure(['error', ]);
        $response->assertStatus(401);
    }

    /** @test **/
    public function it_will_log_out()
    {
        $response = $this->post('api/logout', [ ]);
        $response->assertJsonStructure(['success', ]);
        $response->assertStatus(200);
    }
}

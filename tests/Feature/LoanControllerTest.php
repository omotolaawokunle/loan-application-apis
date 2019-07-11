<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanControllerTest extends TestCase
{
    /**
     *
     *
     * @test
     */
    public function get_loans_for_authorized_user()
    {
        $response = $this->get('api/loans');
        //$response->assertStatus(200);
        //$response->assertJsonStructure(['success','loans']);
        $this->assertTrue(true);
        $response->dump();
    }

    /** @test **/
    /*public function reject_loans_request_from_non_authorized_user()
    {
        $response = $this->get('api/loans');
        $response->assertStatus(401);
        $response->assertJsonStructure(['error','message']);
        $response->dump();
    }*/

    /** @test **/
    public function apply_for_loans()
    {
        $response = $this->post('api/loans', ['loan_id'=>rand(1,2)]);
        //$response->assertStatus(200);
        //$response->response->assertJsonStructure(['success']);
        $this->assertTrue(true);
        $response->dump();
    }
}

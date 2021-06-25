<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

     private $user;

     public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

   /**
    * @test
    */
   public function should_get_authuser()
   {
       $response = $this->actingAs($this->user)->json('GET', route('user'));

       $response->assertStatus(200)
           ->assertJson([
               'name' => $this->user->name,
           ]);
   }

   /**
    * @test
    */
   public function should_get_not_authuser()
   {
       $response = $this->json('GET', route('user'));

       $response->assertStatus(200);
       $this->assertEquals("", $response->content());
   }
}

<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function testABookCanBeAddedToTheLibrary()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => "Cool Book Title",
            'author' => "Victor",
        ]);

        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function testATitleIsRequired()
    {
        $response = $this->post('/books', [
            'title' => "",
            'author' => "Victor",
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function testAAuthorIsRequired()
    {
        $response = $this->post('/books', [
            'title' => "Cool Title",
            'author' => "",
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function testABookCanBeUpdated()
    {
        $this->withoutExceptionHandling();


        $this->post('/books', [
            'title' => 'Cool Title',
            'author' => 'Victor'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/' . $book->id, [
            'title' => 'New Title',
            'author' => 'New Author'
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
    }
}

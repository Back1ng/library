<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function testABookCanBeAddedToTheLibrary()
    {
        $response = $this->post('/books', [
            'title' => "Cool Book Title",
            'author' => "Victor",
        ]);

        $this->assertCount(1, Book::all());
        $book = Book::first();

        $response->assertRedirect($book->path());
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
        $this->post('/books', [
            'title' => 'Cool Title',
            'author' => 'Victor'
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New Title',
            'author' => 'New Author'
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());
    }

    public function testABookCanBeDeleted()
    {
        $this->post('/books', [
            'title' => 'Cool Title',
            'author' => 'Victor'
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect("/books");
    }
}

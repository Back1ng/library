<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function testABookCanBeAddedToTheLibrary()
    {
        $response = $this->post('/books', $this->data());

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
        $response = $this->post('/books', array_merge($this->data(), ['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function testABookCanBeUpdated()
    {
        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New Title',
            'author_id' => 'New Author'
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    public function testABookCanBeDeleted()
    {
        $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect("/books");
    }

    public function testANewAuthorIsAutomaticallyAdded()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Cool Title',
            'author_id' => 'Victor'
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    public function data()
    {
        return [
            'title' => "Cool Book Title",
            'author_id' => "Victor",
        ];
    }
}

<?php

namespace Tests\Unit;

use App\Author;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_author_id_is_recorder()
    {
        Book::create([
            'title' => 'Cool Title',
            'author_id' => 'John Doe'
        ]);

        $this->assertCount(1, Book::all());
    }
}

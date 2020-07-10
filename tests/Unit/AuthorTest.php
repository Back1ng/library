<?php

namespace Tests\Unit;

use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function testOnlyNameIsRequiredToCreateAnAuthor()
    {
        Author::firstOrCreate([
            'name' => 'John Doe'
        ]);

        $this->assertCount(1, Author::all());
    }
}

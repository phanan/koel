<?php

namespace Tests\Feature;

use App\Services\Musixmatch;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery as m;
use Tests\TestCase;

class MusixmatchTest extends TestCase
{
    use WithoutMiddleware;

    public function testSearch()
    {
        $this->withoutEvents();

        $client = m::mock(Client::class, [
            'get' => new Response(200, [], file_get_contents(__DIR__.'/../blobs/musixmatch/search.jsonp')),
        ]);
        
        $api = new Musixmatch(null, $client);
        
        $response = $api->search('foo', 'bar');
        
        $this->assertTrue(strpos($response, "*** This Lyrics are NOT for Commercial use ***") > 0);
    }
    
    public function testSearchFailure()
    {
        $this->withoutEvents();

        $client = m::mock(Client::class, [
            'get' => new Response(200, [], file_get_contents(__DIR__.'/../blobs/musixmatch/search_failure.jsonp')),
        ]);

        $api = new Musixmatch(null, $client);
        
        $response = $api->search("foo", "bar");

        $this->assertEquals($response, false);
    }
}
<?php

namespace App\Tests\Blog;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostTest extends WebTestCase
{
    public function testCreatePost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/post/new');
        $form = $crawler->selectButton('Publish')->form();
        // set some values
        $form['post[title]'] = 'test post';
        $form['post[message]'] = 'Hey there!';

        // submit the form
        $crawler = $client->submit($form);


        $crawler = $client->request('GET', '/blog');
        $this->assertGreaterThan(0, $crawler->filter('h2:contains("test post")')->count());

        $link = $crawler
            ->filter('.blog__post:contains("test post") a')
            ->eq(1)
            ->link();

        $crawler = $client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('div:contains("Hey there!")')->count());
    }
}

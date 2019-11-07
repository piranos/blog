<?php

namespace App\Tests\Blog;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogTest extends WebTestCase
{
    /**
     * Test creation of new blog posts
     */
    public function testCreatePost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/posts/new');
        $form = $crawler->selectButton('Publish')->form();
        // set some values
        $form['post[title]'] = 'test post';
        $form['post[message]'] = 'Hey there!';

        // submit the form
        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/');
        $this->assertGreaterThan(0, $crawler->filter('h2:contains("test post")')->count());

        $link = $crawler
            ->filter('.blog__post:contains("test post") a')
            ->eq(1)
            ->link();

        $crawler = $client->click($link);
        $this->assertGreaterThan(0, $crawler->filter('div:contains("Hey there!")')->count());
    }

    /**
     * test creation of post reactions
     */
    public function testCreateReaction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler
            ->filter('.blog__post a')
            ->eq(1)
            ->link();

        $crawler = $client->click($link);
        // dd($crawler);
        $form = $crawler->selectButton('Publish')->form();
        // set some values
        $form['reaction[name]'] = 'test name';
        $form['reaction[message]'] = 'Just leaving a reaction!';

        // submit the form
        $crawler = $client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('.reaction__publisher:contains("test name")')->count());
        $this->assertGreaterThan(0, $crawler->filter('.reaction__body:contains("Just leaving a reaction!")')->count());
    }
}

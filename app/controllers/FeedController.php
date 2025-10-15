<?php

class FeedController
{
    public function index()
    {
    	require_once APP_PATH . '/models/Post.php';
    	$postModel = new Post();
    	$posts = $postModel->getAll();
    	require APP_PATH . '/views/feed.php';
    }
	
}

<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function show(Topic $topic)
    {
        return view('topic.show');
    }

    public function submit(Topic $topic)
    {

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stream; // Hakikisha jina la model ni sahihi

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    // Hakikisha jina hapa ni $classis ili lilingane na Blade yako
    $streams = Stream::all(); 

    // Tumia 'classis' ndani ya compact
    return view('pages.students.create', compact('streams'));
}

    // Methods zingine zinafuata...
}
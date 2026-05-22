<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass; // Hakikisha jina la model ni sahihi
use App\Models\Stream; // Import na Stream model hapa
class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $classes = SchoolClass::all();
    $streams = Stream::all();

    return view('pages.students.create', compact('classes', 'streams'));
}

    // Methods zingine zinafuata...
}
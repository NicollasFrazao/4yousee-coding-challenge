<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('videos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->hasFile('file')) $response = ['success' => false, 'status' => 406, 'message' => 'O campo "Arquivo de Vídeo" é obrigatório!'];
        //else if (
        
        if ($request->ajax()) return response()->json($response)->status((isset($response['status'])) ? $response['status'] : 200);
        else 
        {
            if ($response['success']) return redirect()->back();
            else return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

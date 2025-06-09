<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Contracts\VideoManagerServiceInterface;

use App\Models\Video;

class VideoController extends Controller
{
    protected $videoManagerService;

    public function __construct(VideoManagerServiceInterface $videoManagerService)
    {
        $this->videoManagerService = $videoManagerService;
    }

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
        $response = [
            'success' => false, 
            'status' => 406,
        ];

        \DB::beginTransaction();

        try
        {
            $this->videoManagerService->setUploadedFile($request->file('video'));
            
            $video = Video::create([
                'uuid' => uniqid(),
                'name' => $this->videoManagerService->getOriginalName(),
                'duration' => $this->videoManagerService->getDuration(),
                'resolution' => $this->videoManagerService->getResolution(),
            ]);

            if ($this->videoManagerService->store($video)) $video->refresh();
            
            $response = [
                'success' => true,
                'status' => 200,
                'message' => 'Tudo certo! Agora estamos armazenando seu upload e iremos notificar quando estiver tudo em ordem.',
            ];
        }
        catch (\Exception $exception)
        {
            $response['message'] = $exception->getMessage();
        }

        if ($response['success']) \DB::commit();
        else \DB::rollback();  
        
        if ($request->ajax()) return response()->json($response)->setStatusCode((isset($response['status'])) ? $response['status'] : 200);
        else 
        {
            if ($response['success']) return redirect()->back()->with($response);
            else return redirect()->back()->withInput()->with($response);
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

<?php

namespace Modules\Events\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Events\Http\Requests\TemplateStoreRequest;
use Modules\Events\Entities\EventTemplate;

class TemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $query = $request->input("query", null);
        $data = EventTemplate::query()->where("name", "like", "%" . $query . "%")->orderBy("created_at", "asc");
        $templates = $data->paginate(10);
        return view('events::templates.index', ["templates" => $templates]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('events::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(TemplateStoreRequest $request)
    {
        try {
            $data = $request->validated();
            $user = auth()->user();
            $data['user_id'] = $user->id;
            EventTemplate::create($data);
            return redirect()->route('templates.index')->with('success', __('The event is saved as a template successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('events::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('events::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $template = $request->except(["_token", "_method"]);
            EventTemplate::find($id)->update($template);
            return redirect()->route('templates.index')->with('success', __('The template is updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('templates.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            EventTemplate::find($id)->delete();
            return redirect()->route('templates.index')->with('success', __('The template is deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('templates.index')->with('error', $e->getMessage());
        }
    }
}

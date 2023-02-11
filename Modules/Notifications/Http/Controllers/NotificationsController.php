<?php

namespace Modules\Notifications\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Notifications\Entities\Notification;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = Notification::query();
        $query = $request->input('search', null);
        if (isset($query)) $query->where("title", "like", "%" . $query . "%")->orWhere("content", "like", "%" . $query . "%");
        $data->orderBy("created_at", "asc");
        $notifications = $data->paginate(10);
        return view('notifications::index', [
            "notifications" => $notifications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('notifications::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "title" => "required|max:255",
                "content" => "required"
            ]);
            
            $data = $request->except("_token");
            Notification::create($data);

            return redirect()->route("settings.notifications.index")->with([
                "success" => "Successfully created!"
            ]);
        } catch (\Exception $e) {
            return redirect()->route("settings.notifications.index")->withErrors([
                "msg" => $e->getMessage()
            ]);
        }


    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $notification = Notification::find($id);
        return response()->json($notification);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('notifications::edit');
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
            $data = $request->except(["_token", "_method"]);
            Notification::find($id)->update($data);
            return redirect()->route("settings.notifications.index")->with("success", "Successfully updated!");
        } catch (\Exception $e) {
            return redirect()->route("settings.notifications.index")->withErros([
                "msg" => $e->getMessage()
            ]);
        }
    }

    public function update_status(Request $request, $id, $status) {
        try {
            Notification::find($id)->update(["status" => $status]);
            return response()->json([
                "status" => true,
                "msg" => "Successfully updated!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "msg" => $e->getMessage()
            ]);
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
            Notification::find($id)->delete();
            return redirect()->route("settings.notifications.index")->with("success", "Successfully deleted!");
        } catch (\Exception $e) {
            return redirect()->route("settings.notifications.index")->withErrors([
                "msg" => $e->getMessage()
            ]);
        }
    }
}

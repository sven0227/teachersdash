<?php

namespace Modules\Coupons\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Coupons\Entities\Coupon;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $coupons = Coupon::where("user_id", auth()->id())
            ->orderBy("created_at", "asc")
            ->get();
        
        return view('coupons::index', [
            "coupons" => $coupons
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('coupons::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:255",
            "code" => "required|max:255",
            "discount_amount" => "required"
        ]);
        $userId = auth()->id();
        $count = Coupon::where(["user_id" => $userId, "code" => $request->input("code")])->count();

        if ($count) {
            return redirect()->route("coupons.index")->withErrors([
                "msg" => "The coupon code is duplicated. please use another code."
            ]);
        } else {
            if ($request->has("is_unlimited")) {
                Coupon::create([
                    "user_id" => $userId,
                    "name" => $request->input("name"),
                    "code" => $request->input("code"),
                    "discount_amount" => $request->input("discount_amount"),
                    "is_unlimited" => true
                ]);
            } else {
                Coupon::create([
                    "user_id" => $userId,
                    "name" => $request->input("name"),
                    "code" => $request->input("code"),
                    "discount_amount" => $request->input("discount_amount"),
                    "expire_date" => $request->input("expire_date")
                ]);
            }
            return redirect()->route("coupons.index")->with([
                "success" => "Successfully created!"
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
        $coupon = Coupon::find($id);
        return response()->json($coupon);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('coupons::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required|max:255",
            "code" => "required|max:255",
            "discount_amount" => "required"
        ]);
        $isDuplicated = Coupon::where(["user_id" =>$id, "name" => $request->input("code")])->where("id", "<>", $id)->count();
        if ($isDuplicated) {
            return redirect()->route("coupons.index")->withErrors([
                "msg" => "The coupon code is duplicated. please use another code."
            ]);
        } else {
            $data = $request->except(["_token", "method"]);
            $data['is_unlimited'] = $data['is_unlimited'] == "1" ? true : false;
            Coupon::find($id)->update($data);

            return redirect()->route("coupons.index")->with("success", "Successfully updated!");
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
            Coupon::find($id)->delete();
            return redirect()->route("coupons.index")->with("success", "Successfully deleted!");
        } catch (\Exception $e) {
            return redirect()->route("coupons.index")->withErrors([
                "msg" => $e->getMessag()
            ]);
        }

    }
}

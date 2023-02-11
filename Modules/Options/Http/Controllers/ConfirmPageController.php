<?php

namespace Modules\Options\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ConfirmPageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('options::confirm_page.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('options::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $message = $request->input("CONFIRM_PAYMENT_MESSAGE");
            $CONFIRM_PAYMENT_CONTENT = <<<EOD
                <div class="container">
                    <h3 class="body-1 mb-3 alert alert-success">Payment Successful.</h3>
                    <form action="{{ route('options.confirm-page.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            {$message}
                        </div>
                        <h3 class="display-7 p-3 text-center text-light mb-3" style="background-color: #bbbbbb">TICKET</h3>
                        <div class="card mb-3 text-center">
                            <div class="card-body">
                                <p>%event_name%</p>
                                <p>%event_start_date%</p>
                            </div>
                        </div>
                        <div class="row row-eq-height">
                            <div class="col-md-4">
                                <div class="card text-center mb-3">
                                    <div class="card-body h-100">
                                        <h3 class="display-7">TICKET TYPE</h3>
                                        <p>%guest_ticket_name%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center mb-3">
                                    <div class="card-body h-100">
                                        <h3 class="display-7">ATTENDEE</h3>
                                        <p>%guest_fullname%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center mb-3">
                                    <div class="card-body h-100">
                                        <h3 class="display-7">TICKET PRICE</h3>
                                        <p>%guest_ticket_price%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-eq-height">
                            <div class="col-md-8 mb-3">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <h3 class="display-7 p-3 text-light mb-3" style="background-color: #bbbbbb">VENUE</h3>
                                        <p>%event_address%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4  mb-3">
                                <div class="card text-center h-100">
                                    <div class="card-body">
                                        <h3 class="display-7">ADDITIONAL GUESTS</h3>
                                        <p>%guest_subgeust_fullname%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card text-center mb-3">
                                    <div class="card-body">
                                        <h3 class="display-7 p-3 text-light" style="background-color: #bbbbbb">ORGANIZER</h3>
                                        <p>%user_company_name%</p>
                                        <p>%user_company_phone% | %user_company_email%</p>
                                        <p>%use_company_website%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-eq-height">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 text-center">
                                    <div class="card-body d-flex justify-content-center align-items-center" style="min-height: 160px;">
                                        %qr_code%
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h3 class="mb-3">Check in for this event</h3>
                                        <p>Scan this QR code at the event to check in.</p>
                                        <p class="text-danger">
                                            Please print the ticket. Do not delete this email.<br/>
                                            Preset the printed ticket or open it on your smartphone when checking in to the event.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right"><i class="fa fa-save"></i> Save</button>
                    </form>
                </div>
            EOD;
            update_option('CONFIRM_PAYMENT_CONTENT', $CONFIRM_PAYMENT_CONTENT);
            return redirect()->route("options.confirm-page.index")->with("success", "Successfully updated.");
        } catch (\Exception $e) {
            return redirect()->route("options.confirm-page.index")->withErrors([
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
        return view('options::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('options::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

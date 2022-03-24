<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Alert;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = Event::latest()->get();
        return response()->json($event, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'title' => 'required',
                'start' => 'required',
                'end' => 'required',
                'allDay' => 'required',
                'color' => 'required',
                'textColor' => 'required'
            ]);
            if ($validation->failed()){

                Alert::error('Error |', $validation->messages()->first());

                return redirect()->back();
            }
            else{

                if (empty($request->event_id)){

                    if ($request->delete){
                        Alert::success('danger', 'Create Event Please');
                        return redirect()->back();
                    }

                    Event::create($request->all());
                    Alert::success('success', 'Event Created Successfull');
                    return redirect()->back();
                }
                else{
                    if ($request->delete){
                        Event::where('id', $request->event_id)->delete();
                        Alert::success('Success', 'Event Deleted Successfull');
                        return redirect()->back();

                    }

                    Event::where('id', $request->event_id)->update([
                        'title' => $request->title,
                        'start' => $request->start,
                        'end' => $request->end,
                        'allDay' => $request->allDay,
                        'color' => $request->color,
                        'textColor' => $request->textColor,

                    ]);
                    Alert::success('Success', 'Event Updated Successfull');
                    return redirect()->back();
                }


            }

        }catch (\Exception $e){
            Alert::error("Erreur ", $e->getMessage());
            return redirect()->back();

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}

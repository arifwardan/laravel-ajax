<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

use App\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('welcome');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name ='upload/foto/' . str_slug($request->name, '-'). '.' . $file->getClientOriginalExtension();
                $request->file('photo')->move("upload/foto", $name);
            }

            if($request->photo == null){
                $name = '';
            }
            
            $table = new Contact;
            $table->name = $request->name;
            $table->email = $request->email;


            $table->photo = $name;

            $table->save();

        return response()->json([
            'success' => true,
            'message' => 'Contact Created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        return $contact;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return $contact;
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
        $table = Contact::find($id);
        $table->name = $request->name;
        $table->email = $request->email;

        if ($request->photo == $table->photo){
            $name = $table->photo;
        }elseif($request->photo == null){
            $name = $table->photo;
        }elseif($table->photo == null){
            $name = '';
        }

        if ($request->hasFile('photo')){
            $file = $request->file('photo');
            $name ='upload/foto/' . str_slug($request->name, '-'). '.' . $file->getClientOriginalExtension();
            $request->file('photo')->move("upload/foto", $name);
        }

        $table->photo = $name;
        $table->update();

        return response()->json([
            'success' => true,
            'message' => 'Contact Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        if (!$contact->photo == NULL){
            unlink(public_path($contact->photo));
        }

        Contact::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Contact Deleted'
        ]);
    }

    public function apiContact()
    {
        $contact = Contact::all();


        return Datatables::of($contact)
            ->addColumn('show_photo', function($contact){
                if ($contact->photo == NULL){
                    return 'No Image';
                }
                return '<img class="rounded-square" width="50" height="50" src="'. url($contact->photo) .'" alt="">';
            })
            ->addColumn('action', function($contact){
                return '<a onclick="showData('. $contact->id .')" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Show</a> ' .
                       '<a onclick="editForm('. $contact->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                       '<a onclick="deleteData('. $contact->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>'.
                       '<a href=""></a>';
            })
            ->rawColumns(['show_photo', 'action'])->make(true);

    }
}

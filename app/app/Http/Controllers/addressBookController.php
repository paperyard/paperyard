<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// storage/file facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use DB;


class addressBookController extends Controller
{
    // show create address book page
    public function create_index(){
    	return view('pages/address_book_create');
    }

    // make child address book page
    public function createChildPage($parentId){
        $parentAddress = DB::table('address_book')->where('ab_user_id', Auth::user()->id)->where('ab_id', $parentId)->first();
        if(count($parentAddress)>0){
            return view('pages/address_book_create_child')->with(compact('parentAddress'));
        }
        return redirect('error_404');

    }

    // save address book
    public function saveAddressBook(Request $request){
	        // set user time zone.
            date_default_timezone_set(Auth::user()->user_timezone);

	    	$save_address_book = DB::table('address_book')
	    	->insert([
	    		'ab_user_id'		=>	Auth::user()->id,
	    		'ab_shortname'		=>  $request->shortname,
	    		'ab_salutation'		=> 	$request->salutation,
	    		'ab_firstname'		=> 	$request->firstname,
	    		'ab_lastname'		=> 	$request->lastname,
	    		'ab_company'		=> 	$request->company,
	    		'ab_address_line1'	=> 	$request->addressline1,
	    		'ab_address_line2'	=> 	$request->addressline2,
	    		'ab_zipcode'		=> 	$request->zipcode,
	    		'ab_town'			=> 	$request->town,
	    		'ab_country'		=> 	$request->country,
	    		'ab_telephone'		=> 	$request->telephone,
	    		'ab_email'			=> 	$request->email,
	    		'ab_notes'			=> 	$request->notes,
	    		'created_at' 	    =>  \Carbon\Carbon::now(), # \Datetime()
  	            'updated_at' 		=>  \Carbon\Carbon::now(),  # \Datetime()
	    	]);

	    	if(count($save_address_book)>0){
	    		 session()->flash('address_book_created', 'New address book successfully created!');
	    		 return "success";
	    	}	
    }//end function

    public function addressBook(){
    	$address_books = DB::table('address_book')->where('ab_user_id', Auth::user()->id)->count();
    	return view('pages/address_book_list')->with(compact('address_books'));
    }

    // return list of users address book
    public function addressBookList(){

    	$addressBooks = DB::table('address_book')->where('ab_user_id', Auth::user()->id)->orderBy('ab_id','desc')->get();
        foreach($addressBooks as $ab){
            $ab_status  = $ab->ab_possible_recipient==1? true:false;
            $ab->select = $ab_status;
        }
    	return json_encode($addressBooks);
    }
    // update bool possible recipient
    public function updatePossibleRecipient(Request $request){
    	
    	$upd = DB::table('address_book')
    	->where('ab_user_id', Auth::user()->id)
    	->where('ab_id', $request->ab_id)
    	->update(['ab_possible_recipient'=>$request->ab_status]);

    }
    // return edit page for address book
    public function editAddressBook($ab_id){

    	$address_book = DB::table('address_book')
    	->where('ab_user_id', Auth::user()->id)
    	->where('ab_Id', $ab_id)
    	->get();

    	if(count($address_book)>0){
    	    return view('pages/address_book_edit')->with(compact('address_book'));
    	}else{
    		return redirect('error_404');
    	}
    }

    //update edited address book
    public function updateAddressBook(Request $request){

    	// set user time zone.
        date_default_timezone_set(Auth::user()->user_timezone);

    	$save_address_book = DB::table('address_book')
    	->where('ab_id', $request->ab_id)
    	->update([
    		'ab_shortname'		=>  $request->shortname,
    		'ab_salutation'		=> 	$request->salutation,
    		'ab_firstname'		=> 	$request->firstname,
    		'ab_lastname'		=> 	$request->lastname,
    		'ab_company'		=> 	$request->company,
    		'ab_address_line1'	=> 	$request->addressline1,
    		'ab_address_line2'	=> 	$request->addressline2,
    		'ab_zipcode'		=> 	$request->zipcode,
    		'ab_town'			=> 	$request->town,
    		'ab_country'		=> 	$request->country,
    		'ab_telephone'		=> 	$request->telephone,
    		'ab_email'			=> 	$request->email,
    		'ab_notes'			=> 	$request->notes,
	        'updated_at' 		=>  \Carbon\Carbon::now(),  # \Datetime()
    	]);
    	
    	return "success";

    }

    // save created child address book
    public function saveCreatedChild(Request $request){

       // set user time zone.
        date_default_timezone_set(Auth::user()->user_timezone);

        $save_address_book = DB::table('address_book')
        ->insert([
            'ab_parent_id'      =>  $request->parent_id,
            'ab_user_id'        =>  Auth::user()->id,
            'ab_shortname'      =>  $request->shortname,
            'ab_salutation'     =>  $request->salutation,
            'ab_firstname'      =>  $request->firstname,
            'ab_lastname'       =>  $request->lastname,
            'ab_company'        =>  $request->company,
            'ab_address_line1'  =>  $request->addressline1,
            'ab_address_line2'  =>  $request->addressline2,
            'ab_zipcode'        =>  $request->zipcode,
            'ab_town'           =>  $request->town,
            'ab_country'        =>  $request->country,
            'ab_telephone'      =>  $request->telephone,
            'ab_email'          =>  $request->email,
            'ab_notes'          =>  $request->notes,
            'created_at'        =>  \Carbon\Carbon::now(), # \Datetime()
            'updated_at'        =>  \Carbon\Carbon::now(),  # \Datetime()
        ]);

        if(count($save_address_book)>0){
             session()->flash('chil_address_created', 'New child address created.');
             return "success";
        }   
    }

    // delete address book

    public function deleteAddressBook(Request $request){

        //if parent is deleted. child is deleted.
        $delete = DB::table('address_book')
        ->where('ab_user_id', Auth::user()->id)
        ->where('ab_id', $request->ab_id)
        ->orWhere('ab_parent_id', $request->ab_id)
        ->delete();
    }

    //====================================================================================================================

    public function autoComplete(Request $request){

        $keyword = $request->keyword;
        $type    = $request->type;

        $ac_list = [];
        $recipient = $type=="receiver"? true:false;
        // SENDER ---------------------------------------------------------------
        $addressBooks = DB::table('address_book')
        ->where('ab_user_id', Auth::user()->id)
        ->where([
            ['ab_shortname',    'LIKE', '%' . $keyword . '%'],
            ['ab_firstname',    'LIKE', '%' . $keyword . '%', 'or'],
            ['ab_lastname',     'LIKE', '%' . $keyword . '%', 'or'],
            ['ab_company',      'LIKE', '%' . $keyword . '%', 'or'],
        ])
        ->when($recipient, function ($query, $recipient) {
            return $query->where('ab_possible_recipient', $recipient);
        })
        ->select('ab_id','ab_shortname','ab_firstname','ab_lastname','ab_company')
        ->get();

        if(count($addressBooks)>0){
            // match found
            foreach($addressBooks as $ab){
                // shortname exist
                if($ab->ab_shortname!=null){
                    $ab->ac = $ab->ab_shortname;
                }else{
                    //company, firstname, lastname )exist
                    if($ab->ab_company!=null && $ab->ab_firstname!= null && $ab->ab_lastname!=null){
                        //company(firstname lastname)
                        $ab->ac = $ab->ab_company."(".$ab->ab_firstname." ".$ab->ab_lastname.")";
                    }
                    //firstname, lastname, not exist(company)
                    elseif($ab->ab_company==null && $ab->ab_firstname!= null && $ab->ab_lastname!=null){
                        //firstname lastname
                        $ab->ac = "$ab->$ab_firstname $ab->$ab_lastname";
                    }
                    //company, firstname not exist
                    elseif($ab->ab_company==null && $ab->ab_firstname== null && $ab->ab_lastname!=null){
                        //lastname
                        $ab->ac = $ab->ab_lastname;
                    }
                    //company, firstname not exist
                    elseif($ab->ab_company==null && $ab->ab_firstname!= null && $ab->ab_lastname==null){
                        //firstname
                        $ab->ac = $ab->ab_firstname;
                    }
                    //firstname, lastname not exist.
                    else{
                        //company
                        $ab->ac = $ab->ab_company;
                    }
                }
            }
            // filter array of objects
            foreach($addressBooks as $abook){
                $md = array('ab_id'=>$abook->ab_id,'ac_keyword'=>$abook->ac);
                array_push($ac_list,$md);
            }
            return json_encode($ac_list);
        }
        else
        { 
           $addressBooks = "not_found";
           return $addressBooks;
        }
    }

    public function searchAddress(Request $request){

        $addressBook = DB::table('address_book')
        ->where('ab_user_id', Auth::user()->id)
        ->where('ab_id', $request->address_id)
        ->get();

        if(count($addressBook)>0){
            return json_encode($addressBook);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoanController extends Controller
{
    protected $user = [];

    public function auth(Request $request)
    {
        $this->user = json_decode(Storage::get('user.json'));
        if(!is_null($request->loan_id)){
            return $this->apply($request);
        }else{
           return $this->index();
        }
    }
    public function index()
    {
        $loan = Storage::get('loans.json');
        $loan = json_decode($loan);
        return response()->json(['success'=>true,'loans'=>$loan], 200);
    }
    public function apply(Request $request)
    {
        //Get Loans stored in JSON file
        $loans = Storage::get('loans.json');
        $loans = json_decode($loans);
        //Get User Loan mappings stored in JSON file
        $user_loan = json_decode(Storage::get('user_loan.json'));
        $id = $request->loan_id; //Loan id sent in the POST Request
        $user_loan_array = []; //User Loan array to be saved

        $found_loan = false; //Variable to check if loan id sent in the POST request belongs to a loan;
        foreach($loans as $loan){
            if($loan->id == $id){
                $found_loan = true;
                $created_on = date('Y-m-d');
                $expires_on = date('Y-m-d', strtotime("+".$loan->tenure));
                if(!empty($user_loan)){ // Check if User Loan mapping loaded is not empty
                    //Loop through the User Loan Mapping and check if user has a loan running that collides
                    //with the new loan they are applying for. Return Erro if true.
                    foreach($user_loan as $u){
                        if($u->expires_on <= $expires_on && $u->created_on >= $created_on){
                            return response()->json(['error'=>true,'message'=> 'User already has a loan within the running time of loan applied for!'], 401);
                        }
                    }
                }
                array_push($user_loan_array, ['user_id'=>$this->user->id,
                'loan_id' => $loan->id, 'created_on' => $created_on, 'expires_on' => $expires_on]);
            }
        }
        if($found_loan){
            $user_loan = array_merge($user_loan, $user_loan_array);
            Storage::put('user_loan.json', json_encode($user_loan));
            return response()->json(['success'=>true,'user_loan'=>$user_loan], 200);
        }else{
            return response()->json(['error'=>true, 'message'=>'Loan ID not found!'], 401);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;


class AuthController extends Controller
{
    //

        public function register(Request $request)
        {
           
            $user = new User;
            
             

            $validator = Validator::make($request->all(),[
            'firstname' => 'required|min:3', 
            'lastname' => 'required|min:3', 
            'email' => 'required|email|max:255|unique:tbl_users',
            'password' => 'required|max:10|min:6',
            'mobile' => 'required|numeric|unique:tbl_users', 
            'alt_mobile' => 'numeric', 
            'user_role' => 'required|numeric', 
            'registered_by' => 'required|numeric', 
            
            ]);
            
                
            if($validator->fails()){

                $validation_errors =  $validator->errors();
              
                return response()->json(['status'=>'fail','returnmsg'=> $validator->errors()->first() ]);
                 
            }
            

            $result = $user->create([
                'firstname' => $request->firstname, 
                'lastname' => $request->lastname, 
                'email' => $request->email, 
                'password' => bcrypt($request->password),
                'mobile' => $request->mobile, 
                'alt_mobile' => $request->alt_mobile, 
                'user_role' => $request->user_role, 
                'registered_by' => $request->registered_by
               
            ]);

            if($result){
                return response()->json(['status'=>'success','returnmsg'=> "User registered succesfully!" ]);
            }
            else{
                return response()->json(['status'=>'fail','returnmsg'=> "Something went wrong!" ]);

            }
           
}



    public function login(Request $request)
    {

 
          
            $validator = Validator::make($request->all(),[
                'email' => 'required',
                'password'=>'required'
            ]);


            if($validator->fails()){
                $validation_errors = $validator->errors();
                return response()->json(['status'=>'fail','returnmsg'=>$validator->errors()->first()]);
            }    

         

        

            if( Auth::attempt(['email'=>$request->email, 'password'=>$request->password]) ) {
                $user = Auth::user();
          

                $token = $user->createToken($user->email.'-'.now());
                
                
                    return response()->json(['status'=>'success','returnmsg'=>'Login Successful',
                        'token' => $token->accessToken
                        ]);
               
                
            }
            else{
    
                return response()->json(['status'=>'fail','returnmsg'=>'Invalid username or password'  ]);
                
              
            }

        

            

    }







   }



 

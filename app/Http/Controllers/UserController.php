<?php

    namespace App\Http\Controllers;

    use App\User;
    use App\Http\Requests\RegisterAuthRequest;
    use Illuminate\Support\Str;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Storage;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class UserController extends Controller
    {
        public function register(RegisterAuthRequest $request)
        {
            $user = [
                'id' => rand(1,20),
                'name' => $request->name,
                'email'    => $request->email,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'date_of_birth' => $request->date_of_birth,
                'password' => Hash::make($request->password),
            ];
            $user = json_encode($user);
            Storage::put('user.json', $user);
            return $this->login($request);
        }

        public function login(Request $request)
        {
            $user = Storage::get('user.json');
            $user = json_decode($user);
            if(!empty($user)){
                if($user->email == $request->email && Hash::check($request->password, $user->password)){
                    $userx = json_encode($user);
                    Storage::put('user.json', $userx);
                    $user->password = '';
                    return response()->json(['success'=>true, 'user'=>$user], 200);
                }else{
                    return response()->json(['error' => true,'message' => 'Invalid Email or Password.', ], 401);
                }
            }else{
                return response()->json(['error' => true,'message' => 'Unregistered User!', ], 401);
            }
        }

        public function logout(Request $request)
        {
            $user = json_encode([]);
            Storage::put('user_loan.json', $user);
            Storage::put('user.json', $user);
            return response()->json(['success'=>true], 200);
        }

    }

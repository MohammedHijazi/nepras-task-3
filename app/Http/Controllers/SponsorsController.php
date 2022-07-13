<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Governorate;
use App\Models\PersonalSponsor;
use App\Models\Sponsor;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SponsorsController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        $countries = Country::all();
        $gevernorates = Governorate::all();
        $cities = City::all();
        $streets = Street::all();

        return view('mgmt.create',[
            'countries' => $countries,
            'gevernorates' => $gevernorates,
            'cities' => $cities,
            'streets' => $streets
        ]);
    }


    public function store(Request $request)
    {
        if($request->ty == 'personal'){
            $request->validate([
               'first_name' => 'required',
                'second_name' => 'required',
                'third_name' => 'required',
                'last_name' => 'required',
                'id_number' => 'required|integer|digits:9|digits_between: 0,9|unique:personal_sponsors,id_number',
                'id_type' => 'required',
                'phone' => 'required|digits:9|digits_between: 0,10',
                'mobile' => 'required|digits:10|digits_between: 0,10',
                'email' => 'required|email|unique:sponsors,email',
                'governorate' => 'required',
                'city' => 'required',
                'street' => 'required',
                'address' => 'required',
                'nationality'=>'required',
                'country'=>'required',
            ]);

            DB::beginTransaction();
            try {
                $name = $request->first_name . ' ' . $request->second_name . ' ' . $request->third_name . ' ' . $request->last_name;
                $email = $request->email;
                $type = 'personal';
                $country = $request->country;
                $governorate = $request->governorate;
                $city = $request->city;
                $street = $request->street;
                $address = $request->address;
                $phone = $request->phone;
                $mobile = $request->mobile;
                $nationality = $request->nationality;
                $id_type = $request->id_type;
                $id_number = $request->id_number;


                //inserting in sponsor table (father table) and getting the ID
                $sponsor_id = Sponsor::insertGetId([
                    'name' => $name,
                    'email' => $email,
                    'type' => $type,
                    'country' => $country,
                ]);


                //inserting in personal sponsor table (son table)
                PersonalSponsor::insert([
                    'sponsor_id' => $sponsor_id,
                    'governorate' => $governorate,
                    'city' => $city,
                    'street' => $street,
                    'address' => $address,
                    'phone' => $phone,
                    'mobile' => $mobile,
                    'nationality' => $nationality,
                    'id_type' => $id_type,
                    'id_number' => $id_number
                ]);

                DB::commit();
                return redirect()->route('home')->with('success', 'Sponsor created successfully');
            }catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }

        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}

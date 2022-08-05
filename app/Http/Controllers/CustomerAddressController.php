<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Transformers\CustomerAddressTransformer;
use App\Traits\ApiResponser;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerAddressType;
use App\Models\Country;
use Auth;

class CustomerAddressController extends Controller
{
    use ApiResponser;

    protected $transformer;
    protected $auth;

    public function __construct(CustomerAddress $transformer)
    {
        $this->transformer = $transformer;
        $this->auth = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, $id)
    {
        /** Validation here */
        $toValidate = [
            'address_type_id' => 'required|numeric',
            'country_id'      => 'required|numeric',
            'address'         => 'required|string',
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        /** Save here */
        $customer            = Customer::find($id);
        $customerAddressType = CustomerAddressType::find($request->address_type_id);
        $country             = Country::find($request->country_id);
        $customerAddress     = new CustomerAddress();
        $customerAddress->customer()->associate($customer);
        $customerAddress->customerAddressType()->associate($customerAddressType);
        $customerAddress->address = $request->address;
        $customerAddress->country()->associate($country);
        $customerAddress->save();

        return $this->successResponse($this->transformer->transform($customerAddress), Response::HTTP_CREATED);
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
        /** Validation here */
        $toValidate = [
            'address_type_id' => 'required|numeric',
            'country_id'      => 'required|numeric',
            'address'         => 'required|string',
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }
        
        /** Update here */
        $customer            = Customer::find($id);
        $customerAddressType = CustomerAddressType::find($request->address_type_id);
        $country             = Country::find($request->country_id);
        $customerAddress     = new CustomerAddress();
        $customerAddress->customer()->associate($customer);
        $customerAddress->customerAddressType()->associate($customerAddressType);
        $customerAddress->address = $request->address;
        $customerAddress->country()->associate($country);
        $customerAddress->save();

        return $this->successResponse($this->transformer->transform($customerAddress), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $customerAddress = CustomerAddress::where('customer_id', $id)->where('customer_address_type_id', $request->address_type_id)->first();
        if (!is_null($customerAddress)) {
            $customerAddress->delete();
            return $this->successResponse(['Destroy' => 'Success'], Response::HTTP_OK);
        }
        return $this->errorResponse(['Destroy' => 'Fail'], Response::HTTP_NOT_FOUND);
    }
}

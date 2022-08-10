<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Transformers\CustomerAddressTransformer;
use App\Traits\ApiResponser;
use App\Models\Customer;
use App\Models\Country;
use Auth;

class CustomerAddressController extends Controller
{
    use ApiResponser;

    protected $transformer;
    protected $auth;

    public function __construct(CustomerAddressTransformer $transformer)
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
            'country_id'               => 'required|numeric',
            'address'                  => 'required|string',
            'customer_address_type_id' => [
                'required',
                'numeric',
                Rule::unique('customer_addresses')
                    ->using(function ($q) use($id) { 
                        $q->where('customer_id', $id); 
                    })
            ],
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        try {
            /** Save here */
            $customer = Customer::where('id', $id)->where('organization_id', $this->auth->organization_id)->first();
            $address  = $customer->addresses()->create([
                'customer_address_type_id' => $request->customer_address_type_id,
                'country_id'               => $request->country_id,
                'address'                  => $request->address,
            ]);

            return $this->successResponse($this->transformer->transform($address), Response::HTTP_CREATED);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $customer = Customer::where('id', $id)->where('organization_id', $this->auth->organization_id)->first();
            $address  = $customer->addresses()->where('customer_address_type_id', $request->customer_address_type_id)->first();

            return $this->successResponse($this->transformer->transform($address), Response::HTTP_OK);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
            'country_id'               => 'required|numeric',
            'address'                  => 'required|string',
            'customer_address_type_id' => [
                    'required',
                    'numeric',
                    Rule::unique('customer_addresses')
                        ->using(function ($q) use($id) { 
                            $q->where('customer_id', $id); 
                        })->ignore($request->customer_address_type_id, 'customer_address_type_id')
            ],
            
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        try {
            /** Update here */
            $customer = Customer::where('id', $id)->where('organization_id', $this->auth->organization_id)->first();
            $address  = tap($customer->addresses()->where('customer_address_type_id', $request->customer_address_type_id))
                ->update([
                    'address'    => $request->address,
                    'country_id' => $request->country_id,
                ])->first();

            return $this->successResponse($this->transformer->transform($address), Response::HTTP_OK);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $customer = Customer::where('id', $id)->where('organization_id', $this->auth->organization_id)->first();
            $address  = $customer->addresses()->where('customer_address_type_id', $request->customer_address_type_id)->delete();
            
            return $this->successResponse(['Success' => $address ? true : false], Response::HTTP_OK);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

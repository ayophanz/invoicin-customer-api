<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Transformers\CustomerAddressTransformer;
use App\Traits\ApiResponser;
use App\Models\Customer;
use App\Models\Country;
use Auth;

class CustomerAddressController extends Controller
{
    use ApiResponser;

    protected $transformer;

    public function __construct(CustomerAddressTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        
        $customer  = Customer::find($id);
        $addresses = $customer->customerAddresses()->get();
        return $this->successResponse(
            $this->transformer->transformCollection(
                $addresses->transform(function ($item, $key) {
                    return $item;
                })->all(),
                Response::HTTP_OK 
            )
        );
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
            'customer_address_type_id' => 'required|numeric|unique:customer_addresses,customer_address_type_id,' . $request->customer_address_type_id . ',id,customer_id,' . $id,
            'country_id'               => 'required|numeric',
            'address'                  => 'required|string',
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $address = [];
        try {
            /** Save here */
            $customer = Customer::find($id);
            $address  = $customer->customerAddresses()->create([
                'customer_address_type_id' => $request->customer_address_type_id,
                'country_id'               => $request->country_id,
                'address'                  => $request->address,
            ]);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($this->transformer->transform($address), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $customerAddress = CustomerAddress::where('customer_id', $id)->where('customer_address_type_id', $request->customer_address_type_id)->first();
        if (!is_null($customerAddress)) {
            return $this->successResponse($this->transformer->transform($customerAddress), Response::HTTP_OK);
        }

        return $this->errorResponse(['Status' => 'Not Found'], Response::HTTP_NOT_FOUND);
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
            'customer_address_type_id' => 'required|numeric|unique:customer_addresses,customer_address_type_id,' . $request->customer_address_type_id . ',id,customer_id,' . $id,
            'country_id'               => 'required|numeric',
            'address'                  => 'required|string',
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $address = [];
        try {
            /** Update here */
            $customer = Customer::find($id);
            $address  = tap($customer->customerAddresses()->where('customer_address_type_id', $request->customer_address_type_id))
                ->update([
                    'address' => $request->address,
                    'country_id' => $request->country_id,
                ])->first();
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($this->transformer->transform($address), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $customer = Customer::find($id);
        $address  = $customer->customerAddresses()->where('customer_address_type_id', $request->customer_address_type_id)->first();
        if (!is_null($address)) {
            $address->delete();
            return $this->successResponse(['Status' => 'Ok'], Response::HTTP_OK);
        }
        return $this->errorResponse(['Status' => 'Not Found'], Response::HTTP_NOT_FOUND);
    }
}

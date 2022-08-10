<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use App\Models\Customer;
use App\Transformers\CustomerTransformer;
use Auth;

class CustomerController extends Controller
{
    use ApiResponser;

    protected $transformer;
    protected $auth;

    public function __construct(CustomerTransformer $transformer)
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
        $customers = Customer::where('organization_id', $this->auth->organization_id)->get();
        return $this->successResponse(
            $this->transformer->transformCollection(
                $customers->transform(function ($item, $key) {
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
    public function store(Request $request)
    {
        /** Validation here */
        $toValidate = [
            'type'  => 'required',
            'email' => 'required|max:255|email|unique:customers'
        ];
        if ($request->type == 'individual') {
            $toValidate['first_name'] = 'required';
            $toValidate['last_name']  = 'required';
        }
        if ($request->type == 'company') $toValidate['company_name'] = 'required';

        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        try {
            /** Save here */
            $customer                  = new Customer();
            $customer->type            = $request->type;
            $customer->organization_id = $this->auth->organization_id;
            $customer->email           = $request->email;
            $customer->phone_no        = $request->phone_no;
            if ($request->type == 'individual') {
                $customer->first_name = $request->first_name;
                $customer->last_name  = $request->last_name;
            }
            if ($request->type == 'company') $customer->company_name = $request->company_name;
            $customer->save();

            return $this->successResponse($this->transformer->transform($customer), Response::HTTP_CREATED);
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

            return $this->successResponse($this->transformer->transform($customer), Response::HTTP_OK);
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
            'type'  => 'required',
            'email' => 'required|max:255|string|email|unique:customers,email,'.$id
        ];
        if ($request->type == 'individual') {
            $toValidate['first_name'] = 'required';
            $toValidate['last_name']  = 'required';
        }
        if ($request->type == 'company') $toValidate['company_name'] = 'required';

        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);

        try {
            /** Update here */
            $customer           = Customer::where('id', $id)->where('organization_id', $this->auth->organization_id)->first();
            $customer->type     = $request->type;
            $customer->email    = $request->email;
            $customer->phone_no = $request->phone_no;
            if ($request->type == 'individual') {
                $customer->first_name = $request->first_name;
                $customer->last_name  = $request->last_name;
            }
            if ($request->type == 'company') $customer->company_name = $request->company_name;
            $customer->save();

            return $this->successResponse($this->transformer->transform($customer), Response::HTTP_OK);
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
    public function destroy($id)
    {
        try {
            $customer = Customer::where('id', $id)->where('organization_id', $this->auth->organization_id)->delete();

            return $this->successResponse(['Success' => $customer ? true : false], Response::HTTP_OK);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

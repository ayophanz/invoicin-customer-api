<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use App\Models\Customer;
use App\Transformers\CustomerSettingTransformer;

class CustomerSettingController extends Controller
{
    use ApiResponser;

    protected $transformer;

    public function __construct(CustomerSettingTransformer $transformer)
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
        $customer = Customer::find($id);
        $settings = $customer->settings()->get();
        return $this->successResponse(
            $this->transformer->transformCollection(
                $settings->transform(function ($item, $key) {
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
            'key'   => 'required|unique:customer_settings,key,' . $request->key . ',id,sourceable_type,App\Models\Customer,sourceable_id,' . $id,
            'value' => 'required',
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $setting = [];
        try {
            /** Save here */
            $customer = Customer::find($id);
            $setting = $customer->settings()->create([
                'key'   => $request->key,
                'value' => $request->value,
            ]);
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($this->transformer->transform($setting), Response::HTTP_OK);
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
            'key'   => 'required',
            'value' => 'required',
        ];
        $validator = Validator::make($request->all(), $toValidate);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $setting = [];
        try {
            /** Update here */
            $customer = Customer::find($id);
            $setting = $customer->settings()->updateOrCreate(
                ['key' => $request->key],
                ['value' => $request->value],
            );
        } catch(\Exception $e) {
            return $this->errorResponse(['Error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($this->transformer->transform($setting), Response::HTTP_OK);
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
        $setting = $customer->settings()->where('key', $request->key)->first();
        if (!is_null($setting)) {
            $setting->delete();
            return $this->successResponse(['Status' => 'Ok'], Response::HTTP_OK);
        }
        return $this->errorResponse(['Status' => 'Not Found'], Response::HTTP_NOT_FOUND);
    }
}

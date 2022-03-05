<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TransactaionsController extends Controller
{
     
    public function list(Request $request)
    {
        $files = Storage::disk('public')->allFiles();

        $data = Array();
        foreach($files as $f){
            $fileName = basename($f, ".json");
            $dc = Array();
            if($fc = Storage::disk('public')->get($f)){
                if($dc = json_decode($fc,true)){
                    $new=array();
                    foreach($dc as $d){
                        $d['provider']=$fileName;
                        $new[]=$d;
                    }
                    $dc=$new;
                    $data = empty($data) ? $dc:array_merge($data,$dc);
                }
            }
        }
        
        // filter with provider
        if($request->provider)
        {
            $data = array_filter($data, function ($tranaction) use ($request) {
                    return ($tranaction['provider'] == $request->provider);
                });
        }

        // filter with status
        if($request->statusCode)
        {
            if($request->statusCode=='paid'){
                $statusArr=['done',1,100];
            }elseif($request->statusCode=='pending'){
                $statusArr=['wait',2,200];
            }
            else{
                $statusArr=['nope',3,300];
            }
            $data = array_filter($data, function ($tranaction) use ($request,$statusArr) {
                return (isset($tranaction['status']) && in_array($tranaction['status'], $statusArr) || isset($tranaction['transactionStatus']) && in_array($tranaction['transactionStatus'], $statusArr) );
                });

        }
        // filter with currency
        if($request->currency)
        {
            $data = array_filter($data, function ($tranaction) use ($request) {
                return ((isset($tranaction['currency']) && $tranaction['currency']==$request->currency) || (isset($tranaction['Currency']) && $tranaction['Currency']==$request->currency));
                });

        }
        // filter with amount range    
        if($request->amounteMin)
        {
            $data = array_filter($data, function ($tranaction) use ($request) {
                return ((isset($tranaction['amount']) && $tranaction['amount'] >= $request->amounteMin) || (isset($tranaction['transactionAmount']) && $tranaction['transactionAmount'] >= $request->amounteMin));
                });

        }
        if($request->amounteMax)
        {
            $data = array_filter($data, function ($tranaction) use ($request) {
                return ((isset($tranaction['amount']) && $tranaction['amount'] <= $request->amounteMax) || (isset($tranaction['transactionAmount']) && $tranaction['transactionAmount'] <= $request->amounteMax));
                });

        }
         
        return response(['tranactions' => $data,'message' => 'Retrieved successfully'], 200);
    }
    public function save(Request $request)
    {
        
        $data = $request->only('amount','currency','phone','status');
        $data['created_at']=date('Y-m-d h:i:s');
        $data['id']=uniqid();
        $fileName = $request->provider. '.json';
        Storage::disk('public')->put($fileName, json_encode($data));
        return response([ 'provider' => $data, 'message' => 'Added successfully'], 201);
    }

    //
}

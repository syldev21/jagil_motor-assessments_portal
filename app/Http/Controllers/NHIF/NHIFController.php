<?php


namespace App\Http\Controllers\NHIF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class NHIFController extends Controller
{
    public function index()
    {
        return view('NHIF.index');
    }

    public function addClaimForm(Request  $request)
    {
        return view('NHIF.add-claim-form');
    }
}

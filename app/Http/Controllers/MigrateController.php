<?php


namespace App\Http\Controllers;


use App\Claim;
use App\Conf\Config;
use App\Document;
use App\User;
use Illuminate\Http\Request;

class MigrateController
{
    public function users()
    {
        $users= User::all();
        foreach ($users as $user)
        {
            $name = $user->name;
            $nameArray = explode(" ", $name);
            $firstName = isset($nameArray[0]) ? $nameArray[0] : NULL;
            $middleName = isset($nameArray[1]) ? $nameArray[1] : NULL;
            $lastName = isset($nameArray[2]) ? $nameArray[2] : NULL;
            User::where(['id'=>$user->id])->update([
                'firstName'=> $firstName,
                'middleName'=> $middleName,
                'lastName' => $lastName
            ]);
        }
    }
    public function updatePdfType(Request $request)
    {
        $documents= Document::where(['documentType' => Config::$DOCUMENT_TYPES["PDF"]["ID"]])->get();
        foreach ($documents as $document)
        {
            if (str_contains($document->name, 'claim')) {
//                $claim = Claim::where(['id' => $document->claimID])->first();
                Document::where(['claimID' => $document->claimID])
                    ->where('name','like','%claim%')
                    ->update([
                    "pdfType" => Config::PDF_TYPES['CLAIM_FORM']['ID']
                ]);
            }elseif (str_contains($document->name, 'invoice'))
            {
                Document::where(['claimID' => $document->claimID])
                    ->where('name','like','%invoice%')
                    ->update([
                    "pdfType" => Config::PDF_TYPES['INVOICE']['ID']
                ]);
            }
        }
    }
}

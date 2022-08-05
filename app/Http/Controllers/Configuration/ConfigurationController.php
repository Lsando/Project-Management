<?php
/**
 * created at: 01-11-2021
 * created by: lsando
 * name: ConfigurationController
 * description: 
 */
namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\CismAuthor;
use App\Models\Config;
use App\Models\Configuration;
use App\Models\Recipient;
use App\Models\UserInstitution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Exibe o formulário para actualizar as configurações do sistema
     *
     * @return \Illuminate\Http\Response
     */
    public function system_configuration(Request $request)
    {
        $recipients = Recipient::whereNull('deleted_at')
        ->orderBy('r_id', 'desc')
        ->get();
        $organizations = UserInstitution::whereNull('deleted_at')->get();
        $cism_authors = CismAuthor::whereNull('deleted_at')->get();

        return view('admin.config.system',[
            'recipients' => $recipients,
            'organizations' => $organizations,
            'cism_authors' => $cism_authors
        ]);
    }


    /**
     * Actualizar o número de tentativas de login e tempo de espera apos exceder o numero de tentativas 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateConfiguration(Request $request)
    {
        
        $rules = [
            'decay_minutes' =>'required',
            'login_attemps' =>'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(),'state'=>412,'data'=>array()], 412);
            
        }            
        Configuration::where("type", "maxAttempts")
        ->update([
            "value" => $request->login_attemps
        ]);
        Configuration::where("type", "decayMinutes")
        ->update([
            "value" => $request->decay_minutes
        ]);
        return redirect()->back()->with('success', translate('requisao_submetida_sucesso'));
    }
}

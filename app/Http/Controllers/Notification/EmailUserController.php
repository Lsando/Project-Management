<?php
/**
 * created at: 01-11-2021
 * created by: lsando
 * name: EmailUserController
 * description: Métodos para auxiliar o envio de emails depois de qualquer atualização do Projecto
 */

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\EmailUser;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Project;
use App\Models\User;
use App\Models\EmailProject;

class EmailUserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $role_id
     * @return array
     */
    public function getEmailUsers($r_id)
    {
        $data = DB::select('SELECT email FROM users WHERE r_id = ? AND deleted_at IS NULL ', [$r_id]);
        $emails =[];
        if(!empty($data)){
            foreach($data as $row){
                array_push($emails,
                    $row->email
                );
            }
        }
        return $emails;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array $sendTo = lista de emails
     * @param  array $message(title, body)
     * @param  string $subject
     * @return \Illuminate\Http\Response
     */

    public function sendEmail($sendTo=array(), $message=array(),$subject)
    {

        $mailData = [
            'title' => $message['title'],
            'body' => $message['body'],
            'url' => action([\App\Http\Controllers\AuthController::class, 'auth'])
        ];

        Mail::to($sendTo)->send(new EmailUser($mailData, $subject));

        return response()->json([
            'message' => 'Notificação enviada com sucesso'
        ], Response::HTTP_OK);
    }
}

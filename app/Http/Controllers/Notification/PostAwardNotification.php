<?php

/**
 * created at: 01-11-2021
 * created by: lsando
 * name: PostAwardNotification
 * description: Métodos para auxiliar o envio de emails depois de qualquer atualização do Projecto (postaward)
 */

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostAwardEmail;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PostAwardNotification extends Controller
{
     /**
     * Update the specified resource in storage.
     *
     * @param  array $sendTo = lista de emails
     * @param  array $message(title, body, document, subject)
     * @return \Illuminate\Http\Response
     */

    public function sendEmail($sendTo=array(), $message=array()) 
    {

        $mailData = [
            'title' => $message['title'],
            'body' => $message['body'],
            'document' => $message['document'],
            'subject' => $message['subject']
        ];

        Mail::to($sendTo)->send(new PostAwardEmail($mailData, $mailData['subject'], $mailData['document']));

        return response()->json([
            'message' => 'Notificação enviada com sucesso'
        ], Response::HTTP_OK);
    }
}

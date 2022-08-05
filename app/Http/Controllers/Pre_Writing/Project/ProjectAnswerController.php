<?php
/**
 * created at: 01-11-2021
 * created by: lsando
 * name: ProjectAnswerController
 * description: Class para gestão das respostas ao inquérito na submissão da proposta
 */
namespace App\Http\Controllers\Pre_Writing\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectAnswer;

class ProjectAnswerController extends Controller
{
     /**
     * Registo de respostas ao inquérito
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $p_id)
    {
        $pq_id=1;
        $size = count($request->input('resposta'));
        $answers = $request->input('resposta');
        
        $pa_id = DB::table('pa_project_answers')->max('pa_id')+1;
        for ($i=0; $i < $size; $i++) {
            ProjectAnswer::create([
                'pa_id' => $pa_id,
                'pq_id' => $pq_id,
                'p_id' => $p_id,
                'pa_answer' => $answers[$i],
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
            $pq_id++;
            $pa_id++;
        }
    }
    public function show($p_id)
    {
        $answer = DB::table('pa_project_answers')
        ->join('pq_project_questions', 'pq_project_questions.pq_id', 'pa_project_answers.pq_id')
        ->whereNull('pq_project_questions.deleted_at')
        ->where('pa_project_answers.p_id', $p_id)
        ->get();

        return $answer;
    }


}

<?php


class SurveyController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function surveyDetailCharts(Request $request, $id)
    {
        $survey = $this->surveyRepository->getSurveysById($this->company->id, $id);
        $start = $request->start ?? $survey->survey_period_starts;
        $end = $request->end ?? Carbon::today()->format('Y-m-d');

        $answerSurveyRatings = $this->surveyRepository->getAnswerSurveyRatingCountByCompletionDate($id, $start, $end);

        $surveyAnswerRating['rating_answer_one'] = ($answerSurveyRatings[0]->all_count) ? round(($answerSurveyRatings[0]->one_count * 100) / ($answerSurveyRatings[0]->all_count)) : 0;
        $surveyAnswerRating['rating_answer_two'] = ($answerSurveyRatings[0]->all_count) ? round(($answerSurveyRatings[0]->two_count * 100) / ($answerSurveyRatings[0]->all_count)) : 0;
        $surveyAnswerRating['rating_answer_three'] = ($answerSurveyRatings[0]->all_count) ? round(($answerSurveyRatings[0]->three_count * 100) / ($answerSurveyRatings[0]->all_count)) : 0;
        $surveyAnswerRating['rating_answer_four'] = ($answerSurveyRatings[0]->all_count) ? round(($answerSurveyRatings[0]->four_count * 100) / ($answerSurveyRatings[0]->all_count)) : 0;
        $surveyAnswerRating['rating_answer_five'] = ($answerSurveyRatings[0]->all_count) ? round(($answerSurveyRatings[0]->five_count * 100) / ($answerSurveyRatings[0]->all_count)) : 0;
        $surveyAnswerRating['rating_answer_avg_percent'] = round((100 * $answerSurveyRatings[0]->avg_answer) / 5);

        return response()->json([
            'success' => true,
            'survey_answer_rating' => $surveyAnswerRating,
        ], Response::HTTP_OK);
    }

}
class SurveyRepository extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getSurveysByIds($companyId, $idsSurvey)
    {
        return $this->startCondition()->where(['surveys.id_company' => $companyId])
            ->whereIn('id', $idsSurvey)->with('category')->paginate(env('PER_PAGE', 21));
    }

    public function getAnswerSurveyRatingCountByCompletionDate($surveyId, $start, $end)
    {
        $query = DB::table('survey_answers')
            ->where('id_surveys', $surveyId);
        if ($start && $end) {
            $query->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end);
        }

        $query->selectRaw('
        id_form_template_field,
        COUNT(CASE WHEN answer = 1 THEN 1 END) as one_count,
        COUNT(CASE WHEN answer = 2 THEN 1 END) as two_count,
        COUNT(CASE WHEN answer = 3 THEN 1 END) as three_count,
        COUNT(CASE WHEN answer = 4 THEN 1 END) as four_count,
        COUNT(CASE WHEN answer = 5 THEN 1 END) as five_count,
        COUNT(answer) as all_count,
        AVG(answer) as avg_answer
    ');
        return $query->get();
    }

}

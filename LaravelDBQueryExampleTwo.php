<?php


class SurveyController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function index($request)
    {
        $sortBy = 'created_at';
        $sortByType = 'desc';
        $surveys = $this->surveyRepository->getSurveys($request, $this->user);
        $surveyItems = $surveys;
        $inProgressIds = [];
        $endedIds = [];
        $upcomingIds = [];

        foreach ($surveys->get() as $k => $item) {
            $completion_date = $this->user->completion_date($item);
            if ($this->isUpcoming($item)) {
                $upcomingIds[] = $item->id;
            } elseif ($this->isInProgress($item, $completion_date)) {
                $inProgressIds[] = $item->id;
            } elseif ($completion_date || Carbon::parse($item->survey_deadline) < Carbon::now('UTC')) {
                $endedIds[] = $item->id;
            }
        }

        if (!empty($request->input('sort_by'))) {
            $sortBy = $request->input('sort_by');
        }

        if (!empty($request->input('sort_by_type')) && $request->input('sort_by_type') === 'asc') {
            $sortByType = $request->input('sort_by_type');
        }

        if (!empty($sortBy)) {
            if ($sortBy == Survey::SURVEY_IN_PROGRESS_VALUE && count($inProgressIds)) {
                $ids = implode(',', $inProgressIds);
            } elseif ($sortBy == Survey::SURVEY_ENDED_VALUE && count($endedIds)) {
                $ids = implode(',', $endedIds);
            } elseif ($sortBy == Survey::SURVEY_UPCOMING_VALUE && count($upcomingIds)) {
                $ids = implode(',', $upcomingIds);
            }

            if (isset($ids)) {
                $surveyItems->orderByRaw(DB::raw("FIELD(surveys.id, $ids) $sortByType"));
            }
        }

        return $surveyItems->paginate(env('PER_PAGE', 21));
    }

}

class SurveyRepository extends Repository
{
    public function getSurveys($user)
    {
        $query = Survey::where(['surveys.id_company' => $user->id_company]);
        $query->leftJoin('survey_users', 'surveys.id', '=', 'survey_users.id_surveys');
        $query->leftJoin('survey_teams', 'surveys.id', '=', 'survey_teams.id_surveys');
        $query->leftJoin('survey_answer_users', 'survey_answer_users.id_surveys', '=', 'surveys.id');

        $query->select(
            'surveys.id',
            'surveys.title',
            'surveys.not_delete',
            'surveys.description',
            'surveys.anonymous',
            'surveys.is_company',
            'surveys.id_category',
            'surveys.survey_period_starts',
            'surveys.survey_deadline',
            'surveys.created_at',
            'surveys.updated_at',
            'surveys.created_by',
            'surveys.color',
            'surveys.id_form_template',
            'survey_teams.id_team',
            'surveys.recurring',
            'surveys.recurring_every_interval',
            'surveys.recurring_every_count',
            'surveys.recurring_ends_on',
            'surveys.recurring_ends_after',
            'survey_users.id_user'
        );

        if (!$user->isAdministrator()) {
            $teamIds = $user->teams()->pluck('teams.id')->toArray();
            if (!empty($teamIds)) {
                $teamIdsStr = '(' . implode(',', $teamIds) . ')';
                $query->where(function ($query) use ($teamIdsStr, $user) {
                    $query->whereRaw(('survey_users.id_user = ' . $user->id . ' OR survey_teams.id_team in ' . $teamIdsStr))
                        ->orWhere('surveys.created_by', $user->id)
                        ->orWhere('surveys.is_company', Survey::IS_COMPANY_YES);
                });

            } else {
                $query->where(function ($query) use ($user) {
                    $query->where('survey_users.id_user', $user->id)
                        ->orWhere('surveys.created_by', $user->id)
                        ->orWhere('surveys.is_company', Survey::IS_COMPANY_YES);
                });
            }
        }

        return $query->distinct('surveys.id');
    }

}

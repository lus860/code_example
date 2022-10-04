<?php

namespace App\Repository;

use App\Models\CheckinNote as Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class CheckinNoteRepository  extends BaseRepository
{
    protected $model;

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCheckinNoteById($id)
    {
        return $this->startCondition()->where('id', $id)->first();
    }

    public function getCheckinNotePluck($id_apm_checkins, $userId)
    {
        return $this->startCondition()
            ->where('created_by', $userId)
            ->where('id_checkins', $id_apm_checkins)
            ->pluck('id')->toArray();
    }

    public function getCheckinNotePaginate($id_apm_checkins, $userNotesIds)
    {
        return $this->startCondition()
            ->where('id_checkins', $id_apm_checkins)
            ->where('status', Model::NOT_PRIVATE)
            ->orWhereIn('id', $userNotesIds)
            ->orderBy('created_at', 'desc')
            ->paginate(env('PER_PAGE_MIN'));
    }

}

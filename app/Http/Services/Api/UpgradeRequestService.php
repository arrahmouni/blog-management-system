<?php

namespace App\Http\Services\Api;

use Illuminate\Support\Facades\DB;
use App\Enums\UpgradeRequestStatuses;
use App\Enums\UserRoles;
use app\Http\Services\Api\BaseApiService;
use app\Models\UpgradeRequests as CrudModel;

class UpgradeRequestService extends BaseApiService
{
    /**
     * Create a new Model instance.
     *
     * @param User $user
     * @return CrudModel
     */
    public function createRequest($user): CrudModel
    {
        $model = DB::transaction(function () use($user) {
            $model = $user->upgradeRequests()->create();

            return $model;
        });

        return $model;
    }

    public function accept($upgradeRequests)
    {
        DB::transaction(function () use($upgradeRequests) {
            $upgradeRequests->status = UpgradeRequestStatuses::ACCEPTED->value;
            $upgradeRequests->save();

            $user = $upgradeRequests->user;
            $user->role = UserRoles::WRITER->value;
            $user->save();
        });

        return $upgradeRequests;
    }

    public function reject($upgradeRequests)
    {
        $upgradeRequests->status = UpgradeRequestStatuses::REJECTED->value;
        $upgradeRequests->save();

        return $upgradeRequests;
    }
}

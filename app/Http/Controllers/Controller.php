<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function checkAdminOrCommittee($userId, $eventId){
        $admin = false;
        $committee = false;

        // Check whether the user is an admin or comittee
        $select = DB::table('user_properties')->where('user_id', $userId);
        $query = $select->where('field_id', 'role.administrator')->first();
        if ($query && $query->value == '1'){
            $admin = true;
            $committee = true;
        }
        $select = DB::table('user_properties')->where('user_id', $userId);
        $query = $select->where('field_id', 'role.committee')->first();
        if ($query && $query->value == '1'){
            $committee = true;
        }
        if (!$admin && !$committee && $eventId > 0){
            // Check whether Event ID exists
            $select = DB::table('events')->where('id', $eventId)->first();
            if ($select){
                $select = DB::table('event_roles')->where('event_id', $eventId);
                $query = $select->where('user_id', $userId)->where('system_role', 'role.administrator')->first();
                if ($query){
                    $admin = true;
                    $committee = true;
                }
                $select = DB::table('event_roles')->where('event_id', $eventId);
                $query = $select->where('user_id', $userId)->where('system_role', 'role.committee')->first();
                if ($query){
                    $committee = true;
                }
            }
        }

        return (object) [
            'admin' => $admin,
            'committee' => $committee
        ];
    }

    /**
     * Utility function to ensure that the user has been authenticated
     */
    protected function requiresLogin(string $path, int $eventId, bool $for_admin, bool $for_committee){
        if (!Auth::check()){
            Session::put('error', 'Please log in before continuing to this page');
            Session::put('loginTo', $path);
            return false;
        }

        $check = $this->checkAdminOrCommittee(Auth::id(), $eventId);

        if (($check->admin && $for_admin) || ($check->committee && $for_committee) == true) return true;
        Session::put('error', 'You are not authorized to access this page');
        return false;
    }

    public function validateFields($event_permissions, $user_properties)
    {
        $eligible_to_register = true;
        for ($i = 0, $j = 0; $i < count($event_permissions) && $j < count($user_properties); $i++, $j++){
            $diff = strcmp($event_permissions[$i]->field_id, $user_properties[$j]->field_id);
            // If equal
            if ($diff == 0){
                $event_permissions[$i]->current_value = $user_properties[$j]->value;
                // Validate with regex
                if (strlen($event_permissions[$i]->validation_rule) == 0 || preg_match($event_permissions[$i]->validation_rule, $user_properties[$j]->value)){
                    $event_permissions[$i]->satisfied = true;
                } else {
                    $eligible_to_register = false;
                }
            }
            // If $user_properties[$j]'s ASCII difference is less than $event_permissions[$i]
            else if ($diff > 0){
                // Try to repeat the loop
                // Increment $j but not $i
                $i--;
            }
            // Else if the field is not found
            else {
                // Is the field required or optional?
                if ($event_permissions[$i]->required){
                    $eligible_to_register = false;
                }
                // Increment $i but not $j
                $j--;
            }
        }
        // If the last required permissions is not satisfied
        if ($i > 0 && !isset($event_permissions[$i - 1]->satisfied) && $event_permissions[$i - 1]->required){
            $eligible_to_register = false;
        }
        // Check whether there are validations which have not been checked
        if ($i < count($event_permissions)) for (; $i < count($event_permissions); $i++){
            if ($event_permissions[$i]->required){
                $eligible_to_register = false;
            }
        }
        // Return a new stdClass
        return (object) [
            'eligible_to_register' => $eligible_to_register,
            'event_permissions' => $event_permissions
        ];
    }
}

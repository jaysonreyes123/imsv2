<?php
namespace App\Http\Helpers;

use App\Models\Agency;
use App\Models\CallLog;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Incident;
use App\Models\Media;
use App\Models\Module;
use App\Models\Preplan;
use App\Models\Report;
use App\Models\Resource;
use App\Models\Responder;
use App\Models\Task;
use App\Models\User;

class ModuleHelpers{
    public static function module_details_name($module){
        $model = Module::where("name",$module)->first();
        return $model;
    }
    public static function module_details_id($module){
        $model = Module::where("id",$module)->first();
        return $model;
    }
    public static function new($module){
        $model = null;
        switch ($module) {
            case 'incidents':
                $model = new Incident();
                break;
            case 'resources':
                $model = new Resource();
                break;
            case 'media':
                $model = new Media();
                break;
            case 'comments':
                $model = new Comment();
                break;
            case 'preplans':
                $model = new Preplan();
                break;
            case 'contacts':
                $model = new Contact();
                break;
            case 'agencies':
                $model = new Agency();
                break;
            case 'responders':
                $model = new Responder();
                break;
            case 'call_logs':
                $model = new CallLog();
                break;
            case 'tasks':
                $model = new Task();
                break;
            case 'users':
                $model = new User();
                break;
            default:
                # code...
                break;
        }
        return $model;
    } 
    public static function find($id,$module){
        $model = null;
        switch ($module) {
            case 'incidents':
                $model = Incident::find($id);
                break;
            case 'resources':
                $model = Resource::find($id);
                break;
            case 'media':
                $model = Media::find($id);
                break;
            case 'comments':
                $model = Comment::find($id);
                break;
            case 'preplans':
                $model = Preplan::find($id);
                break;
            case 'contacts':
                $model = Contact::find($id);
                break;
            case 'agencies':
                $model = Agency::find($id);
                break;
            case 'responders':
                $model = Responder::find($id);
                break;
            case 'call_logs':
                $model = CallLog::find($id);
                break;
            case 'tasks':
                $model = Task::find($id);
                break;
            case 'users':
                $model = User::find($id);
                break;
            default:
                # code...
                break;
        }
        return $model;
    }  

    public static function list($module){
        $model = null;
        switch ($module) {
            case 'incidents':
                $model = Incident::with('incident_types','incident_priorities','incident_statuses');
                break;
            case 'resources':
                $model = Resource::query();
                break;
            case 'media':
                $model = Media::with('assigned_to');
                break;
            case 'comments':
                $model = Comment::query();
                break;
            case 'preplans':
                $model = Preplan::query();
                break;
            case 'contacts':
                $model = Contact::query();
                break;
            case 'agencies':
                $model = Agency::query();
                break;
            case 'responders':
                $model = Responder::with('responder_types');
                break;
            case 'call_logs':
                $model = CallLog::query();
                break;
            case 'tasks':
                $model = Task::with('tasks_statuses');
                break;
            case 'users':
                $model = User::with('user_roles','user_privileges');
                break;
            case 'reports':
                $model = Report::query();
                break;
            default:
                # code...
                break;
        }
        return $model;
    } 
    public static function import($module){
        $model = null;
        switch ($module) {
            case 'incidents':
                $model = Incident::query();
                break;
            case 'resources':
                $model = Resource::query();
                break;
            case 'preplans':
                $model = Preplan::query();
                break;
            case 'contacts':
                $model = Contact::query();
                break;
            case 'agencies':
                $model = Agency::query();
                break;
            case 'responders':
                $model = Responder::query();
                break;
            case 'call_logs':
                $model = CallLog::query();
                break;
            case 'tasks':
                $model = Task::query();
                break;
            default:
                # code...
                break;
        }
        return $model;
    }

}
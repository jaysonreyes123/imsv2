import { agency_fields } from "./agency";
import { call_logs_fields } from "./call_logs";
import { comment_field } from "./comment";
import { contact_fields } from "./contacts";
import { incident_field } from "./incident";
import { media_field } from "./media";
import { preplan_fields } from "./preplan";
import { report_field } from "./report";
import { resouces_field } from "./resources";
import { responder_fields } from "./responder";
import { taks_fields } from "./task";
import { transcript } from "./transcript";
import { user_fields } from "./users";
export function GetFields(module:string){
    let fields:any;
    switch (module) {
        case 'incidents':
            fields = incident_field;
            break;
        case 'resources':
            fields = resouces_field;
            break;
        case 'preplans':
            fields = preplan_fields;
            break;
        case 'contacts':
            fields = contact_fields;
            break;
        case 'agencies':
            fields = agency_fields;
            break;
        case 'responders':
            fields = responder_fields;
            break;
        case 'call_logs':
            fields = call_logs_fields;
            break;
        case 'tasks':
            fields = taks_fields;
            break;
        case 'users':
            fields = user_fields;
            break;
        case 'reports':
            fields = report_field;
            break;
        case 'media':
            fields = media_field;
            break;
        case 'comments':
            fields = comment_field;
            break;
        case 'transcripts':
            fields = transcript;
            break;
        default:
            break;
    }
    return fields;
}
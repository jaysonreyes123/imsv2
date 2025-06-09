import { agency_column } from "./agency";
import { call_logs_column } from "./call_logs";
import { contact_column } from "./contacts";
import { incident_column } from "./incident";
import { insight_report_column } from "./insight-report";
import { media_column } from "./media";
import { preplan_column } from "./preplan";
import { report_column } from "./report";
import { resources_column } from "./resources";
import { responder_column } from "./responder";
import { task_column } from "./task";
import { user_column } from "./user";
import { pbx_column } from "./pbx";
export function GetColumn(module:string){
    incident_column
    let column:any;
        switch (module) {
            case 'incidents':
                column = incident_column;
                break;
            case 'resources':
                column = resources_column;
                break;
            case 'media':
                column = media_column;
                break;
            case 'preplans':
                column = preplan_column;
                break;
            case 'contacts':
                column = contact_column;
                break;
            case 'agencies':
                column = agency_column;
                break;
            case 'responders':
                column = responder_column;
                break;
            case 'call_logs':
                column = call_logs_column;
                break;
            case 'tasks':
                column = task_column;
                break;
            case 'users':
                column = user_column;
                break;
            case 'reports':
                column = report_column;
                break;
            case 'insight_reports':
                column = insight_report_column;
                break;
            case 'pbxes':
                column = pbx_column;
                break;
            default:
                break;
        }
        return column;
}
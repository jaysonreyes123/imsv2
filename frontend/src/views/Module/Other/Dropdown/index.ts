import { caller_types } from "./caller_types";
import { contact_statuses } from "./contact_statuses";
import { incident_priorities } from "./incident_priorities";
import { incident_statuses } from "./incident_statuses";
import { incident_types } from "./incident_types";
import { resources_categories } from "./resources_categories";
import { resources_statuses } from "./resources_statuses";
import { resources_types } from "./resources_types";
import { responder_types } from "./responder_types";
import { response_levels } from "./response_levels";
import { risk_levels } from "./risk_levels";
import { roles } from "./roles";
import { statuses } from "./statuses";
import { task_statuses } from "./task_statuses";
import { user_roles } from "./user_roles";

export default function local_dropdownlist(dropdown:string){
    let dropdown_data = null;
    switch (dropdown) {
        case 'incident_statuses':
            dropdown_data = incident_statuses;
            break;
        case 'incident_types':
            dropdown_data = incident_types;
            break;
        case 'incident_priorities':
            dropdown_data = incident_priorities;
            break;
        case 'responder_types':
            dropdown_data = responder_types;
            break;
        case 'caller_types':
            dropdown_data = caller_types;
            break;
        case 'contact_statuses':
            dropdown_data = contact_statuses;
            break;
        case 'resources_types':
            dropdown_data = resources_types;
            break;
        case 'resources_categories':
            dropdown_data = resources_categories;
            break;
        case 'resources_statuses':
            dropdown_data = resources_statuses;
            break;
        case 'response_levels':
            dropdown_data = response_levels;
            break;
        case 'risk_levels':
            dropdown_data = risk_levels;
            break;
        case 'statuses':
            dropdown_data = statuses;
            break;
        case 'user_roles':
            dropdown_data = user_roles;
            break;
        case 'roles':
            dropdown_data = roles;
            break;
        case 'tasks_statuses':
            dropdown_data = task_statuses;
            break;
        default:
            break;
    }
    return dropdown_data;
}
export const incident_column = [
    {
        label:"Incident ID",
        field:"incident_no",
        search:1
    },
    {
        label:"Incident Type",
        field:"incident_types.label",
        search:1
    },
    {
        label:"Incident Status",
        field:"incident_statuses.label",
        search:1
    },
    {
        label:"Incident Priority",
        field:"incident_priorities.label"
    },
    {
        label:"Created Time",
        field:"created_at"
    },
]
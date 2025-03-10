export const incident_field = [
    {
        title:"Incident Details",
        fields:[
            {
                label:"Incident No",
                name:"incident_no",
                type:"hidden",
            },
            {
                label:"Incident Types",
                name:"incident_types",
                type:"dropdown",
                summary:1
            },
            {
                label:"Time of Incident",
                name:"time_of_incident",
                type:"time",
                summary:1
            },
            {
                label:"Date of Incident",
                name:"date_of_incident",
                type:"date",
                summary:1
            },
            {
                label:"Incident Status",
                name:"incident_statuses",
                type:"dropdown",
                summary:1
            },
            {
                label:"Incident Priority",
                name:"incident_priorities",
                type:"dropdown",
            },
            {
                label:"Notes/Remarks",
                name:"remarks",
                type:"textarea",
                summary:1
            }
        ]
    },
    {
        title:"Location Details",
        fields:[
            {
                label:"location",
                name:"location",
                type:"text",
            },
            {
                label:"Street Name",
                name:"street_name",
                type:"text",
            },
            {
                label:"Nearest Landmark",
                name:"nearest_landmark",
                type:"text",
            },
            {
                label:"Coordinates",
                name:"coordinates",
                type:"coordinates",
                required:1,
                readonly:1
            }
        ]
    },
    {
        title:"Report Details",
        fields:[
            {
                label:"Firstname",
                name:"caller_firstname",
                type:"text",
            },
            {
                label:"Lastname",
                name:"caller_lastname",
                type:"text",
            },
            {
                label:"Contact",
                name:"caller_contact",
                type:"phone",
            },
        ]
    },
    {
        title:"Responder",
        fields:[
            {
                label:"Responder Team",
                name:"responder_types",
                type:"multiselect",
            },
            {
                label:"Assigned By",
                name:"assigned_by",
                type:"text",
            },
            {
                label:"Assigned Team",
                name:"assigned_team",
                type:"text",
            },
        ]
    },
    {
        title:"Incident Resolution",
        fields:[
            {
                label:"Incident Resolution",
                name:"incident_resolution",
                type:"textarea",
            },
        ]
    },
]
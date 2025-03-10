export const preplan_fields = [
    {
        title:"Incident Details",
        fields:[
            {
                label:"Pre Plan Name",
                name:"preplan_name",
                type:"text",
            },
            {
                label:"Incident Types",
                name:"incident_types",
                type:"dropdown",
            },
            {
                label:"Classification",
                name:"preplan_classifications",
                type:"dropdown",
            },
        ]
    },
    {
        title:"Response Procedure",
        fields:[
            {
                label:"Initial Assessment",
                name:"initial_assessment",
                type:"textarea",
            },
            {
                label:"Response Action",
                name:"response_action",
                type:"textatea",
            },
            {
                label:"Classification.",
                name:"classification",
                type:"textarea",
            },
        ]
    },
    {
        title:"Roles and Responsibilities",
        fields:[
            {
                label:"Incident Manager",
                name:"incident_manager",
                type:"text",
            },
            {
                label:"Incident Responder",
                name:"incident_responder",
                type:"text",
            },
            {
                label:"Support Staff",
                name:"support_staff",
                type:"text",
            },
        ]
    },
    {
        title:"Resource Allocation",
        fields:[
            {
                label:"Tools and Equipment",
                name:"tools_and_equipment",
                type:"textarea",
            },
            {
                label:"Personnel",
                name:"personnel",
                type:"textarea",
            },
        ]
    },
]
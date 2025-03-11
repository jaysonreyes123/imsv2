export const preplan_fields = [
    {
        title:"Resources Information",
        fields:[
            {
                label:"Pre-Plan Name",
                name:"preplan_name",
                type:"text",
            },
            {
                label:"Incident Type",
                name:"incident_types",
                type:"dropdown",
            },
            {
                label:"Location",
                name:"location",
                type:"text",
            },
            {
                label:"Risk level",
                name:"risk_levels",
                type:"dropdown",
            },
            {
                label:"Response Level",
                name:"response_levels",
                type:"dropdown",
            },
            {
                label:"Potential Impact",
                name:"potential_impact",
                type:"text",
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
        title:"Response Procedure",
        fields:[
            {
                label:"Incident Commander",
                name:"incident_commander",
                type:"text",
            },
            {
                label:"Liaison Officer",
                name:"liaison_officer",
                type:"text",
            },
            {
                label:"Safety Officer",
                name:"safety_officer",
                type:"text",
            },
            {
                label:"Public Information Officer",
                name:"public_information_officer",
                type:"text",
            },
            {
                label:"Operations Section Chief",
                name:"operations_section_chief",
                type:"text",
            },
            {
                label:"Planing Section Chief",
                name:"planing_section_chief",
                type:"text",
            },
            {
                label:"logistics Section Chief",
                name:"logistics_section_chief",
                type:"text",
            },
            {
                label:"Finance/Admin Section Chief",
                name:"finance_admin_section_chief",
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
export  const resouces_field = [
    {
        title:"Resources Information",
        fields:[
            {
                label:"Resources Name",
                name:"resources_name",
                type:"text",
            },
            {
                label:"Resource Type",
                name:"resources_types",
                type:"dropdown",
                option:"set",
                target:"resources_categories"
            },
            {
                label:"Resource Category",
                name:"resources_categories",
                type:"dropdown",
                option:"get",
            },
            {
                label:"Quantity",
                name:"quantity",
                type:"number",
                default_value:1
            },
            {
                label:"Resource Status",
                name:"resources_statuses",
                type:"dropdown",
            },
            {
                label:"Contact Info",
                name:"contact_info",
                type:"text",
            },
            {
                label:"Owner",
                name:"owner",
                type:"text",
            },
            {
                label:"Date Acquired",
                name:"date_acquired",
                type:"date",
            },
            {
                label:"Dispatch",
                name:"resources_dispatchers",
                type:"dropdown",
            },
            {
                label:"Condition",
                name:"resources_conditions",
                type:"dropdown",
            },
            {
                label:"Remarks",
                name:"remarks",
                type:"textarea",
            },
            {
                label:"Last Assigned",
                name:"last_assigned",
                type:"hidden",
            }
        ]
    },
    {
        title:"Location Details",
        fields:[
            {
                label:"Coordinates",
                name:"coordinates",
                type:"text",
                required:1,
                readonly:1
            }
        ]
    },
]
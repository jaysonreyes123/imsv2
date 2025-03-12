export const responder_fields = [
    {
        title:"Basic information",
        fields:[
            {
                label:"Responder Type",
                name:"responder_types",
                type:"dropdown",
            },
            {
                label:"First Name",
                name:"firstname",
                type:"text",
            },
            {
                label:"Lastname",
                name:"lastname",
                type:"text",
            },
            {
                label:"Contact No",
                name:"contact_no",
                type:"phone",
            },
        ]
    },
    {
        title:"Login information",
        fields:[
            {
                label:"Email Address",
                name:"email_address",
                type:"email",
            },
            {
                label:"Password",
                name:"password",
                type:"text",
            },
            {
                label:"Assigned To",
                name:"assigned_to",
                type:"text",
            },
            {
                label:"Status",
                name:"statuses",
                type:"dropdown",
            },
        ]
    },
]
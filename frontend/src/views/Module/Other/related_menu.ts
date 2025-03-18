interface State{
    [index:string] : any
}
export default function related_menu(module:string){
    const related_menu:State = 
        {
            incidents:[
                {
                    label:"Comment",
                    name:"comments",
                    related:true,
                    action:[],
                },
                {
                    label:"Media",
                    name:"media",
                    related:true,
                    action:['add']
                },
                {
                    label:"Resources",
                    name:"resources",
                    related:true,
                    action:['select']
                },
                {
                    label:"Responder",
                    name:"responders",
                    related:true,
                    action:[]
                },
                {
                    label:"Pre Plan",
                    name:"preplans",
                    related:true,
                    action:['select']
                },
                {
                    label:"Action Plan",
                    name:"tasks",
                    related:true,
                    action:['add']
                },
            ],
            resources:[
                {
                    label:"Comment",
                    name:"comments",
                    related:true,
                    action:[],
                },
                {
                    label:"Media",
                    name:"media",
                    related:true,
                    action:['add']
                },
            ],
        }
    return related_menu[module];
}
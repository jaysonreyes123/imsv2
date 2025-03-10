import { useModuleStore } from "@/stores/module"

const module_details = (module:string) => {
    const module_store = useModuleStore();
    return module_store.modules.find((_:any) => _.name == module);
}

const module_details_by_id = (module:string) => {
    const module_store = useModuleStore();
    return module_store.modules.find((_:any) => _.id == module);
}

export {module_details,module_details_by_id};
const phoneValidation = (label:string,value:string) => {
    let phonenumber:any = value.replace(/[^0-9+]/g, '');
    let maxlength:number = 0;
    let message:string = "";
    if(phonenumber[0] == 0 && phonenumber[1] == 9){
        maxlength = 11;
    }
    else if(phonenumber[0] == 6 && phonenumber[1] == 3 && phonenumber[2] == 9){
        maxlength = 12;
    }
    else if(phonenumber[0] == "+" && phonenumber[1] == 6 && phonenumber[2] == 3 && phonenumber[3] == 9){
        maxlength = 13;
    }
    else{
        maxlength = 0
            message = label+" must be valid phone";
    }
    if(maxlength != 0){
        if(phonenumber.length >= maxlength){
            message = "";
            phonenumber = phonenumber.slice(0,maxlength);
        }
        else{
            message = label+" must be valid phone";
        }   
    }
    return {phone:phonenumber,message:message,maxlength:maxlength};
}

const emailValidation = (label:string,value:string)  => {
    var email = value.match(/^\S+@\S+\.\S+$/);
    let message = "";
    if(email){
        message = "";
    }
    else{
        message = label+" must be valid email";
    }
    return {email:email,message:message};
}

const numberValidation = (value:any) => {
    return value.replace(/\D/g,"");
}
export {phoneValidation,emailValidation,numberValidation};
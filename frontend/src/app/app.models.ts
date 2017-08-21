

export interface Validation {
    validate(): boolean;
}

export class LoginForm implements Validation {
    email: string;
    password: string;


    validate(): boolean {
        return false;
    }
}


export class RegistrationForm implements Validation {
    firstName: string;

    lastName: string;

    email: string;

    confirmEmail: string;

    password: string;

    confirmPassword: string;


    validate(): boolean {
        return false;
    }
}
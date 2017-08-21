
import { Injectable } from "@angular/core";
import { Http } from "@angular/http";
import {Observable} from "rxjs/Observable";


export class SessionToken {
    public token: string;

    constructor(token?: string) {
        if (token) {
            this.token = token;
            localStorage.setItem('api_token', token);
        } else {
            this.token = localStorage.getItem('api_token');
        }
    }
}

/**
 *  For making it easy to specify parameters for User Registration.
 */
export interface UserRegistration {
    email           : string;
    confirmEmail    : string;
    name            : string;
    password        : string;
    confirmPassword : string;
}

@Injectable()
export class Auth {

    static LOGIN = "/api/auth";
    static REGISTER = "/api/auth/create";

    protected authenticated: boolean;

    constructor(private http: Http) {}

    login(email: string, password: string) : Observable<any> {
        const body = {
            email: email,
            password: password
        };

        return this.http.post(Auth.LOGIN, body);
    }

    register(user: UserRegistration): Observable<any> {
        return this.http.post(Auth.REGISTER, user);
    }
}
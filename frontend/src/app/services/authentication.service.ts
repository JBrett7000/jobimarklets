
import { Injectable } from "@angular/core";
import { Http } from "@angular/http";
import {Observable} from "rxjs/Observable";


export class SessionToken {
    public token: string;

    constructor(token: string) {
        this.token = token;
    }
}


@Injectable()
export class Auth {

    static LOGIN = "/auth";

    protected authenticated: boolean;

    constructor(private http: Http) {}

    login(email: string, password: string) : Observable<any> {
        let response: any;

        const body = {
            email: email,
            password: password
        };

        return this.http.post(Auth.LOGIN, body);
    }
}
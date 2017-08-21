/**
 * Created by ndy40 on 09/03/2017.
 */
import { Component } from '@angular/core';

import { LoginForm} from "../app.models";
import { Auth, SessionToken } from "../services/authentication.service";
import {Router} from "@angular/router";


@Component({
    selector: 'login-module',
    templateUrl: './login.component.html',
    providers: [ Auth ]
})
export class LoginComponent {

    loginForm: LoginForm = new LoginForm();

    errorMessage: string;

    sessionToken: SessionToken;

    constructor(private service: Auth, private router: Router) {}

    login(): void {
        this.errorMessage = '';

        this.service.login(this.loginForm.email, this.loginForm.password)
            .subscribe(data => {
                this.sessionToken = new SessionToken(data._body);
                this.router.navigate(["/home"]);
            },error => this.loginError(error));
    }

    loginError(message: any): void {

        if (message._body == "false") {
            this.errorMessage = "username and password can't be found.";
        } else {
            let error = JSON.parse(message._body);
            this.errorMessage = error.message;
        }
    }

    clearLogin():void {
        this.loginForm = new LoginForm();
        this.errorMessage = '';
    }
}
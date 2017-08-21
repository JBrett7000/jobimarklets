
import { Component} from "@angular/core";
import {RegistrationForm} from "../app.models";
import {Auth} from "../services/authentication.service";

@Component({
    selector: 'registeration-module',
    templateUrl: './registration.component.html',
    providers: [ Auth ]
})
export class RegistrationComponent {

    form: RegistrationForm = new RegistrationForm();

    successStatus: boolean = false;

    errorStatus: boolean = false;

    message: string;

    constructor(private auth: Auth){}

    register():void {

        this.successStatus = false;
        this.errorStatus = false;

        if (this.form.firstName == null || this.form.lastName == null) {
            this.errorStatus = true;
            this.message = "First Name or Last Name cannot be empty";
            return;
        }

        this.auth.register({
            name: this.form.firstName + ' ' + this.form.lastName,
            email: this.form.email,
            confirmEmail: this.form.confirmEmail,
            password: this.form.password,
            confirmPassword: this.form.confirmPassword
        }).subscribe(
            x => this.updateNotification(true, x._body),
            error => this.updateNotification(false, JSON.parse(error._body))
        );
    }

    updateNotification(success: boolean, message: any): void {
        switch (success) {
            case true:
                this.successStatus = true;
                this.message = 'Account successfully created. Check your email to activate your account.';
                break;
            case false:
                this.errorStatus = true;
                this.message = message.message;
                break;
        }

    }

    clear():void {
        this.successStatus = this.errorStatus = false;
        this.form = new RegistrationForm();
    }

    getStatusClass(): string {
        if (this.successStatus) {
            return "is-success";
        } else if (this.errorStatus) {
            return "is-danger";
        }

        return "";
    }
}
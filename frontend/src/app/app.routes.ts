import {Routes} from "@angular/router";

import { AppComponent} from "./app.component";
import { RegistrationComponent } from './registration/registration.component';
import  {LoginComponent} from "./login/login.component";


export const routes: Routes = [
    { path: '', redirectTo: 'home', pathMatch: 'full'},
    { path: 'home', component: LoginComponent },
    { path: 'register', component: RegistrationComponent }
];
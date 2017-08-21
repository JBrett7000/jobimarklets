import {Routes} from "@angular/router";

import { AppComponent} from "./app.component";
import { RegistrationComponent } from './registration/registration.component';
import  {LoginComponent} from "./login/login.component";
import { HomeComponent} from "./home/home.component";

export const routes: Routes = [
    { path: '', redirectTo: 'login', pathMatch: 'full'},
    { path: 'login', component: LoginComponent },
    { path: 'register', component: RegistrationComponent },
    { path: 'home', component: HomeComponent }
];
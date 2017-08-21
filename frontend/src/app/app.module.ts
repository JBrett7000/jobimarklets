import { NgModule } from '@angular/core';
import { BrowserModule }  from '@angular/platform-browser';
import { FormsModule} from "@angular/forms";
import { HttpModule } from "@angular/http";
import { RouterModule} from "@angular/router";

import { AppComponent} from './app.component';
import {RegistrationComponent} from "./registration/registration.component";
import { LoginComponent} from "./login/login.component";
import { HomeComponent} from "./home/home.component";
import { routes } from './app.routes';

@NgModule({
    imports : [
        BrowserModule,
        FormsModule,
        HttpModule,
        RouterModule.forRoot(routes,{useHash: true})
    ],
    declarations: [
        AppComponent,
        RegistrationComponent,
        LoginComponent,
        HomeComponent
    ],
    bootstrap: [ AppComponent ]
})
export class AppModule {}
import {NgModule} from "@angular/core";
import {HttpClientModule} from "@angular/common/http";
import {BrowserModule} from "@angular/platform-browser";
import {AppComponent} from "./app.component";
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {NguiMapModule} from "@ngui/map";
import {JwtModule} from "@auth0/angular-jwt";



const moduleDeclarations = [AppComponent];

const JwtHelper = JwtModule.forRoot({
    config: {
        tokenGetter: () => {
            return localStorage.getItem("jwt-token");
        },
        skipWhenExpired: true,
        whitelistedDomains: ["localhost:7272", "https:bootcamp-coders.cnm.edu/"],
        headerName: "X-JWT-TOKEN",
        authScheme: ""
    }
});

@NgModule({
    imports:      [BrowserModule, HttpClientModule, FormsModule, ReactiveFormsModule, routing, NguiMapModule.forRoot({apiUrl: 'https://maps.google.com/maps/api/js?key=AIzaSyBMQE2mPIzXsRIbSUWzBUwiJrdrp80Xkqc'}), JwtHelper],
    declarations: [...moduleDeclarations, ...allAppComponents],
    bootstrap:    [AppComponent],
    providers:    [...appRoutingProviders]
})

export class AppModule {}
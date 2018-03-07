import {NgModule} from "@angular/core";
import {HttpClientModule} from "@angular/common/http";
import {BrowserModule} from "@angular/platform-browser";
import {AppComponent} from "./app.component";
import {allAppComponents, appRoutingProviders, routing} from "./app.routes";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";

const moduleDeclarations = [AppComponent];

@NgModule({
    imports:      [BrowserModule, HttpClientModule, FormsModule, ReactiveFormsModule, routing],
    declarations: [...moduleDeclarations, ...allAppComponents],
    bootstrap:    [AppComponent],
    providers:    [...appRoutingProviders]
})
export class AppModule {}